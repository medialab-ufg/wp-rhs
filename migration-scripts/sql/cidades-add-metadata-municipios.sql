INSERT INTO {{target}} (

    {{target_col}},
    meta_key,
    meta_value
    
)

SELECT

    target_id,
    '_municipio',
    cod_ibge
    
FROM {{table}}

WHERE cod_ibge IS NOT NULL

;
