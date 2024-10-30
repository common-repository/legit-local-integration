<?php

namespace LegitLocal;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ErrorTableSetup{

	private $error_table_version;
	public function __construct(){
		$this->error_table_version = '1.0';

		add_action( 'plugins_loaded', array( $this, 'check_error_table_version' ) );
	}

	public function check_error_table_version(){

		if( get_option( 'legit_local_error_table_version' ) != $this->error_table_version ){
			$this->initialize_error_table();
		}
	}

	protected function initialize_error_table(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'legit_local_api_errors';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			request_body text DEFAULT '' NOT NULL,
			code text DEFAULT '' NOT NULL,
			message text DEFAULT '' NOT NULL,
			sent_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( 'legit_local_error_table_version', $this->error_table_version, false );
	}
}

$error_table_setup = new ErrorTableSetup;
