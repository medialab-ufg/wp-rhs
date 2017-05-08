INSERT IGNORE INTO {{term_relationships}}

(
    object_id,
    term_taxonomy_id
)

SELECT DISTINCT

    t.nid,
    t.tid
    
FROM {{drupaldb}}.taxonomy_index t

    INNER JOIN {{drupaldb}}.taxonomy_term_data d
    ON d.tid = t.tid

WHERE d.vid IN (1,17)

AND t.nid IN (

    SELECT nid FROM {{drupaldb}}.node WHERE type = 'blog'

)

;
