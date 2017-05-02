
INSERT INTO {{postmeta}}

    ( 
    meta_value,
    post_id,
    meta_key
    )

SELECT 

    COUNT(ID) `meta_value`,
    post_id `post_id`,
    '{{total_meta_key}}' `meta_key`
    
FROM

    {{votes}} 
    
    GROUP BY post_id
    
;

