#!/bin/bash
php -r '
echo "\nVerificando se as pastas existe...\n\n";
if (!file_exists("public/wp-content/themes/rhs/vendor")) { 
    mkdir("public/wp-content/themes/rhs/vendor", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/bootstrap")) { 
    mkdir("public/wp-content/themes/rhs/vendor/bootstrap", 0777, true);  
}
if (!file_exists("public/wp-content/themes/rhs/vendor/js")) {  
    mkdir("public/wp-content/themes/rhs/vendor/js", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/css")) {  
    mkdir("public/wp-content/themes/rhs/vendor/css", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/font-awesome")) {  
    mkdir("public/wp-content/themes/rhs/vendor/font-awesome", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/font-awesome/css")) {  
    mkdir("public/wp-content/themes/rhs/vendor/font-awesome/css", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/font-awesome/fonts")) {  
    mkdir("public/wp-content/themes/rhs/vendor/font-awesome/fonts", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/magicsuggest")) {  
    mkdir("public/wp-content/themes/rhs/vendor/magicsuggest", 0777, true); 
}
echo "Copiando arquivos...\n";

echo "...Bootstrap\n";
recurse_copy("vendor/twbs/bootstrap/dist/", "public/wp-content/themes/rhs/vendor/bootstrap");

echo "...Font-awesome\n";
recurse_copy("vendor/fortawesome/font-awesome/css/", "public/wp-content/themes/rhs/vendor/font-awesome/css");
recurse_copy("vendor/fortawesome/font-awesome/fonts/", "public/wp-content/themes/rhs/vendor/font-awesome/fonts");

echo "...Magic suggest\n";
recurse_copy("vendor/nicolasbize/magicsuggest/", "public/wp-content/themes/rhs/vendor/magicsuggest");
if(file_exists("public/wp-content/themes/rhs/vendor/magicsuggest/bower.json")){
    array_map("unlink", glob("public/wp-content/themes/rhs/vendor/magicsuggest/*.json"));
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/README.md");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/magicsuggest.css");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/magicsuggest.js");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/.gitignore");
}

echo "...Bootstrap-navwalker\n";
if (file_exists("public/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php")) { 
    copy("public/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php", "public/wp-content/themes/rhs/vendor/wp-bootstrap-navwalker.php"); 
}

echo "...Deletando Diretorio Bootstrap-navwalker não usado\n";
if (file_exists("public/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php")) {
    deleteDir("public/wp-content/plugins/wp-bootstrap-navwalker");
}

echo "...Bootstrap-hover-dropdown\n";
if (file_exists("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js")) {
    copy("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", "public/wp-content/themes/rhs/vendor/js/bootstrap-hover-dropdown.min.js");
}

echo "...Bootstrap-datepicker\n";
if (file_exists("vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css")) {
    copy("vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css", "public/wp-content/themes/rhs/vendor/css/bootstrap-datepicker3.min.css");
}
if (file_exists("vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")) {
    copy("vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js", "public/wp-content/themes/rhs/vendor/js/bootstrap-datepicker.min.js");
}

echo "...Bootstrap-switch\n";
if (file_exists("vendor/nostalgiaz/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css")) {
    copy("vendor/nostalgiaz/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css", "public/wp-content/themes/rhs/vendor/css/bootstrap-switch.min.css");
}
if (file_exists("vendor/nostalgiaz/bootstrap-switch/dist/js/bootstrap-switch.min.js")) {
    copy("vendor/nostalgiaz/bootstrap-switch/dist/js/bootstrap-switch.min.js", "public/wp-content/themes/rhs/vendor/js/bootstrap-switch.min.js");
}

echo "...Typeahead\n";
if (file_exists("vendor/twitter/typeahead.js/dist/typeahead.bundle.min.js")) {
    copy("vendor/twitter/typeahead.js/dist/typeahead.bundle.min.js", "public/wp-content/themes/rhs/vendor/js/typeahead.bundle.min.js");
}

echo "...Deletando Diretorio não usado\n";
if (file_exists("wp-content/plugins/wp-bootstrap-navwalker")) {
    deleteDir("wp-content");
}

echo "...Finalizando!\n";

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != "." ) && ( $file != ".." )) {
            if ( is_dir($src . "/" . $file) ) {
                recurse_copy($src . "/" . $file,$dst . "/" . $file);
            }
            else {
                copy($src . "/" . $file,$dst . "/" . $file);
            }
        }
    }
    closedir($dir);
}
function deleteDir($path){
    if (is_dir($path) === true){
        $files = array_diff(scandir($path), array(".", ".."));

        foreach ($files as $file){
            deleteDir(realpath($path) . "/" . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true){
        return unlink($path);
    }

    return false;
}
'
