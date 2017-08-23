
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)
SELECT DISTINCT

    cc.nid as `post_id`,
    '{{META_KEY_VIEW}}' as `meta_key`,
    cc.totalcount as `meta_value`

FROM {{drupaldb}}.node_counter cc;
