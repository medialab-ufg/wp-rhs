INSERT IGNORE INTO {{users}}

(
    ID, 
    user_login, 
    user_pass, 
    user_nicename, 
    user_email,
    user_registered, 
    user_activation_key, 
    user_status,
    display_name

)

SELECT DISTINCT

    u.uid, 
    u.mail, 
    u.pass, 
    u.name, 
    u.mail,
    FROM_UNIXTIME(created),
    ' ', 
    0, 
    u.name

FROM {{drupaldb}}.users u


WHERE u.uid > 1

# AND u.mail = 'leogermani@gmail.com'

;
