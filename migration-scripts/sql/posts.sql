
INSERT INTO rhs_posts

    (ID, 
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
    guid
    )

SELECT DISTINCT

    n.nid `ID`,
    n.uid `post_author`,
    FROM_UNIXTIME(n.created) `post_date`,
    FROM_UNIXTIME(n.created) `post_date_gmt`,
    r.body_value `post_content`,
    n.title `post_title`,
    IF (ISNULL(r.body_summary), "", r.body_summary) `post_excerpt`,
    
    IF(SUBSTR(a.alias, 11, 1) = '/', SUBSTR(a.alias, 12), IF(a.alias <> '', a.alias, "aliasvazio")) `post_name`,
    
    FROM_UNIXTIME(n.changed) `post_modified`,
    FROM_UNIXTIME(n.changed) `post_modified_gmt`,
    "post" `post_type`,
    "" `to_ping`,
    "" `pinged`,
    "" `post_content_filtered`,
    IF(n.status = 1, 'publish', 'private') `post_status`,
    CONCAT('http://redehumanizasus.net/node/', n.nid) `guid`

FROM

    rhs.node n
    INNER JOIN rhs.field_data_body r
    ON r.entity_type = 'node' AND r.entity_id = n.nid

    LEFT OUTER JOIN rhs.url_alias a
    ON a.source = CONCAT('node/', n.nid)


WHERE n.type IN ('blog')


# LIMIT 10


# Existem alguns casos que a tabela url_alias tem mais de um valor para o mesmo post. Isso causa um erro ao importar
# pq vai tentar importar duas vezes o post com o mesmo ID. Por isso colocamos essa clausula, para não dar erro. Depois
# podemos tratar essas URLs adicionais que não foram importadas.
ON DUPLICATE KEY UPDATE ID=ID
;

