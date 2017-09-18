SELECT DISTINCT
    oc.gid `term_id`,
    oc.`name`  as `name`
FROM
    {{drupaldb}}.og_content_type oc

  INNER JOIN {{drupaldb}}.og_membership om ON oc.gid = om.gid

    WHERE oc.gid > 1 AND om.entity_type = "node"

      GROUP BY oc.gid