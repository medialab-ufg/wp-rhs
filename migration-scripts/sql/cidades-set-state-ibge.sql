UPDATE {{table}} t

    SET cod_ibge = (
    
        SELECT id FROM uf WHERE nome = t.name 
        
    )
    
WHERE

    parent = 0;
