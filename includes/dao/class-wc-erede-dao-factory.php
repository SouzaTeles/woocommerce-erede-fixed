<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include("class-wc-erede-log-dao.php");

class WC_Erede_Dao_Factory
{
	private static $_instance;
 
	public function __construct()
	{
	}
 
	/**
	 * Set the factory instance
	 * @param WC_Erede_Dao_Factory $f
	 */
	public static function setFactory(WC_Erede_Dao_Factory $f)
	{
		self::$_instance = $f;
	}
 
	/**
	 * Get a factory instance. 
	 * @return WC_Erede_Dao_Factory
	 */
	public static function getFactory()
	{
		if(!self::$_instance)
			self::$_instance = new self;
 
		return self::$_instance;
	}
 
	/**
	 * Get a Erede Log DAO
	 * @return WC_Erede_Log_Dao class
	 */
	public function getEredeLogDao()
	{
		return new WC_Erede_Log_Dao();
	}
}

?>