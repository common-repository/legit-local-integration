<?php

namespace LegitLocal;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BaseIntegration {

	protected $api_url;
	public function __construct(){
		$this->api_url = 'https://api.legitlocal.com/api/integration-emails/store';
		// $this->api_url = 'https://api.legitlocal.redolive.co/api/integration-emails/store'; //DEV ENDPOINT
	}

	/**
	 * Logs information in the Legit Local API Errors table
	 */
	protected function update_legit_local_error_table( $body, $code, $message ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'legit_local_api_errors';

		$result = $wpdb->insert(
			$table_name,
			array(
				'request_body'	=> $body,
				'code' 			=> $code,
				'message' 		=> $message,
				'sent_at' 		=> date('Y-m-d H:i:s')
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s'
			)
		);
	}
}
