UPDATE {{table}} t

    SET cod_ibge = (
    
        SELECT id FROM municipio 
        
        WHERE 
            nome = t.name AND 
            ufid = (
                SELECT id FROM uf 
                WHERE
                    uf.nome = t.parent_name
            ) 
        
    )
    
WHERE

    parent <> 0;
