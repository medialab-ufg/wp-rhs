INSERT IGNORE INTO {{term_taxonomy}}

(
    term_taxonomy_id,
    term_id,
    taxonomy,
    description
)

SELECT DISTINCT

    t.tid,
    t.tid,
    IF (t.vid = 1, "post_tag", 'category'),
    t.description 
    
FROM {{drupaldb}}.taxonomy_term_data t

    INNER JOIN {{drupaldb}}.taxonomy_index i
    ON t.tid = i.tid


WHERE t.vid IN (1,17)

AND i.nid IN (

    SELECT nid FROM {{drupaldb}}.node WHERE type = 'blog'

)


;
