INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
 SELECT DISTINCT

    u.uid as `user_id`,
    'rhs_links' as `meta_key`,
    JSON_OBJECT('title', GROUP_CONCAT(l.field_profile_links_title SEPARATOR '&#44;' ), 'url',  GROUP_CONCAT(l.field_profile_links_url SEPARATOR '&#44;' )) as `meta_value`
    FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.field_data_field_profile_links l ON l.entity_id = u.uid

WHERE u.uid > 1

GROUP BY u.uid;
