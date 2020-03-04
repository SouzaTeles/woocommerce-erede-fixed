<?php 

$wpAdminPos = strpos($_SERVER['REQUEST_URI'], '/wp-admin');
$partialUrl = substr($_SERVER['REQUEST_URI'], 0, $wpAdminPos);
$url = 'http://' . $_SERVER['HTTP_HOST'] . $partialUrl . '/wp-content/plugins/woocommerce-erede/includes/controller/class-wc-erede-log-detail-request.php';

echo '<script>

    function getLogContent(id)
    {
        $.ajax({
            method: "POST",
            url: "' . $url . '",
            data: { erede_log_id: id },
            success: function(result){
                mapLogData(result);
                tb_show("", "#TB_inline?width=600&height=550&inlineId=log_content", null );
            }
        });
    }

    function transactionDateMask(transactionDate)
    { 
        if(transactionDate.length > 10)
        {
            return transactionDate.substr(0, 10);
        }
        else
        {
            return transactionDate;
        }
    }

    function mapLogData(source)
    {
        $("#erede_log_detail [data-erede=\'logId\']").html(source.logId);
        $("#erede_log_detail [data-erede=\'orderId\']").html(source.orderId);
        $("#erede_log_detail [data-erede=\'logDate\']").html(source.logDate);
        $("#erede_log_detail [data-erede=\'messageResult\']").html(source.messageResult);
        $("#erede_log_detail [data-erede=\'tid\']").html(source.tid);
        $("#erede_log_detail [data-erede=\'sqn\']").html(source.sqn);
        $("#erede_log_detail [data-erede=\'transactionDate\']").html(self.transactionDateMask(source.transactionDate));
        $("#erede_log_detail [data-erede=\'creditHolderName\']").html(source.creditHolderName);
        $("#erede_log_detail [data-erede=\'amount\']").html(source.amount);
        $("#erede_log_detail [data-erede=\'installments\']").html(source.installments);
        $("#erede_log_detail [data-erede=\'currency\']").html(source.currency);
        $("#erede_log_detail [data-erede=\'status\']").html(source.status);
        $("#erede_log_detail [data-erede=\'cardBin\']").html(source.cardBin);
        $("#erede_log_detail [data-erede=\'lastCardDigits\']").html(source.lastCardDigits);   
    }

</script>'

?>