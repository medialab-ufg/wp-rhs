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
    'nickname' as `meta_key`,
   SUBSTR(a.alias, 9) as `meta_value`

  FROM {{drupaldb}}.users u
    INNER JOIN {{drupaldb}}.url_alias a
    ON a.source = CONCAT('user/', u.uid)
      WHERE u.uid > 1;