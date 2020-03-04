<?php 

$wpAdminPos = strpos($_SERVER['REQUEST_URI'], '/wp-admin');
$partialUrl = substr($_SERVER['REQUEST_URI'], 0, $wpAdminPos);
$url = 'http://' . $_SERVER['HTTP_HOST'] . $partialUrl . '/wp-content/plugins/woocommerce-erede/includes/controller/class-wc-erede-update-order-request.php';

$errorMessage = sprintf( __('Could not update transaction', 'messages')); 

echo '<script>

    function updateEredeStatus(orderId)
    {
        jQuery.ajax({
            method: "POST",
            url: "' . $url . '",
    	    data : { erede_order_id: orderId },
            success: function(result){
		if(result != null && result.returnCode === null )
	        {
            		window.location.reload(true);
                }
                else
                {
                   var element = \'<div class="error"><p>' . $errorMessage. ' </p></div>\';
                   jQuery(element).insertBefore("#post");
                }             
            }

            //error: function(jqXHR, textStatus, errorThrown){	
            //	alert(errorThrown);
           // }
        });
    }

</script>'

?>
