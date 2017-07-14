##UPDATE {{users}} SET user_email = CONCAT('rhsteste-', user_email)
##
##    WHERE 
##    
##        ID NOT IN (
##        
##            SELECT DISTINCT user_id FROM {{usermeta}}
##            WHERE meta_key = '{{prefix}}capabilities' 
##            AND meta_value IN (
##                'a:1:{s:13:"administrator";b:1;}',
##                'a:1:{s:6:"editor";b:1;}'
##            )
##        
##        )
##;

UPDATE {{users}} SET user_email = CONCAT('rhsteste-', user_email);
