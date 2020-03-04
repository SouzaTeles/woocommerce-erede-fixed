<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class WC_Erede_Config extends WC_Payment_Gateway {

		 public function __construct() {
            $this->id = 'erede';
        }

		public function isEnabled() {
			return $this->get_option('enabled');
		}

		public function getAffiliation() {
			return $this->get_option('affiliation');
		}

		public function getToken() {
			return $this->get_option('token');
		}

		public function getInvoiceName() {
			return $this->get_option('invoice_name');
		}

		public function getTitle() {
			return $this->get_option('title');
		}

		public function getEnvironment() {
			return $this->get_option('environment');
		}

		public function getTransactionType() {
			return $this->get_option('transaction_type');
		}

		public function getInstallments() {
			return $this->get_option('installments');
		}

		public function getMinimumInstallment() {
			return $this->remove_wc_separators($this->get_option('minimum_installment'));
		}

		public function getMinimumInstallmentValue() {
			return $this->remove_wc_separators($this->get_option('minimum_installment_value'));
		}

		public function getSupports() {
			return $this->get_option('supports');
		}

		public function getCaptureFlag() {
			if($this->get_option('transaction_type') == 'later_capture') {
				return false;
			} else if($this->get_option('transaction_type') == 'automatic_capture') {
				return true;
			}

			return null;
		}
		
		public function getCardBrand($number){
			$number = preg_replace( '([^0-9])', '', $number);
			$brand  = '';
				
			$supported_brands = array(
					'Credz'        => array('/^63(6[7-9][6-9][0-9]|70[0-3][0-2])/'),
					'HiperCard'    => array('/^606282/'),
					'Hiper'        => array('/^(637(095|612|599|609))/'),
					'Visa'     	   => array('/^4/', '/^(4026|417500|4508|4844|491(3|7))/'),
					'MasterCard'   => array('/^(5[1-5]|2)/'),
					'DinersClub'   => array('/^3(6|8)/', '/^30[0-5]/'),					
					'JCB'          => array('/^35((2[8-9]|[3-8][0-9])[0-9][0-9])/'),
					'AMEX'		   => array('/^3[47]/'),
					'ELO'			=> array('/^[456](?:011|38935|51416|576|04175|067|06699|36368|36297)\d{10}(?:\d{2})?$/')
			);
				
			foreach ( $supported_brands as $key => $value ) {
				foreach ($value as $regexValue) {
					if ( preg_match( $regexValue, $number ) ) {
						$brand = $key;
						break;
					}
				}
			}
				
			return $brand;
		}
		
		public function getPaymentMethods() {
			return array(
					'credz'        => __( 'Credz', 'e.Rede-woocommerce' ),
					'hipercard'    => __( 'HiperCard', 'e.Rede-woocommerce' ),
					'hiper'        => __( 'Hiper', 'e.Rede-woocommerce' ),
					'visa'         => __( 'Visa', 'e.Rede-woocommerce' ),
					'mastercard'   => __( 'MasterCard', 'e.Rede-woocommerce' ),
					'dinersclub'   => __( 'DinersClub', 'e.Rede-woocommerce' ),
					'jcb'          => __( 'JCB', 'e.Rede-woocommerce' ),
					'amex'         => __( 'AMEX', 'e.Rede-woocommerce' ),
					'elo'          => __( 'ELO', 'e.Rede-woocommerce' ),
			);
		}
		
		public function getPaymentMethodName($slug) {
			$methods = $this->getPaymentMethods();
			
			if ( isset( $methods[ $slug ] ) ) {
				return $methods[ $slug ];
			}
			
			return $slug;
		}
		
		public function getAcceptedBrandsList($methods) {
			$total = count($methods);
			$count = 1;
			$list  = '';
			
			foreach ($methods as $method) {
				$name = $this->getPaymentMethodName($method);
			
				if ( 1 == $total ) {
					$list .= $name;
				} else if ( $count == ( $total - 1 ) ) {
					$list .= $name . ' ';
				} else if ( $count == $total ) {
					$list .= sprintf( __( 'and %s', 'e.Rede-woocommerce' ), $name );
				} else {
					$list .= $name . ', ';
				}
			
				$count++;
			}
			
			return $list;
		}

		public function verifyRequiredFields() {
			$valid = true;
			$valid &= $this->verifyAffiliation();
			$valid &= $this->verifyToken();
			$valid &= $this->verifyInvoiceName();	
			return $valid;	
		}

		public function verifyAffiliation() {
			try{
				$affiliation = $this->get_option('affiliation');
				
				if( !isset($affiliation) || empty($affiliation) ) {
					throw new Exception(__('Config: Affiliation was not informed', 'messages'));
				}
			} catch (Exception $e){
				wc_add_notice($e->getMessage(), 'error');
				return false;
			}				
			return true;
		}

		public function verifyToken() {
			try{
				$token = $this->get_option('token');

				if( !isset($token) || empty($token) ) {
					throw new Exception(__('Config: Token was not informed', 'messages'));
				}
			} catch (Exception $e){
				wc_add_notice($e->getMessage(), 'error');
				return false;
			}				
			return true;
		}	

		public function verifyInvoiceName() {
			try{
				$invoiceName = $this->get_option('invoice_name');

				if(strlen($invoiceName) > 13) {
					throw new Exception(__('Config: Invoice Name exceeded 13 characters', 'messages'));
				}
			} catch (Exception $e){
				wc_add_notice($e->getMessage(), 'error');
				return false;
			}				
			return true;
		}

		private function remove_wc_separators($value)
	 	{
			$decimal_separator = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();

			$result = str_replace($thousand_separator, '', $value);
			$result = str_replace($decimal_separator, '.', $result);
		
			return $result;
	 	}				
	}