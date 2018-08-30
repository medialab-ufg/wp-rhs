<?php
class statistics {
	public  $type = [
			'increasing' => "Crescimento",
			'user' => "Usuário"
		];

	function __construct() {
		add_action('wp_enqueue_scripts', array(&$this, 'addJS'), 2);

		add_action('wp_ajax_rhs_gen_charts', array(&$this, "gen_charts"));
	}

	//Funções
	public function gen_charts()
	{
		switch ($_POST['type'])
		{
			case 'user':
				$result = $this->gen_users_charts($_POST['filter']);
				break;
			case 'increasing':
				$result = $this->gen_increasing_charts($_POST['filter']);
				break;
		}

		echo json_encode($result);
		wp_die();
	}

	private function gen_users_charts($filter)
	{
		global $wpdb;
		$sql_users = "SELECT count(*) as count FROM $wpdb->usermeta ";

		$active_users = "WHERE meta_key='_last_login'
			and 
			date(meta_value) 
			between 
			DATE_ADD(CURDATE(), INTERVAL -2 year)
                and
            curdate();";

		$sql_all_users = "
			SELECT meta_value FROM $wpdb->usermeta
				where meta_key='rhs_capabilities'
		";

		if(in_array('active', $filter))
		{
			$users['active_users'] = $wpdb->get_results($sql_users.$active_users, ARRAY_A)[0]['count'];
		}

		$all_users_capabilities = $wpdb->get_results($sql_all_users, ARRAY_A);

		if(in_array('all', $filter))
		{
			$users['total'] = count($all_users_capabilities);
		}

		$users['voter'] = 0;
		$users['author'] = 0;
		$users['contributor'] = 0;

		foreach ($all_users_capabilities as $user_capability)
		{
			$capabilities = unserialize($user_capability['meta_value']);
			if(in_array("voter", $filter) && isset($capabilities['voter'])) {
				$users['voter']++;
			}else if(in_array("author", $filter) && isset($capabilities['author'])) {
				$users['author']++;
			}else if(in_array("contributor", $filter) && isset($capabilities['contributor'])){
				$users['contributor']++;
			}
		}

		if(in_array('not_active', $filter))
		{
			if(!isset($users['active_users']))
			{
				$users['active_users'] = $wpdb->get_results($sql_users.$active_users, ARRAY_A)[0]['count'];
			}

			$users['not_active_users'] = count($all_users_capabilities) - $users['active_users'];
		}

		return $users;
	}

	private function gen_increasing_charts($filter)
	{
		global $wpdb;
		$result = [];

		$date = $this->get_date($filter);

		if(in_array('all_users', $filter))
		{
			$sql_date = $this->gen_sql_date($date, 'user_registered');
			$sql = "
			SELECT date(user_registered) as date, count(*) as count FROM $wpdb->users 
				group by date(user_registered)
			";

			$all_users = $wpdb->get_results($sql, ARRAY_A);
			foreach ($all_users as $user)
			{
				$result[$user['date']]['all_users'] = intval($user['count']);
			}
		}

		$sql_users_capabilities = "
			SELECT date(u.user_registered) registered, um.meta_value capabilities FROM $wpdb->usermeta um JOIN $wpdb->users u
			ON
		    um.user_id = u.ID
		    AND
		    um.meta_key='rhs_capabilities' 
		";

		$all_users_capabilities = $wpdb->get_results($sql_users_capabilities, ARRAY_A);
		foreach ($all_users_capabilities as $user_capability)
		{
			$capabilities = unserialize($user_capability['capabilities']);

			if(isset($capabilities['author']) && in_array('author', $filter))
			{
				$result[$user_capability['registered']]['author'] += 1;
			}if(isset($capabilities['contributor']) && in_array('contributor', $filter))
			{
				$result[$user_capability['registered']]['contributor'] += 1;
			}if(isset($capabilities['voter']) && in_array('voter', $filter))
			{
				$result[$user_capability['registered']]['voter'] += 1;
			}
		}

		if(in_array('all_posts', $filter))
		{
			$sql = "
			SELECT date(post_date) as date, count(*) as count FROM $wpdb->posts
				WHERE post_type='post' and (post_status = 'publish' OR post_status = 'voting-queue')
				group by date(post_date)
			";

			$posts = $wpdb->get_results($sql, ARRAY_A);
			foreach ($posts as $post)
			{
				$result[$post['date']]['all_posts'] = intval($post['count']);
			}
		}

		if(in_array('followed', $filter))
		{
			$sql = "
				SELECT date(datetime) as date, count(*) as count FROM ".$wpdb->prefix."notifications
				WHERE type = 'post_followed' 
				group by date(datetime)
			";

			$follows = $wpdb->get_results($sql, ARRAY_A);
			foreach ($follows as $follow)
			{
				$result[$follow['date']]['followed'] = intval($follow['count']);
			}
		}

		if(in_array('comments', $filter))
		{
			$sql = "
				SELECT date(comment_date) as date, count(*) as count FROM $wpdb->comments
				WHERE comment_type <> 'acholhesus_log' 
				group by date(comment_date)
			";

			$comments = $wpdb->get_results($sql, ARRAY_A);
			foreach ($comments as $comment)
			{
				$result[$comment['date']]['comments'] = intval($comment['count']);
			}
		}

		uksort($result, function($a, $b){
			$t1 = strtotime($a);
			$t2 = strtotime($b);
			return $t1 - $t2;
		});

		return $result;
	}

	private function gen_sql_date($date, $date_column_name)
	{
		return '';
	}
	private function get_date($filters)
	{
		$date = [];
		foreach ($filters as $filter)
		{
			if(is_array($filter))
			{
				$date[] = $filter['date'];
			}
		}

		if(empty($date[0] && empty($date[1])))
			return [];
		else if(empty($date[0]))
			return ['inicial' => $date[1]];
		else if(empty($date[1]))
			return ['inicial' => $date[0]];


		if(strtotime($date[0]) < strtotime($date[1]))
			return ['inicial' => $date[0], 'final' => $date[1]];
		else return ['inicial' => $date[1], 'final' => $date[0]];
	}
	//Funções de controle
	public function addJS() {
		if (get_query_var('rhs_login_tpl') == RHSRewriteRules::STATISTICS) {
			wp_enqueue_script('statistics', get_template_directory_uri() . '/inc/statistics/statistics.js', array('jquery'));
			wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
			wp_localize_script( 'statistics', 'ajax_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			) );
		}
	}

	public function get_type()
	{
		$options = '';
		foreach ($this->type as $index => $value)
		{
			$options .= "<option value='".$index."'>".$value."</option>";
		}

		return $options;
	}
}

global $RHSStatistics;
$RHSStatistics = new statistics();