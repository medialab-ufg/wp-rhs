INSERT INTO {{table}} (

    tid,
    parent,
    name,
    target_id,
    parent_name

)

SELECT 

    t.tid, 
    h.parent,
    t.name, 
    e.entity_id,
    z.name as parent_name
    
FROM {{drupaldb}}.taxonomy_term_data t 

    JOIN {{drupaldb}}.taxonomy_term_hierarchy h ON h.tid = t.tid 
    JOIN {{drupaldb}}.field_data_{{source}} e ON e.{{source}}_target_id = t.tid 
    JOIN {{drupaldb}}.taxonomy_term_data z ON z.tid = h.parent
    
WHERE 

    t.vid = 5 AND 
    e.bundle = '{{bundle}}' 


;
