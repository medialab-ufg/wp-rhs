
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT

    c.user_id `user_id`,
    '{{META_KEY}}' as `meta_key`,
    REPLACE('{{CHANNEL_COMMENTS}}', '%s', c.comment_post_ID) as `meta_value`

FROM {{comments}} c
    JOIN {{posts}} p ON p.ID = c.comment_post_ID
    WHERE p.post_author <> c.user_id
    AND c.user_id NOT IN (
        SELECT user_id FROM {{usermeta}} um WHERE meta_key = '{{follow_post_meta}}' AND meta_value LIKE CONCAT('%_', c.comment_post_ID)
    )
;

