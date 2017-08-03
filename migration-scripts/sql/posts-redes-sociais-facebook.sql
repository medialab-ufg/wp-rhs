
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)
SELECT

    ssf.nid as `post_id`,
    'rhs_data_facebook' as `meta_key`,
    ssf.fb_total as `meta_value`

FROM {{drupaldb}}.social_stats_facebook ssf
    GROUP BY ssf.nid;
