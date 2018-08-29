<?php
class statistics {
	public  $type = [
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
				$result = $this->gen_users_charts();
				break;
		}

		echo json_encode($result);
		wp_die();
	}

	private function gen_users_charts()
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

		$not_active_users = "WHERE meta_key='_last_login'
			and 
			date(meta_value) 
			not between 
			DATE_ADD(CURDATE(), INTERVAL -2 year)
                and
            curdate();";

		$sql_all_users = "
			SELECT meta_value FROM $wpdb->usermeta
				where meta_key='rhs_capabilities'
		";

		$users['active_users'] = $wpdb->get_results($sql_users.$active_users, ARRAY_A)[0]['count'];
		$users['not_active_users'] = $wpdb->get_results($sql_users.$not_active_users, ARRAY_A)[0]['count'];

		$all_users_capabilities = $wpdb->get_results($sql_all_users, ARRAY_A);
		$users['total'] = count($all_users_capabilities);
		$users['voter'] = 0;
		$users['author'] = 0;
		$users['contributor'] = 0;

		foreach ($all_users_capabilities as $user_capability)
		{
			$capabilities = unserialize($user_capability['meta_value']);
			if(isset($capabilities['voter'])) {
				$users['voter']++;
			}else if(isset($capabilities['author'])) {
				$users['author']++;
			}else if(isset($capabilities['contributor'])){
				$users['contributor']++;
			}
		}

		return $users;
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