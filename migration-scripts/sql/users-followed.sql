
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT

    ff.entity_id as `user_id`,
    '{{followed_meta}}' as `meta_key`,
    ff.uid as `meta_value`

FROM {{drupaldb}}.flagging ff
    WHERE fid = 4
