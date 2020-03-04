<?php echo '

<div id="log_content" style="display:none;" >


<h2 style=\'font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; font-size: 23px;
    font-weight: 400;\'>' . esc_html__("Transactions Log e.Rede", 'messages') . '</h2>


	<table id="erede_log_detail" class="widefat fixed"  style="overflow:scroll;">
    	<tbody>
			<tr class="alternate">
				<td><strong>' . esc_html__("Log ID", 'messages') . '</strong></td>
            	<td data-erede="logId"></td>
        	</tr>
			<tr>
				<td><strong>' . esc_html__("Order ID", 'messages') . '</strong></td>
            	<td data-erede="orderId"></td>
        	</tr>
        	<tr class="alternate">
				<td><strong>' .  esc_html__("Log Date", 'messages')  . '</strong></td>
            	<td data-erede="logDate"></td>
        	</tr>
			<tr>            
				<td><strong>' .  esc_html__("Message", 'messages')  . '</strong></td>
	            <td data-erede="messageResult"></td>
    	    </tr>
			<tr class="alternate">
				<td><strong>' .  esc_html__("TID", 'messages')  . '</strong></td>				
    	        <td data-erede="tid"></td>
        	</tr>
			<tr>				
				<td><strong>' .  esc_html__("NSU", 'messages')  . '</strong></td>
				<td data-erede="sqn"></td>
			</tr>
			<tr class="alternate">
				<td><strong>' .  esc_html__("Transaction Date", 'messages')  . '</strong></td>				
	            <td data-erede="transactionDate"></td>
    	    </tr>
        	<tr>            	
				<td><strong>' .  esc_html__("Customer Name", 'messages')  . '</strong></td>					
            	<td data-erede="creditHolderName"></td>
        	</tr>
		 	<tr class="alternate">
				<td><strong>' .  esc_html__("Order Value", 'messages')  . '</strong></td>
            	<td data-erede="amount"></td>
        	</tr>
			<tr>
				<td><strong>' .  esc_html__("Installments Amount", 'messages')  . '</strong></td>
            	<td data-erede="installments"></td>
        	</tr>
			<tr class="alternate">
				<td><strong>' .  esc_html__("Currency", 'messages')  . '</strong></td>				
	            <td data-erede="currency"></td>
    	    </tr>
			<tr>
				<td><strong>' .  esc_html__("Transaction Status", 'messages')  . '</strong></td>
            	<td data-erede="status"></td>
        	</tr>
			<tr class="alternate">
				<td><strong>' .  esc_html__("BIN", 'messages')  . '</strong></td>				
				<td data-erede="cardBin"></td>
			</tr>
			<tr>				
				<td><strong>' .  esc_html__("Last 4 digits", 'messages')  . '</strong></td>
				<td data-erede="lastCardDigits"></td>
			</tr>
   		 </tbody>
	</table>
</div>';

?>