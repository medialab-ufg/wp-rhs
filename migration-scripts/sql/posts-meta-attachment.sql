-- tabela field_revision_upload do drupal
-- retornando o ID do post de cada anexo
-- testando com Post: 86532 e Arquivo: 25904

INSERT INTO {{postmeta}}
(
    post_id,
    meta_key,
    meta_value
)

SELECT
   f.entity_id as `post_id`,
   '_wp_attached_file' as `meta_key`,
   a.filepath as `meta_value`

FROM 
    {{drupaldb}}.field_data_upload f
    INNER JOIN {{drupaldb}}.field_data_body r
    ON r.entity_type = 'node' AND r.entity_id = f.entity_id
    LEFT OUTER JOIN {{drupaldb}}.files a
    ON f.upload_fid = a.fid