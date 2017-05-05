INSERT IGNORE INTO {{usersmeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT

    u.uid as `user_id`,
    'nickname' as `meta_key`
   SUBSTR(a.alias, 9) as `meta_value`

  FROM {{drupaldb}}.users u
    LEFT OUTER JOIN {{drupaldb}}.url_alias a
    ON a.source = CONCAT('user/', u.uid)
      WHERE u.uid > 1;

INSERT IGNORE INTO {{usersmeta}}
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
        LEFT OUTER JOIN {{drupaldb}}.url_alias a
        ON a.source = CONCAT('user/', u.uid)
          WHERE u.uid > 1;

INSERT IGNORE INTO {{usersmeta}}
(
    user_id,
    meta_key,
    meta_value
)

SELECT DISTINCT
    u.uid as `user_id`,
    'last_name' as `meta_key`,
    REPLACE(u.name, SUBSTRING_INDEX(u.name,' ',1)) as `meta_value`
      FROM {{drupaldb}}.users u
        LEFT OUTER JOIN {{drupaldb}}.url_alias a
        ON a.source = CONCAT('user/', u.uid)
          WHERE u.uid > 1;


INSERT IGNORE INTO {{usersmeta}}
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
  			LEFT OUTER JOIN {{drupaldb}}.field_data_field_body d ON d.entity_id = u.uid
  				WHERE u.uid > 1;