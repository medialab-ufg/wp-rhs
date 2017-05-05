UPDATE {{posts}}

SET `comment_count` = (

    SELECT COUNT(comment_ID)

        FROM {{comments}}

        WHERE {{posts}}.ID = {{comments}}.comment_post_ID

)

;
