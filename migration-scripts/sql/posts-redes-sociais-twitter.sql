
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)

SELECT

    sst.nid as `post_id`,
    'rhs_data_twitter' as `meta_key`,
    sst.tweets as `meta_value`

FROM {{drupaldb}}.social_stats_twitter sst
    GROUP BY sst.nid;