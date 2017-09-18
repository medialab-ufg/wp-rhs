
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT 

    p.post_author `user_id`,
    '{{META_KEY}}' as `meta_key`,
    REPLACE('{{CHANNEL_COMMENTS}}', '%s', p.ID) as `meta_value`

FROM {{posts}} p
    WHERE post_status IN ('publish', 'private')
    AND post_type = 'post'
;

