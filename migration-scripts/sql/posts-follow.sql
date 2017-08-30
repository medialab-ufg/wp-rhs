
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT

    ff.uid as `user_id`,
    '{{follow_post_meta}}' as `meta_key`,
    ff.entity_id as `meta_value`

FROM {{drupaldb}}.flagging ff
    WHERE fid = 3
