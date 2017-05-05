INSERT IGNORE INTO {{comments}}

(
    comment_ID,
    comment_post_ID,
    comment_author,
    comment_author_email,
    comment_author_url,
    comment_author_IP,
    comment_date,
    comment_date_gmt,
    comment_parent,
    user_id,
    comment_content

)

SELECT DISTINCT

    c.cid,
    c.nid,
    c.name,
    u.mail,
    '',
    c.hostname,
    FROM_UNIXTIME(c.created),
    FROM_UNIXTIME(c.created),
    c.pid,
    c.uid,
    d.comment_body_value

FROM {{drupaldb}}.comment c

    INNER JOIN {{drupaldb}}.users u
    ON c.uid = u.uid
    
    INNER JOIN {{drupaldb}}.field_data_comment_body d
    ON entity_id = c.cid
    
WHERE d.entity_type = 'comment'



;
