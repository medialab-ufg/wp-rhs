
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)

SELECT 

    n.nid `post_id`,
    '_home' as `meta_key`,
    '1' as `meta_value`

FROM {{drupaldb}}.node n
    WHERE n.promote = 1 AND n.type = 'blog' AND n.status = 1
;

