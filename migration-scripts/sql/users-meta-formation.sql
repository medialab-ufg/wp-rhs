
/**
 Formação
 */
INSERT IGNORE INTO {{usermeta}}
(
    user_id,
    meta_key,
    meta_value
)
SELECT DISTINCT
	u.uid as `user_id`,
	'rhs_formation' as `meta_key`,
   f.field_formacao_value as `meta_value`
  		FROM {{drupaldb}}.users u
  			INNER JOIN {{drupaldb}}.field_data_field_formacao f ON f.entity_id = u.uid
  				WHERE u.uid > 1;