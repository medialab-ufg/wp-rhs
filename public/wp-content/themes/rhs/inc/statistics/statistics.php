<?php
class statistics {
	public  $type = [
			'user' => "Usuário"
		];

	function __construct() {
		add_action('wp_enqueue_scripts', array(&$this, 'addJS'), 2);

		add_action('rhs_gen_graph', array(&$this, "gen_graph"));
	}

	//Funções
	public function gen_graph()
	{
		print_r($_POST);
	}

	//Funções de controle
	public function addJS() {
		if (get_query_var('rhs_login_tpl') == RHSRewriteRules::STATISTICS) {
			wp_enqueue_script('statistics', get_template_directory_uri() . '/inc/statistics/statistics.js', array('jquery'));
			wp_localize_script( 'statistics', 'stats', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_localize_script( 'statistics', 'ajax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
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