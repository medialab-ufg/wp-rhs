
INSERT IGNORE INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)

SELECT DISTINCT

    n.nid `post_id`,
    'rhs-post-date-order' as `meta_key`,
    FROM_UNIXTIME(n.created) as `meta_value`

FROM {{drupaldb}}.node n
	INNER JOIN {{drupaldb}}.field_data_body r
	ON r.entity_type = 'node' AND r.entity_id = n.nid
		WHERE n.type IN ('blog')
