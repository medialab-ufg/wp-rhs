
INSERT INTO {{votes}}

    (ID, 
    post_id,
    user_id,
    vote_date,
    vote_source
    )

SELECT DISTINCT

    vote_id `ID`,
    entity_id `post_id`,
    uid `user_id`,
    FROM_UNIXTIME(timestamp) `vote_date`,
    vote_source
    
FROM

    {{drupaldb}}.votingapi_vote 

WHERE entity_type = 'node' AND value_type = 'points' AND tag = 'plus1_node_vote' 


# LIMIT 10
;

