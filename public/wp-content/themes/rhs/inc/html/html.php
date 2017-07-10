<?php

class RHSHtml {

    private static $titulo;

    public static function setTitulo($titulo){
        self::$titulo = $titulo;
    }

    public static function getTitulo(){
        return self::$titulo;
    }

}

global $RHSHtml;
$RHSHtml = new RHSHtml();