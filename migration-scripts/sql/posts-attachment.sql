INSERT INTO {{posts}}

    (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt, 
    post_name,
    post_modified,
    post_modified_gmt,
    post_type,
    to_ping,
    pinged,
    post_content_filtered,
    `post_status`,
    guid,
    post_parent,
    post_mime_type
    )

SELECT DISTINCT

    n.uid `post_author`,
    FROM_UNIXTIME(n.created) `post_date`,
    FROM_UNIXTIME(n.created) `post_date_gmt`,
    "" `post_content`,
    n.title `post_title`,
    "" `post_excerpt`,
    SUBSTRING(a.filename,1, CHAR_LENGTH(a.filename)-4) `post_name`,
    FROM_UNIXTIME(n.changed) `post_modified`,
    FROM_UNIXTIME(n.changed) `post_modified_gmt`,
    "attachment" `post_type`,
    "" `to_ping`,
    "" `pinged`,
    "" `post_content_filtered`,
    IF(n.status = 1, 'publish', 'draft') `post_status`,
    CONCAT('http://redehumanizasus.net/', a.filepath) `guid`,
    n.nid `post_parent`,
    a.filemime `post_mime_type`

FROM

    {{drupaldb}}.node n
    INNER JOIN {{drupaldb}}.field_data_body r
    ON r.entity_type = 'node' AND r.entity_id = n.nid

    INNER JOIN {{drupaldb}}.field_data_upload f
    ON f.entity_id = r.entity_id

    INNER JOIN {{drupaldb}}.files a
    ON a.fid = f.upload_fid

WHERE n.type IN ('blog')


# LIMIT 10


# Existem alguns casos que a tabela url_alias tem mais de um valor para o mesmo post. Isso causa um erro ao importar
# pq vai tentar importar duas vezes o post com o mesmo ID. Por isso colocamos essa clausula, para não dar erro. Depois
# podemos tratar essas URLs adicionais que não foram importadas.
ON DUPLICATE KEY UPDATE ID=ID
;