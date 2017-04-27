INSERT IGNORE INTO {{usermeta}} (

    user_id,
    meta_key, 
    meta_value

)

SELECT DISTINCT

    u.uid, 
    '{{prefix}}capabilities',
    'a:1:{s:13:"administrator";b:1;}'

    FROM {{drupaldb}}.users_roles u

    WHERE rid IN ( SELECT rid FROM {{drupaldb}}.role WHERE name = 'site_admin' )
    
    AND uid > 1

;


