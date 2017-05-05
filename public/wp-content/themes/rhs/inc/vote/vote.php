<?php

/**
 * Essa classe implementa tudo relacionado ao esquema de votação
 *
 * * Registra os post status novos
 * * Registra o novo role e capability e as regras para que os usuários ganhem essas permissões
 * * Todas as ações para registrar os votos
 *
 */
Class RHSVote {

	const VOTING_QUEUE = 'voting-queue';
	const VOTING_EXPIRED = 'voting-expired';
	const PUBLISH = 'publish';
	const ROLE_VOTER = 'voter';

	static $instance;

	var $tablename;

	var $post_status = [];

	var $total_meta_key = '_total_votes';

	var $days_for_expired;
	var $days_for_expired_default = 14;

	var $votes_to_approval;
	var $votes_to_approval_default = 5;

	function __construct() {

		if ( empty( self::$instance ) ) {
			global $wpdb;
			$this->tablename   = $wpdb->prefix . 'votes';
			$this->post_status = $this->get_custom_post_status();

			$days_for_expired  = get_option( 'vq_days_for_expired' );
			$votes_to_approval = get_option( 'vq_votes_to_approval' );

			$this->days_for_expired  = $days_for_expired ? $days_for_expired : $this->days_for_expired_default;
			$this->votes_to_approval = $votes_to_approval ? $votes_to_approval : $this->votes_to_approval_default;

			// Hooks
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_footer-post.php', array( &$this, 'add_status_dropdown' ) );

			add_action( 'wp_ajax_rhs_vote', array( &$this, 'ajax_vote' ) );
			add_action( 'rhs_votebox', array( &$this, 'get_vote_box' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'addJS' ) );

			add_filter( 'map_meta_cap', array( &$this, 'vote_post_cap' ), 10, 4 );

			add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
			add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
			add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );

			add_action( 'pre_get_posts', array( &$this, 'fila_query' ) );

			add_action( 'admin_menu', array( &$this, 'gerate_admin_menu' ) );
			/**
			 * ROLES
			 */
			$option_name = 'roles_edited';
			if ( ! get_option( $option_name ) ) {

				// só queremos que isso rode uma vez
				add_option( $option_name, true );

				global $wp_roles;

				$contributor = $wp_roles->get_role( 'contributor' );

				// Criamos o role voter copiando as capabilites de author
				$voter = $wp_roles->add_role( self::ROLE_VOTER, 'Votante', $contributor->capabilities );

				// Adicionamos a capability de votar a todos os roles que devem
				$voter->add_cap( 'vote_posts' );

				$editor = $wp_roles->get_role( 'editor' );
				$editor->add_cap( 'vote_posts' );

				$administrator = $wp_roles->get_role( 'administrator' );
				$administrator->add_cap( 'vote_posts' );

			}

			/**
			 * DATABASE TABLE
			 */
			$option_name = 'database';
			if ( ! get_option( $option_name ) ) {

				// só queremos que isso rode uma vez
				add_option( $option_name, true );

				$createQ = "
                CREATE TABLE IF NOT EXISTS `$this->tablename` (
                    ID bigint(20) unsigned NOT NULL auto_increment PRIMARY KEY,
                    post_id bigint(20) unsigned NOT NULL default '0',
                    user_id tinytext NOT NULL,
                    vote_date datetime NOT NULL default CURRENT_TIMESTAMP,
                    vote_source varchar(20) NOT NULL default '0.0.0.0'
                )
            ";

				$wpdb->query( $createQ );

			}

			self::$instance = true;
		}

	}

	function get_custom_post_status() {
		return array(

			self::VOTING_QUEUE => array(
				'label'                     => 'Fila de votação',
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Fila <span class="count">(%s)</span>',
					'Fila <span class="count">(%s)</span>' ),
			),

			self::VOTING_EXPIRED => array(
				'label'                     => 'Não promovidos',
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Não promovido <span class="count">(%s)</span>',
					'Não promovidos <span class="count">(%s)</span>' ),
			)

		);
	}

	function addJS() {
		wp_enqueue_script( 'rhs_vote', get_template_directory_uri() . '/inc/vote/vote.js', array( 'jquery' ) );
		wp_localize_script( 'rhs_vote', 'vote', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	function init() {

		// Registra post status
		foreach ( $this->post_status as $post_status => $args ) {
			register_post_status( $post_status, $args );
		}

	}

	function rewrite_rules( &$wp_rewrite ) {
		$new_rules         = array(
			"fila-de-votacao/?$" => "index.php?filavotacao=1",
			"filavotacao/?$"     => "index.php?filavotacao=1",
		);
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

	}

	function rewrite_rules_query_vars( $public_query_vars ) {

		$public_query_vars[] = "filavotacao";

		return $public_query_vars;

	}

	function rewrite_rule_template_include( $template ) {
		global $wp_query;

		if ( $wp_query->get( 'filavotacao' ) ) {

			if ( file_exists( STYLESHEETPATH . '/fila-de-votacao.php' ) ) {
				return STYLESHEETPATH . '/fila-de-votacao.php';
			}

		}

		return $template;


	}

	function fila_query( $wp_query ) {

		if ( $wp_query->is_main_query() && $wp_query->get( 'filavotacao' ) ) {

			$args = array(
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'    => self::VOTING_QUEUE,
				'posts_per_page' => - 1,
			);

			foreach ( $args as $k => $v ) {
				$wp_query->set( $k, $v );
			}

		}


	}

	function add_status_dropdown() {
		global $post;
		$complete = '';
		$label    = '';
		if ( $post->post_type == 'post' ) {

			$js                  = '';
			$change_status_label = false;

			foreach ( $this->post_status as $post_status => $args ) {

				$selected = '';

				if ( $post->post_status == $post_status ) {
					$selected            = 'selected';
					$change_status_label = $args['label'];

				}

				$js .= '$("select#post_status").append("<option value=\'' . $post_status . '\' ' . $selected . '>' . $args['label'] . '</option>");';

			}

			if ( $change_status_label !== false ) {
				$js .= '$("#post-status-display").append("' . $change_status_label . '");';
			}

			echo '
                <script>
                    jQuery(document).ready(function($){
                        ' . $js . '
                    });
                </script>
            ';
		}
	}

	function vote_post_cap( $caps, $cap, $user_id, $args ) {

		if ( $cap == 'vote_post' ) {

			$caps = array();

			$post = get_post( $args[0] );

			if ( $post ) {

				if ( strtotime( $post->post_date ) < strtotime( '-' . $this->days_for_expired . ' days' ) ) {
					$caps[] = 'vote_old_posts';
					$this->check_votes_to_expire( $post );
				} elseif ( $this->user_has_voted( $post->ID, $user_id ) ) {
					$caps[] = 'vote_posts_again';
				} elseif ( $post->post_author == $user_id ) {
					$caps[] = 'vote_own_posts';
				} else {
					$caps[] = 'vote_posts';
				}

			}
		}

		return $caps;
	}

	function ajax_vote() {

		if ( isset( $_POST['post_id'] ) && is_numeric( $_POST['post_id'] ) ) {

			if ( current_user_can( 'vote_post', $_POST['post_id'] ) ) {

				$this->add_vote( $_POST['post_id'], get_current_user_id() );
				$this->get_vote_box( $_POST['post_id'] );

			}

		}

		die;

	}

	function add_vote( $post_id, $user_id = null ) {

		global $wpdb;

		if ( is_null( $user_id ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		// Adiciona voto na table se ainda não houver
		if ( ! $this->user_has_voted( $post_id, $user_id ) ) {
			$wpdb->insert( $this->tablename, array(
				'user_id'     => $user_id,
				'post_id'     => $post_id,
				'vote_source' => $_SERVER['REMOTE_ADDR']
			) );
		}

		$this->update_vote_count( $post_id );
		$this->check_votes_to_upgrade( $post_id );

	}

	function update_vote_count( $post_id ) {

		global $wpdb;

		$numVotes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $this->tablename WHERE post_id = %d",
			$post_id ) );

		update_post_meta( $post_id, $this->total_meta_key, $numVotes );

	}

	function get_total_votes( $post_id ) {
		return get_post_meta( $post_id, $this->total_meta_key, true );

	}

	function get_total_votes_by_author( $user_id ) {

		global $wpdb;

		$total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM rhs_votes WHERE post_id IN (SELECT ID FROM rhs_posts WHERE post_author = %d)",
			$user_id ) );

		return $total;
	}

	function user_has_voted( $post_id, $user_id = null ) {

		global $wpdb;

		if ( is_null( $user_id ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		// Verifica se este usuário já votou neste post
		$vote = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $this->tablename WHERE user_id = %d AND post_id = %d",
			$user_id, $post_id ) );

		return sizeof( $vote ) > 0;

	}

	function get_vote_box( $post_id, $echo = true ) {

		$output     = '<div id="votebox-' . $post_id . '">';
		$totalVotes = $this->get_total_votes( $post_id );
		if ( empty( $totalVotes ) ) {
			$totalVotes = 0;
		}

		$output .= '<span class="vTexto">' . $totalVotes . '</span>';


		// TODO: vai haver uma meta capability vote_post,
		// Se o usuário ja votou neste post, não aparece o botão e aparece de alguma maneira que indique q ele já votou
		// Se ele não estiver logado, aparece só o texto "Votos"

		if ( current_user_can( 'vote_post', $post_id ) ) {
			$output .= '<span class="vButton"><a class="btn btn-danger js-vote-button" data-post_id="' . $post_id . '">VOTAR</a></span>';
		} else {
			if ( is_user_logged_in() && $this->user_has_voted( $post_id ) ) {

				$output .= '<span class="vButton"><a class="btn btn-danger js-vote-button" data-post_id="' . $post_id . '" disabled><i class="glyphicon glyphicon-ok"></i></a></span>';

			} else {
				$output .= '<span class="vTexto">Votos</span>';
			}

		}

		$output .= '</div>';

		if ( $echo ) {
			echo $output;
		}

		return $output;


	}

	function change_post_status( $data, $postarr ) {

		global $pagenow;

		// Apenas novos posts
		if ( $pagenow != 'post.php' ) {
			$data['post_status'] = self::VOTING_QUEUE;
		}

		return $data;
	}

	function check_votes_to_expire( WP_Post $post ) {

		if ( $post->post_status != self::VOTING_QUEUE ) {
			return;
		}

		if ( $this->get_total_votes( $postID ) >= $this->votes_to_approval ) {
			return;
		}

		$new_post = array(
			'ID'          => $post->ID,
			'post_status' => self::VOTING_EXPIRED
		);

		wp_update_post( $new_post );
	}

	function check_votes_to_upgrade( $postID ) {


		if ( $this->get_total_votes( $postID ) < $this->votes_to_approval ) {
			return;
		}

		$status = get_post_status( $postID );

		if ( $status != self::VOTING_QUEUE ) {
			return;
		}

		$new_post = array(
			'ID'          => $postID,
			'post_status' => self::PUBLISH
		);

		wp_update_post( $new_post );

		$this->update_user_role( $postID );
	}

	function update_user_role( $postID ) {

		if ( ! $post = get_post( $postID ) ) {
			return;
		}

		$user = get_userdata( $post->post_author );

		if ( ! $user->roles || ! in_array( "contributor", $user->roles ) ) {
			return;
		}

		$user_new = array(
			'ID'   => $user->ID,
			'role' => self::ROLE_VOTER
		);

		wp_update_user( $user_new );
	}

	function gerate_admin_menu() {
		/*/add_menu_page( 'RHS Menu', 'RHS Menu', 'manage_options', 'rhs/rhs-admin-page.php', 'rhs_admin_page',
			'dashicons-lock', 30 );
		add_submenu_page( 'rhs/rhs-admin-page.php', 'RHS Menu', 'RHS Menu', 'manage_options', 'rhs/rhs-admin-page.php',
			'rhs_admin_page' );*/
		add_options_page( 'Fila de votação', 'Fila de votação', 'manage_options',
			'rhs/rhs-fila-de-votacao.php', array( &$this, 'rhs_admin_page_voting_queue' ) );
	}

	function rhs_admin_page_voting_queue() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$labels = array(
			'vq_days_for_expired'  => array(
				'name'    => __( "Dias para expiração:" ),
				'type'    => 'select',
				'default' => $this->days_for_expired_default
			),
			'vq_votes_to_approval' => array(
				'name'    => __( "Votos para aprovação:" ),
				'type'    => 'select',
				'default' => $this->votes_to_approval_default
			),
			'vq_description'       => array(
				'name'    => __( "Texto introdutório:" ),
				'type'    => 'textarea',
				'default' => ''
			)
		);

		$i = 0;
		if ( ! empty( $_POST ) ) {
			foreach ( $labels as $label => $attr ) {

				if ( empty( $_POST ) ) {
					continue;
				}

				update_option( $label, $_POST[ $label ] );

				if ( $i == 0 ) {

					?>
                    <div class="updated">
                        <p>
                            <strong><?php _e( 'Configurações salva.' ); ?></strong>
                        </p>
                    </div>
					<?php
				}

				$i ++;
			}
		}

		?>
        <div class="wrap">
            <h2><?php echo __( 'Fila de votação' ); ?></h2>
            <form name="form1" method="post" action="">
                <table class="form-table">
                    <tbody>
					<?php foreach ( $labels as $label => $attr ) { ?>
						<?php

						$default = $attr['default'];
						$value   = get_option( $label );

						?>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo $label; ?>"><?php echo $attr['name']; ?></label>
                            </th>
                            <td>
								<?php if ( $attr['type'] == 'select' ) { ?>
                                    <select name="<?php echo $label; ?>" id="<?php echo $label; ?>">
										<?php for ( $i = 1; $i <= 50; $i ++ ) { ?>
                                            <?php if($i == $default){ ?>
                                                <option value="" <?php echo $value == '' ? 'selected' : ''; ?> >Padrão (<?php echo $default; ?>)</option>
                                            <?php } ?>
                                            <option <?php echo $value == $i ? 'selected' : ''; ?> ><?php echo $i ?></option>
										<?php } ?>
                                    </select>
                                    <p><i><?php echo __( 'Valor padrão: ' ) . $default; ?></i></p>
								<?php } ?>
								<?php if ( $attr['type'] == 'textarea' ) { ?>
                                    <textarea name="<?php echo $label; ?>" id="<?php echo $label; ?>" class="large-text"
                                              rows="5"><?php echo $value; ?></textarea>
								<?php } ?>
                            </td>
                        </tr>
					<?php } ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary"
                           value="<?php esc_attr_e( 'Save Changes' ) ?>"/>
                </p>
            </form>
        </div>

		<?php
	}
}

global $RHSVote;
$RHSVote = new RHSVote();
