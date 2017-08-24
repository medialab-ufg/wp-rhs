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
	const ROLE_VOTER = 'voter';
	const META_PUBISH = 'rhs-promoted-publish';

	static $instance;

	var $tablename;

	var $post_status = [];

	var $total_meta_key = '_total_votes';

	public $days_for_expired_default = 14;
    public $votes_to_approval_default = 5;
    public $votes_to_text_help;

	function __construct() {

		if ( empty( self::$instance ) ) {
			global $wpdb;
			$this->tablename   = $wpdb->prefix . 'votes';
			$this->post_status = $this->get_custom_post_status();

			// Hooks
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_footer-post.php', array( &$this, 'add_status_dropdown' ) );

			add_action( 'wp_ajax_rhs_vote', array( &$this, 'ajax_vote' ) );
			add_action( 'rhs_votebox', array( &$this, 'get_vote_box' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'addJS' ) );

			add_filter( 'map_meta_cap', array( &$this, 'vote_post_cap' ), 10, 4 );

			add_action( 'pre_get_posts', array( &$this, 'fila_query' ) );

			add_action( 'admin_menu', array( &$this, 'gerate_admin_menu' ) );

            $this->verify_role();
            $this->verify_database();
            $this->verify_params();

            $this->votes_to_approval = get_option('vq_votes_to_approval');
            $this->days_for_expired = get_option('vq_days_for_expired');

			self::$instance = true;
		}

	}

	private function verify_role(){
        /**
         * ROLES
         */
        $option_name = 'roles_edited_'.get_class();
        if ( ! get_option( $option_name ) ) {

            // só queremos que isso rode uma vez
            add_option( $option_name, true );

            global $wp_roles;

            $contributor = $wp_roles->get_role( 'contributor' );
            $contributor->add_cap( 'upload_files' );

            // Criamos o role voter copiando as capabilites de author
            $wp_roles->remove_role(self::ROLE_VOTER);
            $voter = $wp_roles->add_role( self::ROLE_VOTER, 'Votante', $contributor->capabilities );
            $voter = $wp_roles->get_role( self::ROLE_VOTER );

            // Adicionamos a capability de votar a todos os roles que devem
            $voter->add_cap( 'vote_posts' );

            $editor = $wp_roles->get_role( 'editor' );
            $editor->add_cap( 'vote_posts' );

            $administrator = $wp_roles->get_role( 'administrator' );
            $administrator->add_cap( 'vote_posts' );

        }
    }

    private function verify_database(){
        /**
         * DATABASE TABLE
         */
        $option_name = 'database_'.get_class();
        if ( ! get_option( $option_name ) ) {

            // só queremos que isso rode uma vez
            add_option( $option_name, true );

            $createQ = "
                CREATE TABLE IF NOT EXISTS `$this->tablename` (
                    ID bigint(20) unsigned NOT NULL auto_increment PRIMARY KEY,
                    post_id bigint(20) unsigned NOT NULL default '0',
                    user_id tinytext NOT NULL,
                    vote_date datetime NOT NULL default '0000-00-00 00:00:00',
                    vote_source varchar(20) NOT NULL default '0.0.0.0'
                )
            ";
            global $wpdb;
            $wpdb->query( $createQ );

        }
    }

    private function verify_params(){

        if(!get_option( 'vq_days_for_expired' )){
            add_option('vq_days_for_expired', $this->days_for_expired_default);
        }

        if(!get_option( 'vq_votes_to_approval' )){
            add_option('vq_votes_to_approval', $this->votes_to_approval_default);
        }

        if(!get_option( 'vq_text_explanation' )){
            add_option('vq_text_explanation', 'Você não tem permissão para votar');
        }

        if(!get_option( 'vq_text_vote_old_posts' )){
            add_option('vq_text_vote_old_posts',  'Infelizmente esse post não pode ser mais votado, sua data de votação já passou.');
        }

        if(!get_option( 'vq_text_vote_posts_again' )){
            add_option('vq_text_vote_posts_again',  'Infelizmente você não pode votar em um post mais de uma vez.');
        }

        if(!get_option( 'vq_text_vote_own_posts' )){
            add_option('vq_text_vote_own_posts',  'Infelizmente você não pode votar no seu proprio post.');
        }

        if(!get_option( 'vq_text_vote_posts' )){
            add_option('vq_text_vote_posts',  'Infelizmente você não pode votar em um post, para saber como se tornar um votante <a href="%s">leia aqui</a>.');
        }

        if(!get_option( 'vq_text_vote_update' )){
            add_option('vq_text_vote_update',  'Parabéns, seu voto foi contabilizado, aguarde e veremos se ele será aprovado.');
        }

        if(!get_option( 'vq_text_post_promoted' )){
            add_option('vq_text_post_promoted',  'Parabens, com o seu voto o post será aprovado e irá para a página incial');
        }

    }

	public function getTextHelp(){

	    if($this->votes_to_text_help){
	        return $this->votes_to_text_help;
        }

	    return get_option('vq_text_explanation');
    }

	function get_custom_post_status() {
		return array(

			self::VOTING_EXPIRED => array(
				'label'                     => 'Não promovidos',
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Não promovido <span class="count">(%s)</span>',
					'Não promovidos <span class="count">(%s)</span>' ),
			),
            self::VOTING_QUEUE => array(
                'label'                     => 'Fila de votação',
                'public'                    => false,
                'exclude_from_search'       => true,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Fila <span class="count">(%s)</span>',
                    'Fila <span class="count">(%s)</span>' ),
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

	function fila_query( $wp_query ) {

        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_login_tpl' ) == RHSRewriteRules::VOTING_QUEUE_URL ) {

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

        } elseif ($wp_query->is_main_query() && $wp_query->get('post_type') == '' && ( $wp_query->is_author() || $wp_query->is_single() ) ) {

            // No perfil do usuário, exibir posts de todos os status
            // Permite que pessoas vejam a single dos posts com status Fila de Votação ou expirados
            // A checagem pelo post type vazio é para ser aplicado apenas no post týpe padrão (post) e não em outros, como o ticket, por exmeplo

            $statuses = ['publish', self::VOTING_QUEUE, self::VOTING_EXPIRED];
            if (is_user_logged_in())
                $statuses[] = 'private';

            $wp_query->set('post_status', $statuses);

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
                    $this->votes_to_text_help = get_option('vq_text_vote_old_posts');
					$this->check_votes_to_expire( $post );
				} elseif ( $this->user_has_voted( $post->ID, $user_id ) ) {
                    $this->votes_to_text_help = get_option('vq_text_vote_posts_again');
					$caps[] = 'vote_posts_again';
				} elseif ( $post->post_author == $user_id ) {
                    $this->votes_to_text_help = get_option('vq_text_vote_own_posts');
					$caps[] = 'vote_own_posts';
				} else {
                    $this->votes_to_text_help = sprintf(get_option('vq_text_vote_posts'), get_permalink(get_option('vq_page_explanation')));
					$caps[] = 'vote_posts';
				}
			}
		}

		return $caps;
	}

	function ajax_vote() {

        $json = array();

        if ( empty( $_POST['post_id'] ) || !is_numeric( $_POST['post_id'] ) ) {
            $json = array('error' => array('text'=>'Não foi encontrado o usuário.'));

            echo json_encode($json);
            exit;
        }

        if ( !current_user_can( 'vote_post', $_POST['post_id'] ) ) {
            $json = array('error' => array('text'=>$this->getTextHelp()));

            echo json_encode($json);
            exit;
        }


        $this->add_vote( $_POST['post_id'], get_current_user_id() );
        $box = $this->get_vote_box( $_POST['post_id'], false);

        $json = array('success' => array('html' =>$box, 'text' => $this->getTextHelp()));
        echo json_encode($json);
        exit;

	}

	function add_vote( $post_id, $user_id = null ) {

		global $wpdb;
		global $RHSPosts;

		if ( is_null( $user_id ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
		}

		// Adiciona voto na table se ainda não houver
		if ( ! $this->user_has_voted( $post_id, $user_id ) ) {
			$wpdb->insert( $this->tablename, array(
				'user_id'     => $user_id,
                'vote_source' => $_SERVER['REMOTE_ADDR'],
				'post_id'     => $post_id,
				'vote_date'   => current_time('mysql')
			) );
		}

		$this->update_vote_count( $post_id );
        $this->votes_to_text_help = get_option('vq_text_vote_update');
        $RHSPosts->update_date_order($post_id);
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
		$vote = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $this->tablename WHERE user_id = %d AND post_id = %d", $user_id, $post_id ) );

		return sizeof( $vote ) > 0;

	}

	function get_vote_box( $post_id, $echo = true ) {

		$output     = '<div id="votebox-' . $post_id . '">';
		$totalVotes = $this->get_total_votes( $post_id );

		if ( empty( $totalVotes ) ) {
			$totalVotes = 0;
		}
		if($totalVotes == 1){
			$textVotes = 'voto';
		}else{
			$textVotes = 'votos';
		}

		$output .= '<span class="vTexto">' . $totalVotes . '</span>';


		// TODO: vai haver uma meta capability vote_post,
		// Se o usuário ja votou neste post, não aparece o botão e aparece de alguma maneira que indique q ele já votou
		// Se ele não estiver logado, aparece só o texto "Votos"

        if(! is_user_logged_in()){
            $output .= '<span class="vTexto">'.$textVotes.'</span>';
        }  else if($this->user_has_voted( $post_id )) {
            $output .= '<span class="vButton"><a class="btn btn-danger" data-post_id="' . $post_id . '" disabled><i class="glyphicon glyphicon-ok"></i></a></span>';
        } else {
            $output .= '<span class="vButton"><a class="btn btn-danger js-vote-button" data-post_id="' . $post_id . '">VOTAR</a></span>';
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

		if ( $this->get_total_votes( $post->ID ) >= $this->votes_to_approval ) {
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
			'post_status' => 'publish'
		);

		add_post_meta($postID, self::META_PUBISH, '1', true);

		wp_update_post( $new_post );
        do_action('rhs_post_promoted', $postID);

        $this->votes_to_text_help = get_option('vq_text_post_promoted');

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
        do_action('rhs_user_promoted', $user->ID);
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

        $pages = get_pages();
        $pagesArr = array('0'=> '-- Escolha a página --');

        foreach ($pages as $page){
            $pagesArr[$page->ID] = $page->post_title;
        }

        $labels = array(
			'vq_days_for_expired'  => array(
				'name'    => __( "Dias para expiração:" ),
				'type'    => 'select',
                'options' => array_combine(range(1, 50), range(1, 50)),
				'default' => $this->days_for_expired_default
			),
			'vq_votes_to_approval' => array(
				'name'    => __( "Votos para aprovação:" ),
				'type'    => 'select',
                'options' => array_combine(range(1, 50), range(1, 50)),
				'default' => $this->votes_to_approval_default
			),
			'vq_description'       => array(
				'name'    => __( "Texto introdutório:" ),
				'type'    => 'textarea',
				'default' => ''
			),
            'vq_text_explanation'       => array(
                'name'    => __( "Texto de informação:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o usuário não tiver permissão.',
                'default' => $this->votes_to_text_help
            ),
            'vq_page_explanation'       => array(
                'name'    => __( "Página de informação:" ),
                'type'    => 'select',
                'options' => $pagesArr,
                'help' => 'Página que aparecerá no texto de ajuda quando o usuário não tiver permissão.'
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
            <form autocomplete="off" name="form1" method="post" action="">
                <table class="form-table">
                    <tbody>
					<?php foreach ( $labels as $label => $attr ) { ?>
						<?php

						$default = !empty($attr['default']) ? $attr['default'] : '';
                        $help = !empty($attr['help']) ? $attr['help'] : '';
						$value   = get_option( $label );

						?>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo $label; ?>"><?php echo $attr['name']; ?></label>
                            </th>
                            <td>
								<?php if ( $attr['type'] == 'select' ) { ?>
                                    <select name="<?php echo $label; ?>" id="<?php echo $label; ?>">
                                        <?php foreach ($attr['options'] as $i => $text){ ?>
                                            <option <?php echo ($value == $i || (empty($value) && $i == $default )) ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $text; ?></option>
                                        <?php } ?>
                                    </select>
								<?php } ?>
								<?php if ( $attr['type'] == 'textarea' ) { ?>
                                    <textarea name="<?php echo $label; ?>" id="<?php echo $label; ?>" class="large-text"
                                              rows="5"><?php echo $value; ?></textarea>
								<?php } ?>
                                <?php if ( $attr['type'] == 'text' ) { ?>
                                    <input class="regular-text" type="text" name="<?php echo $label; ?>" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
                                <?php } ?>
                                <?php if(!empty($default)){ ?>
                                    <p><i><?php echo __( 'Valor padrão: ' ) . $default; ?></i></p>
                                <?php } ?>
                                <?php if(!empty($help)){ ?>
                                    <p><i><?php echo $help; ?></i></p>
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
