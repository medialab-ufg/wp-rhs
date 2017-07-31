#!/bin/bash
php -r '
if (!file_exists("public/wp-content/themes/rhs/vendor")) { 
    mkdir("public/wp-content/themes/rhs/vendor", 0777, true); 
}
if (!file_exists("public/wp-content/themes/rhs/vendor/bootstrap")) { 
    mkdir("public/wp-content/themes/rhs/vendor/bootstrap", 0777, true);  
}
if (!file_exists("public/wp-content/themes/rhs/vendor/js")) {  
    mkdir("public/wp-content/themes/rhs/vendor/js", 0777, true); 
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
recurse_copy("vendor/twbs/bootstrap/dist/", "public/wp-content/themes/rhs/vendor/bootstrap");
recurse_copy("vendor/fortawesome/font-awesome/css/", "public/wp-content/themes/rhs/vendor/font-awesome/css");
recurse_copy("vendor/fortawesome/font-awesome/fonts/", "public/wp-content/themes/rhs/vendor/font-awesome/fonts");
recurse_copy("vendor/nicolasbize/magicsuggest/", "public/wp-content/themes/rhs/vendor/magicsuggest");
if(file_exists("public/wp-content/themes/rhs/vendor/magicsuggest/bower.json")){
    array_map("unlink", glob("public/wp-content/themes/rhs/vendor/magicsuggest/*.json"));
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/README.md");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/magicsuggest.css");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/magicsuggest.js");
    unlink("public/wp-content/themes/rhs/vendor/magicsuggest/.gitignore");
}
if (file_exists("wp-content/plugins/wp-bootstrap-navwalker")) {
    recurse_copy("wp-content", "vendor/wp-content");
}
if (file_exists("vendor/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php")) { 
    copy("vendor/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php", "public/wp-content/themes/rhs/vendor/wp-bootstrap-navwalker.php"); 
}
if (file_exists("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js")) {
    copy("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", "public/wp-content/themes/rhs/vendor/js/bootstrap-hover-dropdown.min.js");
}
if (file_exists("vendor/desandro/masonry/dist/masonry.pkgd.min.js")) {
    copy("vendor/desandro/masonry/dist/masonry.pkgd.min.js", "public/wp-content/themes/rhs/vendor/js/masonry.pkgd.min.js");
}
if (file_exists("vendor/twitter/typeahead.js/dist/typeahead.bundle.min.js")) {
    copy("vendor/twitter/typeahead.js/dist/typeahead.bundle.min.js", "public/wp-content/themes/rhs/vendor/js/typeahead.bundle.min.js");
}
if (file_exists("wp-content/plugins/wp-bootstrap-navwalker")) {
    deleteDir("wp-content");
}
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