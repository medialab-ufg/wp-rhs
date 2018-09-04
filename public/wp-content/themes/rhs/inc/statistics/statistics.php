<?php
class statistics {
	const INCREASING = 'INCREASING';
	const USER = 'USER';
	const AVERAGE = 'AVERAGE';

	public  $type = [
			'increasing' => "Crescimento",
			'average' => 'Média',
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
			case 'average':
				$result = $this->gen_average_charts($_POST['filter']);
		}

		echo json_encode($result);
		wp_die();
	}

	private function gen_users_charts($filter)
	{
		global $wpdb;
		$date = $this->get_date($filter);

		$sql_users = "SELECT count(*) as count FROM $wpdb->usermeta ";

		$active_users = "WHERE meta_key='_last_login'
			and 
			date(meta_value) 
			between 
			DATE_ADD(CURDATE(), INTERVAL -2 year)
                and
            curdate()";

		if(empty($date)){
			$sql_all_users = "
			SELECT meta_value FROM $wpdb->usermeta
				where meta_key='rhs_capabilities'
			";
		}else
		{
			$sql_date = $this->gen_sql_date($date, 'u.user_registered', self::USER);

			$sql_all_users = "
			SELECT um.meta_value meta_value FROM $wpdb->usermeta um JOIN $wpdb->users u
				where um.meta_key='rhs_capabilities' AND u.ID = um.user_id $sql_date
			";
		}

		$all_users_capabilities = $wpdb->get_results($sql_all_users, ARRAY_A);
		if(in_array('active', $filter))
		{
			$users['active_users'] = $wpdb->get_results($sql_users.$active_users, ARRAY_A)[0]['count'];
			$users['active_users'] = ($users['active_users'] > count($all_users_capabilities)) ? count($all_users_capabilities) : $users['active_users'];
		}

		if(in_array('all_users', $filter))
		{
			$users['all_users'] = count($all_users_capabilities);
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
				$users['active_users'] = ($users['active_users'] > count($all_users_capabilities)) ? count($all_users_capabilities) : $users['active_users'];
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
		$sql_date = $this->gen_sql_date($date, 'user_registered');

		if(in_array('all_users', $filter))
		{
			$sql = "
			SELECT date(user_registered) as date, count(*) as count FROM $wpdb->users
				$sql_date
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
		    $sql_date
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
			$sql_date = $this->gen_sql_date($date, 'post_date');
			$sql = "
			SELECT date(post_date) as date, count(*) as count FROM $wpdb->posts
				WHERE post_type='post' and (post_status = 'publish' OR post_status = 'voting-queue') $sql_date
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
			$sql_date = $this->gen_sql_date($date, 'datetime');
			$sql = "
				SELECT date(datetime) as date, count(*) as count FROM ".$wpdb->prefix."notifications
				WHERE type = 'post_followed' $sql_date
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
			$sql_date = $this->gen_sql_date($date, 'comment_date');
			$sql = "
				SELECT date(comment_date) as date, count(*) as count FROM $wpdb->comments
				WHERE comment_type <> 'acholhesus_log' $sql_date
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

	private function gen_average_charts($filter)
	{
		global $wpdb;
		$period = $this->get_period($filter);

		$date = $this->get_date($filter);
		$result = [];

		/*Users types*/
		$sql_date = $this->gen_sql_date($date, 'u.user_registered', self::USER);
		$sql_all_users_capabilities= "
			SELECT um.meta_value capabilities FROM $wpdb->usermeta um JOIN $wpdb->users u
				where um.meta_key='rhs_capabilities' AND u.ID = um.user_id $sql_date
			";

		$all_users_capabilities = $wpdb->get_results($sql_all_users_capabilities, ARRAY_A);
		foreach ($all_users_capabilities as $user_capability)
		{
			$capabilities = unserialize($user_capability['capabilities']);

			if(isset($capabilities['author']) && in_array('author', $filter))
			{
				$result['author'] += 1;
			}if(isset($capabilities['contributor']) && in_array('contributor', $filter))
			{
				$result['contributor'] += 1;
			}if(isset($capabilities['voter']) && in_array('voter', $filter))
			{
				$result['voter'] += 1;
			}
		}

		$div = $this->gen_div_factor($date, $period, 'user_registered', $wpdb->users, $wpdb)['div'];
		foreach ($result as $i => $r)
		{
			$result[$i] /= $div;
		}

		/*All users*/
		$sql_date = $this->gen_sql_date($date, 'user_registered', self::INCREASING);
		if(in_array('all_users', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'user_registered', $wpdb->users, $wpdb)['div'];
			$sql_all_users = "
				SELECT sum(c.count) as sum FROM
				(SELECT COUNT(*) count FROM $wpdb->users $sql_date
				group by $period(user_registered)) as c  		
			";

			$result['all_users'] = $wpdb->get_results($sql_all_users, ARRAY_A)[0]['sum'] / $div;
		}

		/*All posts*/
		$sql_date = $this->gen_sql_date($date, 'post_date', self::USER);
		if(in_array('all_posts', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'post_date', $wpdb->posts, $wpdb)['div'];
			$sql_all_posts = "
				SELECT sum(c.count) as sum FROM
				(SELECT COUNT(*) count FROM $wpdb->posts 
				where 
				post_type = 'post' and (post_status = 'publish' or post_status = 'voting-queue') $sql_date
				group by $period(post_date)) as c 
			";

			$result['all_posts'] = $wpdb->get_results($sql_all_posts, ARRAY_A)[0]['sum'] / $div;
		}

		/*Follows*/
		if(in_array('followed', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'datetime', $wpdb->prefix . "notifications", $wpdb)['div'];
			$sql_date = $this->gen_sql_date( $date, 'datetime', self::USER );
			$sql      = "
				SELECT sum(c.count) as sum FROM
				(SELECT count(*) as count FROM " . $wpdb->prefix . "notifications
				WHERE type = 'post_followed' $sql_date
				group by $period(datetime)) as c
			";

			$result['followed'] = $wpdb->get_results( $sql, ARRAY_A )[0]['sum'] / $div;
		}

		/*Comments*/
		if(in_array('comments', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'comment_date', $wpdb->comments, $wpdb)['div'];
			$sql_date = $this->gen_sql_date($date, 'comment_date', self::USER);
			$sql = "
				SELECT sum(c.count) as sum FROM
				(SELECT count(*) as count FROM $wpdb->comments
				WHERE comment_type <> 'acholhesus_log' $sql_date
				group by $period(comment_date)) as c
			";

			$result['comments'] = $wpdb->get_results( $sql, ARRAY_A )[0]['sum'] / $div;
		}

		/*Posts visits*/
		if(in_array('posts_visits', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'post_date', $wpdb->posts, $wpdb)['div'];
			$sql_date = $this->gen_sql_date($date, 'post_date', self::USER);
			$sql = "
			SELECT sum(c.view) as sum FROM
			(SELECT sum(pm.meta_value) view FROM $wpdb->postmeta pm JOIN $wpdb->posts p
				where pm.meta_key='_rhs_data_view' AND p.ID = pm.post_id $sql_date
                group by $period(p.post_date)) as c
			";

			$result['posts_visits'] = $wpdb->get_results( $sql, ARRAY_A )[0]['sum'] / $div;
		}

		/*Posts shares*/
		if(in_array('shares', $filter))
		{
			$div = $this->gen_div_factor($date, $period, 'post_date', $wpdb->posts, $wpdb)['div'];
			$sql_date = $this->gen_sql_date($date, 'post_date', self::USER);
			$sql = "
			SELECT sum(c.view) as sum FROM
			(SELECT sum(pm.meta_value) view FROM $wpdb->postmeta pm JOIN $wpdb->posts p
				where (pm.meta_key='_rhs_data_twitter' or pm.meta_key='_rhs_data_facebook') AND p.ID = pm.post_id $sql_date
                group by $period(p.post_date)) as c
			";

			$result['shares'] = $wpdb->get_results( $sql, ARRAY_A )[0]['sum'] / $div;
		}

		return $result;
	}

	private function gen_sql_date($date, $date_column_name, $type = self::INCREASING)
	{
		$date_sql = '';
		if(!empty($date))
		{
			if($type === self::INCREASING)
			{
				if(isset($date['final']))
				{
					if($date_column_name === 'user_registered')
					{
						$date_sql .= "WHERE $date_column_name between date('".$date['inicial']."') and date_add(date('". $date['final']."'), interval 1 day)";
					}else
					{
						$date_sql .= "AND $date_column_name between date('".$date['inicial']."') and date_add(date('". $date['final']."'), interval 1 day)";
					}
				}else
				{
					if($date_column_name === 'user_registered')
					{
						$date_sql .= "WHERE $date_column_name >= date('".$date['inicial']."')";
					}else
					{
						$date_sql .= "AND $date_column_name >= date('".$date['inicial']."')";
					}
				}
			}else if($type === self::USER)
			{
				if(isset($date['final']))
				{
					$date_sql .= "AND $date_column_name between date('".$date['inicial']."') and date_add(date('". $date['final']."'), interval 1 day)";
				}else
				{
					$date_sql .= "AND $date_column_name >= date('".$date['inicial']."')";
				}
			}
		}

		return $date_sql;
	}

	private function gen_div_factor($date, $period, $column, $table, $wpdb)
	{
		$min = 0; $max = 0;
		if(isset($date['inicial']))
		{
			$earlier = new DateTime($date['inicial']);
		}else {
			$sql_min_date = "SELECT date(min($column)) min from $table";
			$min = $wpdb->get_results($sql_min_date, ARRAY_A)[0]['min'];
			$earlier = new DateTime($min);
		}

		if(isset($date['final']))
		{
			$later = new DateTime($date['final']);
		}else {
			$sql_max_date = "SELECT date(max($column)) max from $table";
			$max = $wpdb->get_results($sql_max_date, ARRAY_A)[0]['max'];
			$later = new DateTime($max);
		}


		$diff = $earlier->diff($later);

		if($period === 'month')
			$div = $diff->m;
		else if($period === 'day')
			$div = $diff->d;
		else if($period === 'year')
			$div = $diff->y;
		else
		{
			$day = ($diff->d === 0)? 1 : $diff->d;
			$month = ($diff->m === 0)? 1 : $diff->m;
			$year = ($diff->y === 0)? 1 : $diff->y;
			$div =  ($year * $month * $day) / 7;
		}

		$div++;

		return ['min' => $min, 'max' => $max, 'div' => $div];
	}

	//Funções de controle
	private function get_date($filters)
	{
		$date = [];
		foreach ($filters as $filter)
		{
			if(is_array($filter) && isset($filter['date']))
			{
				$date[] = $filter['date'];
			}
		}

		if(empty($date[0]) && empty($date[1]))
			return [];
		else if(empty($date[0]))
			return ['inicial' => $date[1]];
		else if(empty($date[1]))
			return ['inicial' => $date[0]];


		if(strtotime($date[0]) < strtotime($date[1]))
			return ['inicial' => $date[0], 'final' => $date[1]];
		else return ['inicial' => $date[1], 'final' => $date[0]];
	}

	private function get_period($filters)
	{
		foreach ($filters as $filter)
		{
			if(is_array($filter) && isset($filter['period']))
			{
				$period = $filter['period'];
				break;
			}
		}

		return $period;
	}

	public function addJS() {
		if (get_query_var('rhs_login_tpl') == RHSRewriteRules::STATISTICS) {
			wp_enqueue_script('estatisticas', get_template_directory_uri() . '/inc/statistics/statistics.js', array('jquery'));
			wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
			wp_localize_script( 'estatisticas', 'ajax_vars', array(
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