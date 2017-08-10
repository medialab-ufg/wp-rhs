UPDATE

  {{posts}} AS p

INNER JOIN {{postmeta}} AS pm ON p.ID = pm.post_id

SET `post_status` = IF(pm.meta_value >= 5,'publish',IF(p.post_date > NOW() - INTERVAL 2 WEEK,'voting-queue','voting-expired'))

WHERE pm.meta_key = '{{total_meta_key}}' AND p.post_status = 'publish';