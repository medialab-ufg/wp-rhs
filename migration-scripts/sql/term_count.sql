UPDATE {{term_taxonomy}} t

SET `count` = ( 

    SELECT COUNT(object_id) FROM {{term_relationships}} r

    WHERE t.term_taxonomy_id = r.term_taxonomy_id

)

;
