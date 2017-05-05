INSERT IGNORE INTO {{terms}}

(
    term_id,
    name,
    slug
)

SELECT DISTINCT

    t.tid,
    t.name,
    SUBSTR(a.alias, 15) 
    
FROM {{drupaldb}}.taxonomy_term_data t

    LEFT OUTER JOIN {{drupaldb}}.url_alias a
    ON a.source = CONCAT('taxonomy/term/', t.tid)


WHERE t.vid = 1


;
