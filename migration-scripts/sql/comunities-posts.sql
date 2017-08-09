
SELECT DISTINCT
    om.etid as `post_id`,
    fdga.group_access_value as `access`
FROM {{drupaldb}}.og_membership om
  LEFT JOIN {{drupaldb}}.field_data_group_access fdga ON om.etid = fdga.entity_id
    AND fdga.entity_type = "node"
     WHERE om.gid = {{comunity_id}} AND om.entity_type = "node"
