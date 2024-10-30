<?php

namespace LegitLocal;

class WooCommerceIntegration extends BaseIntegration{

	protected $customer_info;
	protected $options;
	public function __construct(){
		parent::__construct();
		$options_singleton = LegitLocalOptions::get_instance();
		$this->options = $options_singleton->get_options();
		add_action( 'woocommerce_thankyou', array( $this, 'call_legit_local_api' ) );
	}

	public function call_legit_local_api( $order_id ){
		$this->customer_info = $this->get_info_from_order( $order_id );

		if( $this->customer_info ){
			$this->call_api();
		}
	}

	protected function get_info_from_order( $order_id ){
		$order = new \WC_Order( $order_id );

		if( ! $order ){
			return false;
		}

		// newer versions of WC
		if( method_exists( $order, 'get_billing_email' ) ){
			$customer_info = array(
				'email' => $order->get_billing_email(),
				'fname' => $order->get_billing_first_name(),
				'lname' => $order->get_billing_last_name(),
				'phone' => $order->get_billing_phone()
			);
		} else {
			$customer_info = array(
				'email' => $order->billing_email,
				'fname' => $order->billing_first_name,
				'lname' => $order->billing_last_name,
				'phone' => $order->billing_phone
			);
		}

		return $customer_info;
	}

	protected function call_api(){
		if( ! isset( $this->options['legit_local_api_key'] ) || ! $this->options['legit_local_api_key'] ){
			return;
		}

		$body = $this->build_request_body();

		$args = array(
			'method' 	=> 'POST',
			'body' 		=> $body, 
			'headers' 	=> array(
				'content-type' 	=> 'application/json',
				'X-api-token' 	=> $this->options['legit_local_api_key']
			),
		);

		$add_customer = wp_remote_request( $this->api_url, $args );

		if( is_wp_error( $add_customer ) ){
			$this->update_legit_local_error_table( $body, 'wp_error', $add_customer->get_error_message()  );
		}elseif( $add_customer['response']['code'] != 200 && $add_customer['response']['code'] != 201 ){
			$this->update_legit_local_error_table( $body, $add_customer['response']['code'], $add_customer['body'] );
		}
	}

	protected function build_request_body(){
		$data = array(
			'channel' 	=> 'woocommerce',
			'business_id' => $this->options['legit_local_business_id'],
			'name' 		=> $this->customer_info['fname'] . ' ' . $this->customer_info['lname']
		);

		if( $this->options['woocommerce_contact_method'] == 'phone' ){
			$contact_method = 'phone';
		}else{
			$contact_method = 'email';
		}

		if( $contact_method == 'email' && $this->customer_info['email'] ){
			$data['email'] = $this->customer_info['email'];
		}

		if( $contact_method == 'phone' && $this->customer_info['phone'] ){
			$data['phone_number'] = $this->customer_info['phone'];
		}

		if( isset( $this->options['woocommerce_days_to_wait'] ) && $this->options['woocommerce_days_to_wait'] ){
			$data['days_to_wait'] = $this->options['woocommerce_days_to_wait'];
		}

		return json_encode( $data );
	}
}

$woocommerce_integration = new WooCommerceIntegration;
