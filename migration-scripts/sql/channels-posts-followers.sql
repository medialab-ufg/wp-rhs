
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT 

    p.user_id `user_id`,
    '{{META_KEY}}' as `meta_key`,
    REPLACE('{{CHANNEL_COMMENTS}}', '%s', p.meta_value) as `meta_value`

FROM {{usermeta}} p
    WHERE meta_key = '{{follow_post_meta}}'
;

