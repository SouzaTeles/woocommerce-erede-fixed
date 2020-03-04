<?php
/**
 * Payment Form.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<script>
	jQuery(document).ready(function() {
	jQuery("#erede-card-cpf").mask("999.999.999-99");
	jQuery("#erede-card-expiry").mask("99/99");
	jQuery( "#btnSecurityCode" ).tooltip({ 
		content: '<img src="<?php echo plugins_url( '../assets/images/cvc.png' , __FILE__ ) ?>">'
	});

	jQuery('#erede-card-holder-name').blur(function(){
		var isValid = true;
		var objField = jQuery('#erede-card-holder-name')
	    var regex = new RegExp("[\\s]");
	    var objError = objField.parent().find(".errorMsgHolder");
	    var strValue = objField.val().trim();
	    //Se campo está preenchido, torna valido
	    if (strValue.length > 0) {
	        if (regex.test(strValue)) {
	            objError.text("");
				objField.removeClass("errorValidate");
	        } else {
	            objError.text("Nome e sobrenome obrigatórios");
				objField.addClass("errorValidate");
	            isValid = false;
	        }
	    } else {
	        objError.text("Campo obrigatório");
			objField.addClass("errorValidate");
	        isValid = false;
	    }
		return isValid;
	});

	jQuery('#erede-card-number').blur(function(){
		var isValid = true;
		var objField = jQuery('#erede-card-number');
		var objErrorMsg = objField.parents().find(".errorMsgCardNumber");
		var strValue = objField.val();
		if(strValue.length > 0){
			objErrorMsg.text("");
			objField.removeClass("errorValidate");
		}else{
			objErrorMsg.text("Campo obrigatório");
			 objField.addClass("errorValidate");
			 isValid =  false;
		}
		return isValid;
	});
		
	jQuery("#erede-card-cpf").blur(function(){
		var isValid = true;
		var objField = jQuery("#erede-card-cpf");
		var objErrorMsg = objField.parents().find(".errorMsgCpf");
		var strValue = objField.val();
		if(strValue.length > 0){
			if(isCPFValid(strValue)){
				objErrorMsg.text("");
				objField.removeClass("errorValidate");
			}else{
				 objErrorMsg.text("CPF inválido");
				 objField.addClass("errorValidate");
				 isValid =  false;
			}
		}else{
			objErrorMsg.text("Campo obrigatório");
			objField.addClass("errorValidate");
			isValid = false;
		}
		return isValid;
	});
	
	jQuery('#erede-card-expiry').blur(function(){
		var isValid = true;
		var objField = jQuery('#erede-card-expiry');
		var objErrorMsg = objField.parents().find(".errorMsgExpiry");
		var strValue = objField.val();
		if(strValue.length > 0){
			objErrorMsg.text("");
			objField.removeClass("errorValidate");
		}else{
			objErrorMsg.text("Campo obrigatório");
			 objField.addClass("errorValidate");
			 isValid =  false;
		}
		return isValid;
	});

	jQuery('#erede-card-cvc').blur(function(){
		var isValid = true;
		var objField = jQuery('#erede-card-cvc');
		var objErrorMsg = objField.parents().find(".errorMsgCVC");
		var strValue = objField.val();
		if(strValue.length > 0){
			objErrorMsg.text("");
			objField.removeClass("errorValidate");
		}else{
			objErrorMsg.text("Campo obrigatório");
			 objField.addClass("errorValidate");
			 isValid =  false;
		}
		return isValid;
	});
	
		
		/*CPF Validation*/
		function isCPFValid(cpf) {
		    var numbers, digits, sum, i, result, equal_digits;
			cpf = cpf.replace(/[\. ,:-]+/g, "");
		    equal_digits = 1;
		    if (cpf.length < 11)
		          return false;
		    for (i = 0; i < cpf.length - 1; i++)
		        if (cpf.charAt(i) != cpf.charAt(i + 1))
		        {
		            equal_digits = 0;
		            break;
		        }
		    if (!equal_digits)
		    {
		        numbers = cpf.substring(0,9);
		        digits = cpf.substring(9);
		        sum = 0;
		        for (i = 10; i > 1; i--)
		              sum += numbers.charAt(10 - i) * i;
		        result = sum % 11 < 2 ? 0 : 11 - sum % 11;
		        if (result != digits.charAt(0))
		              return false;
		        numbers = cpf.substring(0,10);
		        sum = 0;
		        for (i = 11; i > 1; i--)
		              sum += numbers.charAt(11 - i) * i;
		        result = sum % 11 < 2 ? 0 : 11 - sum % 11;
		        if (result != digits.charAt(1))
		              return false;
		        return true;
		    }
		    else
		        return false;
		}
		
		jQuery(".alphabetical").keypress(function(event){
				var objRegex = new RegExp("^[a-zA-Z ]+$");
				var objCharCode = getCharCode(event);
		    	var strKey = String.fromCharCode(objCharCode);
				if(isAllowedCharCode(objCharCode) || objRegex.test(strKey))
				{
					return true;
				}
				event.preventDefault();
		    	return false;
			});

		function getCharCode(e) {
		    var charCode = null;
		    if (window.event)
		        charCode = window.event.keyCode;
		    else if (e)
		        charCode = e.which;

		    return charCode;
		}

		function isAllowedCharCode(charCode) {
		    if (charCode == null || charCode == 0 || charCode == 8 || charCode == 9 || charCode == 13 || charCode == 27)
		        return true;

		    return false;
		}
		
		jQuery("#erede-card-number").keyup(function() {
			$credzBin = /^63(6[7-9][6-9][0-9]|70[0-3][0-2])/;
			$bin = jQuery("#erede-card-number").val().replace(" ", "");
			if($credzBin.test($bin)) {
				jQuery.payment.cards.forEach(function FunctionName(element, index, array) {
					if(array[index].type === 'credz') {
						if(-1 === jQuery.inArray($bin, array[index].patterns)){
							array[index].patterns.unshift($bin);
						}
						return;
					}
				})
			}  
		});
	});
