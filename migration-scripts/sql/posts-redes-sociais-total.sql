
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)
SELECT

    ssf.nid as `post_id`,
    '{{META_KEY_TOTAL_SHARES}}' as `meta_key`,
    ssf.total_virality as `meta_value`

FROM {{drupaldb}}.social_stats_total ssf
    GROUP BY ssf.nid;
