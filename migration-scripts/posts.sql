
TRUNCATE TABLE rhs_posts;

TRUNCATE TABLE rhs_postmeta;

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
    `post_status`
    )

SELECT DISTINCT

    n.nid `ID`,
    n.uid `post_author`,
    FROM_UNIXTIME(n.created) `post_date`,
    FROM_UNIXTIME(n.created) `post_date_gmt`,
    r.body_value `post_content`,
    n.title `post_title`,
    IF (ISNULL(r.body_summary), "", r.body_summary) `post_excerpt`,
    #IF(SUBSTR(a.alias, 11, 1) = '/', SUBSTR(a.alias, 12), a.alias) `post_name`,
    n.nid `post_name`,
    FROM_UNIXTIME(n.changed) `post_modified`,
    FROM_UNIXTIME(n.changed) `post_modified_gmt`,
    "post" `post_type`,
    "" `to_ping`,
    "" `pinged`,
    "" `post_content_filtered`,
    IF(n.status = 1, 'publish', 'private') `post_status`

FROM

    drupal_rhs.node n
    INNER JOIN drupal_rhs.field_data_body r
    ON r.entity_type = 'node' AND r.entity_id = n.nid

    # LEFT OUTER JOIN drupal_rhs.url_alias a
    # ON a.source = CONCAT('node/', n.nid)

# If applicable, add more Drupal content types below.

WHERE n.type IN ('blog')

# LIMIT 10
;

