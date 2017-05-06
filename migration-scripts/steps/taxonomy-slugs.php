<?php


$query = $this->get_sql('terms-fill-empty-slugs');

$this->log('Taxonomias: Criando slugs para tags sem slugs');
$wpdb->query($query);

$this->log('Taxonomias: Tratando slugs repetidos');

$repeated = $wpdb->get_results("SELECT count(term_id) as c, slug FROM $wpdb->terms WHERE slug <> '' group by slug");

//var_dump($repeated); die;

foreach ($repeated as $r) {

    if ($r->c < 2)
        continue;

    $inc = -1;
    
    $terms = $wpdb->get_col("SELECT term_id FROM $wpdb->terms WHERE slug = '$slug'");
    
    foreach ($terms as $t) {
        
        $inc ++;
        
        if ($inc === 0) 
            continue;
            
        var_dump($wpdb->update($wpdb->terms, ['slug' => $r->slug . '-' . $inc], ['term_id' => $t]));
    
    }
    
    //echo $r->slug;

}
