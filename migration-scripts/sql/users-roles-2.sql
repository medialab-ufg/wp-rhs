INSERT IGNORE INTO {{usermeta}} (

    user_id,
    meta_key, 
    meta_value

)

SELECT DISTINCT

    u.uid, 
    '{{prefix}}capabilities',
    'a:1:{s:6:"editor";b:1;}'

    FROM {{drupaldb}}.users_roles u

    WHERE rid IN ( SELECT rid FROM {{drupaldb}}.role WHERE name = 'editorx' )
    
    # Ignoramos usuários que já tem algum papel
    AND uid NOT IN ( SELECT user_id FROM {{usermeta}} WHERE meta_key = '{{prefix}}capabilities' ) 
    
    AND uid > 1

;


