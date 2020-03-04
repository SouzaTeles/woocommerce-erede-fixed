<?php

class WC_Erede_Base_Request {

	public function __construct() {
        $this->includes();        
	}

    private function includes()
    {
        $wordpress_pos = strpos($_SERVER['SCRIPT_FILENAME'], '/wp-content');
        $path = substr($_SERVER['SCRIPT_FILENAME'], 0, $wordpress_pos);
        
        include_once $path . '/wp-config.php';

        include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-post-data.php';
        include_once dirname( __FILE__ ) . '/../utils/class-wc-erede-functions.php';
        include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-sessions.php';
    }

    public function returnAjaxMessage($message)
    {
    	
        header('Content-Type: application/json');
        echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

?>
