INSERT IGNORE INTO {{terms}}

(
    term_id,
    name,
    slug
)

SELECT DISTINCT

    t.tid,
    t.name,
    IF (t.vid = 1, 
        
        ## category/tags/slug or tags/slug
        IF (SUBSTR(a.alias, 1, 4) = 'tags',
        
            SUBSTR(a.alias, 6),
            
            REPLACE(SUBSTR(a.alias, 15), '/', '-')
            
        
        )
        , 
        SUBSTR(a.alias, 20)
    )
    
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
