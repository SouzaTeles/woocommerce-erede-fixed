<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WC_Erede_Environment
{
    const Test = "Teste";  //Teste
    const Production = "Produção"; //Produção

    public static function getTranslatedEnvironment($environment) {
        switch($environment) {
            case 'test' :
                return WC_Erede_Environment::Test;
            case 'production' : 
                return WC_Erede_Environment::Production;
            default :
                return $environment;
        }
    }
}

?> 