</script>

<fieldset id="erede-credit-payment-form">
	<p class="form-row form-row-wide">			
		<label for="erede-card-holder-name"><?php _e( 'Nome do Titular do Cartão', 'messages' ); ?> <span class="ered-required">*</span></label>
		<input id="erede-card-holder-name" name="erede_credit_holder_name" class="input-text alphabetical" type="text" autocomplete="off" style="font-size: 1.5em; padding: 8px; text-transform: uppercase;"  maxlength="40"/>
		<span class="errorMsgHolder"></span>
	</p>
	<p class="form-row form-row-wide">
		<label for="erede-card-cpf"><?php _e( 'CPF', 'messages' ); ?> <span class="ered-required">*</span></label>
		<input id="erede-card-cpf" name="erede_credit_cpf" class="input-text" type="text" autocomplete="off" style="font-size: 1.5em; padding: 8px;" />
		<span class="errorMsgCpf"></span>
	</p>

	<div class="clear"></div>
	
	<p class="form-row form-row-wide">
		<label for="erede-card-number"><?php _e( 'Número do Cartão de Crédito', 'messages' ); ?> <span class="ered-required">*</span></label>
		<input id="erede-card-number" name="erede_credit_number" class="input-text wc-credit-card-form-card-number" type="tel" maxlength="23" autocomplete="off"  style="font-size: 1.5em; padding: 8px;" />
		<span class="errorMsgCardNumber"></span>
	</p>
	<p class="form-row form-row-wide">
		<?php if(WC_Erede_Config_Static::getPluginConfig()->getTransactionType() == 'later_capture'):?>
			<img src="<?php echo plugins_url( '../assets/images/cards-no-credz.png' , __FILE__ ) ?>"/>
		<?php else:  ?>
			<img src="<?php echo plugins_url( '../assets/images/cards.png' , __FILE__ ) ?>"/>
		<?php endif; ?>
	</p>
	<p class="form-row form-row-wide">
		<label for="erede-card-expiry"><?php _e( 'Validade', 'messages' ); ?> <span class="ered-required">*</span></label>
		<input id="erede-card-expiry" name="erede_credit_expiry" type="tel" placeholder="<?php _e( 'MM / YY', 'messages' ); ?>" style="font-size: 1.5em; padding: 8px;" />
		<span class="errorMsgExpiry"></span>
	</p>

	<div class="clear"></div>

	<p class="form-row form-row-first">
				<label for="erede-card-cvc"><?php _e( 'CSC Code', 'messages' ); ?> <span class="ered-required">*</span></label>
				<input id="erede-card-cvc" name="erede_credit_cvc" class="input-text wc-credit-card-form-card-cvc" maxlength="4" type="tel" autocomplete="off" style="font-size: 1.5em; padding: 8px;" />
				<span class="errorMsgCVC"></span>
	</p>
	<p class="form-row form-row-last" style="vertical-align: bottom;">
		<label for="btnSecurityCode">&nbsp;</label>
		<a id="btnSecurityCode" href="javascript:void(0)" title="" class="nounderline">
			
				<span class="erede-button">
					<?php _e( 'O que é isto?', 'messages' ) ?>
				</span>
			
		</a>
	</p>

	<div class="clear"></div>

	<p class="form-row form-row-wide">
		<label for="erede-installments"><?php _e( 'Número de Parcelas', 'messages' ); ?> 
		<span class="ered-required">*</span></label>
		<?php echo $installments; ?>
	</p>

	<input id='checkInputClass' class='input-text wc-credit-card-form-card-number visa identified' style='display:none;' />
		
</fieldset>