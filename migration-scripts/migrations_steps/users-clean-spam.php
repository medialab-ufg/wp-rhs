<?php
/*
 * Cria perfil 'Spam', sem permissões, para facilitar sua marcação no painel admin do WP.
 * O step 1, executado a partir do migration-scripts/rhs_migrations.php, busca identificar usuários
 * spam já cadastrados, e seta-os como spam, adicionando-os a este perfil.
*/
$spam_user_pemrissions = array(
    'read' => false,
    'edit_posts' => false,
    'edit_pages' => false,
    'edit_others_posts' => false,
    'create_posts' => false,
    'manage_categories' => false,
    'publish_posts' => false,
    'edit_themes' => false,
    'install_plugins' => false,
    'update_plugin' => false,
    'update_core' => false
);
$this->log("Criando perfil de usuarios 'SPAM' ...");
add_role(RHSUsers::ROLE_SPAM, __('Spam'), $spam_user_pemrissions);

$this->log('Buscando os usuarios registrados na RHS ...');
$all_users = $wpdb->get_results("SELECT * FROM $wpdb->users  WHERE ID > 1;");
if(is_array($all_users)) {
    $correct_emails = array();
    foreach ($all_users as $usr) {
        if(is_object($usr)) {
            $spam_role_exists = get_role(RHSUsers::ROLE_SPAM);
            $curr_email = $usr->user_email;
            if( !filter_var($curr_email, FILTER_VALIDATE_EMAIL) && $spam_role_exists ) {
                $user_set_as_spam = update_user_meta($usr->ID , RHSUsers::SPAM_USERMETA, true);
                if($user_set_as_spam) {
                    set_user_as_spam($usr->ID,$usr->user_nicename, $curr_email);
                }
            } else {
                $mail_parts = explode('@', $curr_email);
                if(is_array($mail_parts) && count($mail_parts) === 2) {
                    $mail = str_replace(".", "", $mail_parts[0]);
                    $formatted = $mail . "@" . $mail_parts[1];
                    if( filter_var($formatted, FILTER_VALIDATE_EMAIL) ) {
                        if( in_array($formatted, $correct_emails) ) {
                            set_user_as_spam($usr->ID,$usr->user_nicename, $curr_email);
                        } else {
                            array_push($correct_emails, $formatted);
                        }
                    }
                }
            }
        }
    }
}

function set_user_as_spam($id, $user_name, $user_email) {
    echo $user_name . " foi marcado como SPAM: \t " . $user_email . "\n";
    wp_update_user(array( 'ID' => $id, 'role' => RHSUsers::ROLE_SPAM ));
}