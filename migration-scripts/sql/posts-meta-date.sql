
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)

SELECT 

    n.post_id `post_id`,
    'rhs-post-date-order' as `meta_key`,
    MAX(n.vote_date) as `meta_value`

FROM {{votes}} n
    GROUP BY n.post_id
;

