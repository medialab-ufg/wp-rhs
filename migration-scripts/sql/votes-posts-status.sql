UPDATE {{posts}} AS p
	INNER JOIN {{postmeta}} AS pm ON p.ID = pm.post_id
	SET `post_status` = if(pm.meta_value > 5,'publish','voting-queue')
        WHERE pm.meta_key = '{{total_meta_key}}' AND p.post_status = 'publish';