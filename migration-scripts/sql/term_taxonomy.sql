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
    'post_tag',
    t.description 
    
FROM {{drupaldb}}.taxonomy_term_data t

WHERE t.vid = 1


;
