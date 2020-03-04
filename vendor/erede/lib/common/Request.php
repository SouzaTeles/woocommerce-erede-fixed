<?php

namespace erede\common;

use erede\model\EnvironmentType;

/**
 * Class Request
 *
 */
class Request{
	/**
	 * Base url from api
	 * 
	 * @var string
	 */
    private $baseUrl = '';

    /**
	 * Default Curl Options
	 *
	 * @var array
	 */
	private $defaultCurlOptions = array(
		CURLOPT_CONNECTTIMEOUT => 60,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json; charset=UTF-8'),
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_SSL_VERIFYPEER => 0
	);

	/**
	 * Constructor
	 */
	function __construct($security)
	{
		$this->setAuthorizationHeader($security->affiliation, $security->password);

		if($security->environment == EnvironmentType::PRODUCTION)
		$this->baseUrl = 'https://api.userede.com.br/erede/v1/';
		elseif ($security->environment == EnvironmentType::HOMOLOG)
		$this->baseUrl = 'https://api.userede.com.br/desenvolvedores/v1/';
		elseif ($security->environment == EnvironmentType::DEVELOP)
		$this->baseUrl = 'http://localhost:56513/';
	}

    /**
     * Send get request to api
     *
     * @param string $url_path
     * @return string $response
     */
    function get($url_path)
    {
        return $this->send($url_path, 'GET');
    }

    /**
     * Send post request to api
     *
     * @param string $url_path
     * @param string(json formatted) $params
     * @return string $response
     */
    function post($url_path, $params)
    {
        return $this->send($url_path, 'POST', $params);
    }

    /**
     * Send put request to api
     *
     * @param string $url_path
     * @param mixed $params
     * @return string
     */
    function put($url_path, $params)
    {
        return $this->send($url_path, 'PUT', $params);
    }

    /**
     * Send request
     *
     * @param string $url_path
     * @param string $method('GET', 'POST', 'PUT')
     * @param string (json formatted) $json
     * @throws \Exception
     * @return string (json formatted) $response
     */
    private function send($url_path, $method, $json = NULL)
    {
        $curl = curl_init($this->getFullUrl($url_path));

        curl_setopt_array($curl, $this->defaultCurlOptions);

        if($method == 'POST'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }
        elseif($method == 'PUT'){
        	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }

        $response = curl_exec($curl);
        
        if(!$response){
        	throw new \Exception(curl_error($curl));
        }
        curl_close($curl);

        return $response;
    }

    /**
     * Get request full url
     *
     * @param string $url_path
     * @return string $url(config) + $url_path
     */
    private function getFullUrl($url_path)
    {
        if (stripos($url_path, $this->baseUrl, 0) === 0)
        {
            return $url_path;
        }
        return $this->baseUrl . $url_path;
    }

    /**
     * Set Authorization Header
     */
    private function setAuthorizationHeader($affiliation, $token)
    {
        $credenciais = $affiliation . ':' . $token;
        $this->defaultCurlOptions[CURLOPT_HTTPHEADER][] = 'Authorization: Basic ' . base64_encode($credenciais);
    }
}
