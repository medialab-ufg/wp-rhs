INSERT INTO {{target}} (

    post_id,
    meta_key,
    meta_value
    
)

SELECT

    target_id,
    '_uf',
    SUBSTR(cod_ibge, 1, 2)
    
FROM {{table}}

WHERE cod_ibge IS NOT NULL

;
