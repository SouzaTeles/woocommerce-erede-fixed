<?php
namespace erede\model;
/**
* Class Security
*
* This class is responsable for store affiliation's information.
*/
class Security
{
    /**
    * Affiliation's number.
    *
    * @var string
    */
    public $affiliation = null;

    /**
    * Affiliation's password.
    *
    * @var string
    */
    public $password    = null;

    /**
    * The environment which will conect.
    *
    * @var EnvironmentType
    */
    public $environment = null;

    function __construct($affiliation, $password, $environment) {
        $this->affiliation = $affiliation;
        $this->password    = $password;
        $this->environment = $environment;
    }
}
