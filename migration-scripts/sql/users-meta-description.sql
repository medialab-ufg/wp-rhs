INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT DISTINCT

    u.uid as `user_id`,
    'description' as `meta_key`,
    d.field_body_value as `meta_value`
    FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.field_data_field_body d ON d.entity_id = u.uid

WHERE u.uid > 1;
