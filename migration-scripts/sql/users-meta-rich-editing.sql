
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT DISTINCT
    
    u.ID as `user_id`,
    'rich_editing' as `meta_key`,
    'true' as `meta_value`
    FROM {{users}} u

WHERE u.ID > 1;
