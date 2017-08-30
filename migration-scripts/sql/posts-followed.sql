
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)
SELECT

    ff.entity_id as `post_id`,
    '{{followed_post_meta}}' as `meta_key`,
    ff.uid as `meta_value`

FROM {{drupaldb}}.flagging ff
    WHERE fid = 3
