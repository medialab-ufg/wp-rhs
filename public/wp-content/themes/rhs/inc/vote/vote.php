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
	const META_PUBLISH = 'rhs-promoted-publish';
    const META_TOTAL_VOTES = '_total_votes';

	static $instance;

	var $tablename;

	var $post_status = [];

	var $total_meta_key;

    public $days_for_expired_default = 14;
    public $votes_to_approval_default = 5;
    public $votes_to_text_help;
    public $votes_to_text_code;

	function __construct() {

		if ( empty( self::$instance ) ) {
			global $wpdb;
			$this->tablename   = $wpdb->prefix . 'votes';
			$this->post_status = $this->get_custom_post_status();
            $this->total_meta_key = self::META_TOTAL_VOTES;

			// Hooks
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_footer-post.php', array( &$this, 'add_status_dropdown' ) );

			add_action( 'wp_ajax_rhs_vote', array( &$this, 'ajax_vote' ) );
			add_action( 'rhs_votebox', array( &$this, 'get_vote_box' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'addJS' ) );

			add_filter( 'map_meta_cap', array( &$this, 'vote_post_cap' ), 10, 4 );
			add_filter( 'map_meta_cap', array( &$this, 'read_post_cap' ), 10, 4 );

			add_action( 'pre_get_posts', array( &$this, 'fila_query' ) );

			add_action('wp_ajax_rhs_get_posts_vote', array(&$this, 'rhs_get_posts_vote'));
			
            // habilita comentarios para posts na fila de votação
            add_action( 'comment_on_draft', array( &$this, 'allow_comments_in_queue' ) );
            
            // adiciona os posts na fila na contagem de posts de um usuáregister_rest_route
            add_filter( 'get_usernumposts', array( &$this, 'count_user_posts' ), 10, 4);

            $this->verify_role();
            $this->verify_database();
            $this->verify_params();

            $this->votes_to_approval = get_option('vq_votes_to_approval');
            $this->days_for_expired = get_option('vq_days_for_expired');

			self::$instance = true;
		}
	}

	public function rhs_get_posts_vote()
    {
        $post_id = sanitize_text_field($_POST['post_id']);
		if (is_string($post_id) && !empty($post_id)) {
			$users = $this->get_post_voters($post_id);
			ob_start();
			?>
			<ul class="dropdown-menu dropdown<?php echo $post_id; ?>">
				<?php if (!empty($users)) {
					foreach ($users as $user) {
						?>
						<li>
							<a href="<?php echo get_author_posts_url($user['ID']); ?>" target="_blank"><?php echo $user['name']; ?></a>
						</li>
						<?php
					}
				}
				?>
			</ul>
			<?php

			echo ob_get_clean();
			wp_die();
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
        
        // atualizando permissões. agora podemos votar nos proprios posts
        $option_name = '_roles_update1_'.get_class();
        if ( ! get_option( $option_name ) ) {
            global $wp_roles;
            // só queremos que isso rode uma vez
            add_option( $option_name, true );
            
            $voter = $wp_roles->get_role( self::ROLE_VOTER );
            $voter->add_cap( 'vote_own_posts' );

            $editor = $wp_roles->get_role( 'editor' );
            $editor->add_cap( 'vote_own_posts' );

            $administrator = $wp_roles->get_role( 'administrator' );
            $administrator->add_cap( 'vote_own_posts' );
        }
        // atualizando permissões. agora podemos editar os proprios posts publicados
        $option_name = '_roles_update2_'.get_class();
        if ( ! get_option( $option_name ) ) {
            global $wp_roles;
            // só queremos que isso rode uma vez
            add_option( $option_name, true );
            $voter = $wp_roles->get_role( self::ROLE_VOTER );
            $voter->add_cap( 'edit_published_posts' );

            $contributor = $wp_roles->get_role( 'contributor' );
            $contributor->add_cap( 'edit_published_posts' );
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
            add_option('vq_text_vote_own_posts',  'Infelizmente você não pode votar no seu próprio post.');
        }

        if(!get_option( 'vq_text_vote_posts' )){
            add_option('vq_text_vote_posts',  'Infelizmente você ainda não pode votar em um post.');
        }

        if(!get_option( 'vq_text_vote_update' )){
            add_option('vq_text_vote_update',  'Parabéns, seu voto foi contabilizado!');
        }

        if(!get_option( 'vq_text_post_promoted' )){
            add_option('vq_text_post_promoted',  'Parabéns, seu voto foi contabilizado e o post foi promovido para a página inicial!');
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

        if ( $wp_query->is_main_query() && $wp_query->get(RHSRewriteRules::TPL_QUERY) == RHSRewriteRules::VOTING_QUEUE_URL ) {

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
            
            $wp_query->is_home = false;

        } elseif ($wp_query->is_main_query() && $wp_query->get('post_type') == '' && ( $wp_query->is_author() || $wp_query->is_single() ) ) {

            // No perfil do usuário, exibir posts de todos os status
            // Permite que pessoas vejam a single dos posts com status Fila de Votação ou expirados
            // A checagem pelo post type vazio é para ser aplicado apenas no post týpe padrão (post) e não em outros, como o ticket, por exemplo
            $statuses = ['publish', self::VOTING_EXPIRED];


            if (is_user_logged_in()) {
                $statuses[] = "private";
                $statuses[] = self::VOTING_QUEUE;

                /*
                 * Quando post está como rascunho, pode ser visualizado apenas pelo autor do post,
                 * ou usuários com perfil de capability mínima de 'edit_others_posts'
                 * */
                global $wp_query;
                $_pre_post_id = $wp_query->get('p');

                $post = get_post($_pre_post_id);
                $_pre_post_author_id = -1;
                if($post instanceof WP_Post)
                    $_pre_post_author_id = (int) $post->post_author;

                if( is_numeric($_pre_post_id) && ($_pre_post_id > 0) && is_numeric($_pre_post_author_id) ) {
                    if( ( $_pre_post_author_id === get_current_user_id() ) || current_user_can('edit_others_posts') ) {
                        $statuses[] = 'draft';
                    }
                }
            }

            $wp_query->set('post_status', $statuses);
        }

	}

	function add_status_dropdown() {
		global $post;
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
                
				if ( $this->is_post_expired($post) ) {
					$caps[] = 'vote_old_posts';
                    $this->votes_to_text_code = 'vq_text_vote_old_posts';
                    $this->votes_to_text_help = get_option($this->votes_to_text_code);
				} elseif ( $this->user_has_voted( $post->ID, $user_id ) ) {
                    $this->votes_to_text_code = 'vq_text_vote_posts_again';
                    $this->votes_to_text_help = get_option($this->votes_to_text_code);
					$caps[] = 'vote_posts_again';
				} elseif ( $post->post_author == $user_id ) {
                    $this->votes_to_text_code = 'vq_text_vote_own_posts';
                    $this->votes_to_text_help = get_option($this->votes_to_text_code);
					$caps[] = 'vote_own_posts';
				} else {
                    $this->votes_to_text_code = 'vq_text_vote_posts';
                    $this->votes_to_text_help = sprintf(get_option($this->votes_to_text_code), get_permalink(get_option('vq_page_explanation')));
					$caps[] = 'vote_posts';
				}
			} else {
                $caps[] = '__no_privs';
            }
		}

		return $caps;
	}
    
    function read_post_cap( $caps, $cap, $user_id, $args ) {

	    if ( $cap == 'read_post' ) {

			$post = get_post( $args[0] );

			if ( $post ) {

				if (is_user_logged_in() && $post->post_status == self::VOTING_QUEUE) {
				    $caps = ['read'];
				}
			} else {
                $caps = ['__no_privs'];

            }
		}

		return $caps;
	}

	function ajax_vote() {
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
		if ( user_can($user_id, 'vote_post', $post_id) ) {
			$wpdb->insert( $this->tablename, array(
				'user_id'     => $user_id,
                'vote_source' => $_SERVER['REMOTE_ADDR'],
				'post_id'     => $post_id,
				'vote_date'   => current_time('mysql')
			) );

            $this->update_vote_count( $post_id );
            $this->votes_to_text_help = get_option('vq_text_vote_update');
            $RHSPosts->update_date_order($post_id);
    		$this->check_votes_to_upgrade( $post_id );

            return true;

		}

		return false;

	}

	function update_vote_count( $post_id ) {

		global $wpdb;

		$numVotes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $this->tablename WHERE post_id = %d",
			$post_id ) );

		update_post_meta( $post_id, $this->total_meta_key, $numVotes );
        
        // Atualiza total de votos do usuário
        $author_id = get_post_field( 'post_author', $post_id );
        
        $total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $this->tablename WHERE post_id IN (SELECT ID FROM $wpdb->posts WHERE post_author = %d)",
			$author_id ) );
            
        update_user_meta($author_id, $this->total_meta_key, $total);

	}

	function get_total_votes( $post_id ) {
		return get_post_meta( $post_id, $this->total_meta_key, true );
	}

	function get_total_votes_by_author( $user_id ) {
        return get_user_meta( $user_id, $this->total_meta_key, true );
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


	function get_post_voters($post_id)
	{
		global $wpdb;
		$get_users = $wpdb->prepare("SELECT ID, display_name as name FROM $wpdb->users WHERE ID in (SELECT user_id FROM $this->tablename WHERE post_id = %d)", intval($post_id));
		$users = $wpdb->get_results($get_users, ARRAY_A);

		return $users;
	}

	function get_voters_box($post_id)
	{
		ob_start();
		?>
		<div class="dropdown">
			<button class="btn btn-xs dropdown-toggle who-votted" data-postid="<?php echo $post_id; ?>" type="button" data-toggle="dropdown">
				Quem votou <span class="caret"></span>
			</button>
        </div>
		<?php

		$users_button = ob_get_clean();

		return $users_button;
	}

	function get_vote_box( $post_id, $echo = true ) {
		$textVotes = 'votos';
		$output     = "<div id='votebox-$post_id' class='votebox-wrapper'>";
		$totalVotes = intval($this->get_total_votes($post_id));

		if (empty($totalVotes)) {
			$totalVotes = 0;
		}

		if ($totalVotes == 1) {
			$textVotes = 'voto';
		}

		$output .= '<span class="vTexto">' . $totalVotes . '</span> ';
        if (!is_user_logged_in() || $this->is_post_expired($post_id)) {
            $output .= ' <span class="vTexto vote-text">'.$textVotes.'</span>';
			if (is_user_logged_in()) {
				$users_button = $this->get_voters_box($post_id);
				$output .= $users_button;
			}
        } else if($this->user_has_voted( $post_id )) {
            /*Already voted*/
            $output .= '<span class="vButton"><a class="btn btn-danger" data-post_id="' . $post_id . '" disabled><i class="glyphicon glyphicon-ok"></i></a></span>';
        } else {
            /*Didn't vote yet*/
            $output .= '<span class="vButton"><a class="btn btn-danger js-vote-button hidden-print" data-post_id="' . $post_id . '">VOTAR</a></span>';
        }

		$output .= '</div>';

		if ($echo) {
			echo $output;
		}

		return $output;
	}

    /**
    * Verifica se post está expirado e não deve mais receber votos
    * 
    * @param $post int|WP_Post ID ou objeto WP_Post
    * 
    * return bool
    * 
    */
	public function is_post_expired($post) {
	    
        if (is_numeric($post))
            $post = get_post($post);

        if (!$post instanceof WP_Post) {
            return new WP_Error( 'post_not_found', __( "Post não encontrado", "rhs" ) );
        }

        $post_date = strtotime( $post->post_date );
	    $expire_date = strtotime( '-' . $this->days_for_expired . ' days' );

        $expired = $post_date < $expire_date;

        if ($expired)
            $this->mark_post_as_expired($post);

        return $expired;
    }

	function change_post_status( $data, $postarr ) {

		global $pagenow;

		// Apenas novos posts
		if ( $pagenow != 'post.php' ) {
			$data['post_status'] = self::VOTING_QUEUE;
		}

		return $data;
	}

	function mark_post_as_expired( WP_Post $post ) {

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

		add_post_meta($postID, self::META_PUBLISH, '1', true);

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
                'help' => 'Texto generico de erro ao votar, caso não caia em nenhuma das outras condições.',
                'default' => $this->votes_to_text_help
            ),
            
            'vq_text_vote_old_posts'       => array(
                'name'    => __( "Alerta de post antigo:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o usuário tentar votar em um post mais antigo do que o configurado para receber votos.',
                'default' => $this->votes_to_text_help
            ),
            'vq_text_vote_posts_again'       => array(
                'name'    => __( "Alerta ao tentar votar de novo:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o usuário tentar votar em um mesmo post mais de uma vez.',
                'default' => $this->votes_to_text_help
            ),
            'vq_text_vote_own_posts'       => array(
                'name'    => __( "Alerta ao votar no próprio post:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o usuário tentar votar no próprio post (e não tiver permissão pra isso).',
                'default' => $this->votes_to_text_help
            ),
            'vq_text_vote_posts'       => array(
                'name'    => __( "Texto de informação:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o usuário não tiver permissão para votar - ainda não for um votante.',
                'default' => $this->votes_to_text_help
            ),
            'vq_text_vote_update'       => array(
                'name'    => __( "Texto de informação:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o voto for registrado com sucesso.',
                'default' => $this->votes_to_text_help
            ),
            'vq_text_post_promoted'       => array(
                'name'    => __( "Texto de informação:" ),
                'type'    => 'text',
                'help' => 'Texto que aparecerá quando o voto for registrado com sucesso e o post for promovido.',
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
    
    
    /*
    * Permite adicionar comentários a post em fila de votação
    *
    * Modifica por um momento o status do post de "voting-queue" para "publish", tornando possível a adição de comentários. 
    * Após comentário adicionado o seu status volta ao normal e há redirecionamento, mantento comentário novo em destaque.
    */
    function allow_comments_in_queue($post_id){
        
        $post_status = get_post_status($post_id);
        
        if ($post_status != self::VOTING_QUEUE)
            return;
        
        function modificarRetornoGetPostStatus(){
            return 'publish';
        }
        add_filter('get_post_status', 'modificarRetornoGetPostStatus', 1);
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        remove_filter('get_post_status', 'modificarRetornoGetPostStatus');
        
        $location = empty( $_POST['redirect_to'] ) ? get_comment_link( $comment ) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;
        $location = apply_filters( 'comment_post_redirect', $location, $comment );
        
        wp_safe_redirect( $location );
        exit;
    }
    
    /**
     * Filtra a função que retorna o total de posts de um usuário e acrescenta o número de posts na fila de votação
     *
     * Isso corrige o resultado da API, que não permite trazer informações de usuários que não possuam um post publicado.
     *
     * Também afeta todos os lugares onde o total de posts de um usuário é exibido, como a página de perfil.
     *
     * A princípio pensamos em fazer essa função ser ativada apenas para usuários logados, já que só usuários logados vêem a fila. Mas, 
     * na verdade, ao visitar o perfil de um usuário é possível ver os posts da fila, mesmo não estando logado. Por isso as duas primeiras linhas
     * dessa função estão comentadas.
     * 
     */
    function count_user_posts($count, $userid, $post_type, $public_only) {

        #if (!is_user_logged_in())
        #    return $count;
        
        if ( is_array( $post_type ) ) {
            $post_types = $post_type;
        } else {
            $post_types = array( $post_type );
        }

        if (!is_numeric($userid) || !in_array('post',$post_types))
            return $count;
        
        global $wpdb;
        
        $sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = %s AND post_author = %d", self::VOTING_QUEUE, $userid);
        $posts_in_queue = $wpdb->get_var($sql);
        
        $new = is_numeric($count) ? (int) $count : 0;
        
        $new += (int) $posts_in_queue;

        return (string) $new;
        
    }
}

global $RHSVote;
$RHSVote = new RHSVote();
