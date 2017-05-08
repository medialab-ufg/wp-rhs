
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT

    u.uid as `user_id`,
    'first_name' as `meta_key`,
    SUBSTRING_INDEX(u.name,' ',1) as `meta_value`
    FROM {{drupaldb}}.users u

WHERE u.uid > 1;
