#!/usr/bin/env bash

php -r '

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

if (!file_exists("public/wp-content/themes/rhs/assets")) { mkdir("public/wp-content/themes/rhs/assets", 777, true); }  }
if (!file_exists("public/wp-content/themes/rhs/vendor")) { mkdir("public/wp-content/themes/rhs/vendor", 777, true); }
if (!file_exists("public/wp-content/themes/rhs/vendor/bootstrap")) { mkdir("public/wp-content/themes/rhs/vendor/bootstrap", 777, true);  }
if (!file_exists("public/wp-content/themes/rhs/assets/js")) {  mkdir("public/wp-content/themes/rhs/assets/js", 777, true); }
if (!file_exists("public/wp-content/themes/rhs/vendor/js")) {  mkdir("public/wp-content/themes/rhs/vendor/js", 777, true); }
if (!file_exists("public/wp-content/themes/rhs/vendor/fortawesome/font-awesome/css")) {  mkdir("public/wp-content/themes/rhs/vendor/font-awesome/css", 777, true); }


recurse_copy("vendor/twbs/bootstrap/dist/", "public/wp-content/themes/rhs/vendor/bootstrap");
recurse_copy("vendor/fortawesome/font-awesome/css/", "public/wp-content/themes/rhs/vendor/font-awesome/css");

if (file_exists("wp-content")) {
    recurse_copy("wp-content", "vendor");
    deleteDir("wp-content");
}

if (file_exists("vendor/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php")) { copy("vendor/wp-content/plugins/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php", "public/wp-content/themes/rhs/vendor/wp-bootstrap-navwalker.php"); }
if (file_exists("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js")) {

    copy("vendor/cwspear/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", "public/wp-content/themes/rhs/vendor/js/bootstrap-hover-dropdown.min.js");
}
'