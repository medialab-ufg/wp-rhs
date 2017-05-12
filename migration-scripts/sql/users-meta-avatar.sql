/**
 Apelido
 */
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT
    u.uid as `user_id`,
    'rhs_avatar' as `meta_key`,
   CONCAT('wp-content/uploads/2017/01/',fm.filename) as `meta_value`
  FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.file_managed fm ON u.picture = fm.fid
      WHERE u.uid > 1;