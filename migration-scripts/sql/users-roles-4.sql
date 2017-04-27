INSERT IGNORE INTO {{usermeta}} (

    user_id,
    meta_key, 
    meta_value

)

SELECT DISTINCT

    u.uid, 
    '{{prefix}}capabilities',
    'a:1:{s:6:"author";b:1;}'

    FROM {{drupaldb}}.users u

    WHERE 
    
    # Ignoramos usuários que já tem algum papel
    uid NOT IN ( SELECT user_id FROM {{usermeta}} WHERE meta_key = '{{prefix}}capabilities' ) 
    
    AND uid > 1

;


