SELECT DISTINCT
    om.etid as `user_id`,
    our.uid as `moderate`
FROM {{drupaldb}}.og_membership om
    LEFT JOIN {{drupaldb}}.og_users_roles our ON om.etid = our.uid AND our.gid = {{comunity_id}}
    WHERE om.gid = {{comunity_id}} AND om.entity_type = "user"
