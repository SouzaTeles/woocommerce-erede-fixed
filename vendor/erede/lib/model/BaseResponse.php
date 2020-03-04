<?php

namespace erede\model;

use erede\common\BaseModel;

/**
* Class BaseResponse
*/
class BaseResponse extends BaseModel
{
	private $returnCode;
	private $returnMessage;
	private $links;
	
	/**
	 * @return string
	 */
	public function getReturnCode(){
		return $this->returnCode;
	}
	
	/**
	 * @param string $returnCode
	 */
	public function setReturnCode($returnCode){
		$this->returnCode = $returnCode;
	}
	
	/**
	 * @return string
	 */
	public function getReturnMessage(){
		return $this->returnMessage;
	}
	
	/**
	 * @param string $returnMessage
	 */
	public function setReturnMessage($returnMessage){
		$this->returnMessage = $returnMessage;
	}
	
	/**
	 * @return Link[]
	 */
	public function getLinks(){
		return $this->links;
	}
	
	/**
	 * @param Link[] $links
	 */
	public function setLinks($links){
		$this->links = $links;
	}
	
	/**
	 * @param array $array
	 * @return Link[]
	 */
	protected static function mapLinksFromArray($array){
		$links = null;
		
		if (is_array($array) && !empty($array))
		{
			$links = array();
			foreach ($array as $subArray)
			{
				if (is_array($subArray) && !empty($subArray))
				{
					$link = new Link();
					if (array_key_exists('method', $subArray))
						$link->setMethod($subArray['method']);
					if (array_key_exists('rel', $subArray))
						$link->setRel($subArray['rel']);
					if (array_key_exists('href', $subArray))
						$link->setHref($subArray['href']);
					$links[] = $link;
				}
			}
		}
		
		return $links;
	}
}