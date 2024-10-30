<?php

namespace LegitLocal;

class NinjaFormsIntegration extends BaseIntegration{

	protected $options;
	protected $customer_info;
	public function __construct(){
		parent::__construct();
		$options_singleton = LegitLocalOptions::get_instance();
		$this->options = $options_singleton->get_options();

		add_action( 'ninja_forms_after_submission', array( $this, 'call_legit_local_api' ) );
	}

	public function include_ninja_forms_scripts(){
		wp_enqueue_script( 'll-integration-nf', LEGIT_LOCAL_URL . 'assets/js/NinjaFormsIntegration.js', array( 'jquery' ) );
	}

	public function call_legit_local_api( $form_data ){
		$this->customer_info = $this->get_info_from_form( $form_data );

		if( $this->customer_info ){
			$this->call_api();
		}
	}

	protected function get_info_from_form( $form_data ){

		//If anything is missing just return away. Contact field is required but name field isn't
		if( ! $form_data || ! isset( $this->options['ninja_forms_selected_form'] ) || $form_data['form_id'] != $this->options['ninja_forms_selected_form'] || ! isset( $this->options['ninja_forms_contact_field'] ) || ! $this->options['ninja_forms_contact_field'] ){
			return false;
		}

		$customer_info = array();


		if( isset( $this->options['ninja_forms_name_field'] ) && $this->options['ninja_forms_name_field'] && $form_data['fields'][$this->options['ninja_forms_name_field']] ){
			$customer_info['name'] = $form_data['fields'][$this->options['ninja_forms_name_field']]['value'];
		}

		if( $form_data['fields'][$this->options['ninja_forms_contact_field']] ){
			$contact_value = $form_data['fields'][$this->options['ninja_forms_contact_field']]['value'];

			if( is_email ( $contact_value ) ){
				$customer_info['email'] = $contact_value;
			}else{
				$customer_info['phone'] = $contact_value;
			}
		}else{
			return false;
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
			'channel' 	=> 'ninja-forms',
			'business_id' => $this->options['legit_local_business_id']
		);

		if( isset( $this->customer_info['name'] ) && $this->customer_info['name'] ){
			$data['name'] = $this->customer_info['name'];
		}

		if( isset( $this->customer_info['email'] ) && $this->customer_info['email'] ){
			$data['email'] = $this->customer_info['email'];
		}

		if( isset( $this->customer_info['phone'] ) && $this->customer_info['phone'] ){
			$data['phone_number'] = $this->customer_info['phone'];	
		}

		if( isset( $this->options['ninja_forms_days_to_wait'] ) && $this->options['ninja_forms_days_to_wait'] ){
			$data['days_to_wait'] = $this->options['ninja_forms_days_to_wait'];
		}

		return json_encode( $data );
	}
}

$ninja_forms_integration = new NinjaFormsIntegration;