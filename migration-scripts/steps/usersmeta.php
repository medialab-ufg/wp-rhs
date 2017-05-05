<?php

$query = $this->get_sql('usersmeta');
$this->log('Importando informações usuarios...');
$wpdb->query($query);