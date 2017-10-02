<?php
$this->log('Fetching RHS\' registered users ..');
$all_users = $wpdb->get_results("SELECT * FROM $wpdb->users  WHERE ID > 1;");
if(is_array($all_users)) {
    foreach ($all_users as $usr) {
        if(is_object($usr)) {
            $spam_role_exists = get_role('spam');
            $curr_email = $usr->user_email;
            if( !filter_var($curr_email, FILTER_VALIDATE_EMAIL) && $spam_role_exists ) {
                $user_set_as_spam = update_user_meta($usr->ID , 'is_spam', true);
                if($user_set_as_spam) {
                    echo $usr->user_nicename . " was flagged as SPAM: \t" . $curr_email . "\n";
                    wp_update_user(array( 'ID' => $usr->ID, 'role' => 'spam' ));
                }
            }
        }
    }
}