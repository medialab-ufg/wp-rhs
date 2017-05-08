
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT DISTINCT
    
    u.uid as `user_id`,
    'rhs_interest' as `meta_key`,
    i.field_interesses_value as `meta_value`
    FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.field_data_field_interesses i ON i.entity_id = u.uid

WHERE u.uid > 1;
