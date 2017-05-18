
SELECT DISTINCT

    t.tid,
    t.name,
    a.alias
    
FROM {{drupaldb}}.taxonomy_term_data t

    LEFT OUTER JOIN {{drupaldb}}.url_alias a
    ON a.source = CONCAT('taxonomy/term/', t.tid)
    
    INNER JOIN {{drupaldb}}.taxonomy_index i
    ON t.tid = i.tid


WHERE t.vid IN (1,17)

AND i.nid IN (

    SELECT nid FROM {{drupaldb}}.node WHERE type = 'blog'

)

;
