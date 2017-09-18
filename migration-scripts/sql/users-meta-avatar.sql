
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT

    u.uid as `user_id`,
    'rhs_avatar' as `meta_key`,
    IF(fm.uri LIKE 'public://%%',REPLACE(fm.uri, 'public://', 'sites/default/files/styles/profile-photo/public/'),fm.uri)  as `meta_value`
    FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.file_managed fm ON u.picture = fm.fid

WHERE u.uid > 1;
