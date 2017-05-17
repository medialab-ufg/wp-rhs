<?php


$query = $this->get_sql('export-terms');

$terms = $wpdb->get_results($query);

$f = fopen('terms.csv', 'w');

foreach ($terms as $t) {


    $line =  '"' . $t->tid . '";';
    $line .= '"' . $t->name . '";';
    $line .= '"' . $t->alias . '";';
    $line .= "\n";
    
    fwrite($f, $line);


}

fclose($f);
