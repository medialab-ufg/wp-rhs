UPDATE {{posts}} p

SET comment_count = (

    SELECT COUNT(comment_ID)

        FROM {{comments}} c

        WHERE p.ID = c.comment_post_ID

)

;
