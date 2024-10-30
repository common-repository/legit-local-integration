<?php

namespace LegitLocal;

class GeneralSection{

	protected static $options;

	public static function init(){
		self::set_singleton();
		self::add_sections();
		self::add_fields();
	}

	private static function set_singleton(){
		$options_singleton = LegitLocalOptions::get_instance();
		self::$options = $options_singleton->get_options();
	}

	private static function add_sections(){
		/**
		 * GENERAL
		 */
		add_settings_section(
            'legit_local_general', // ID
            'General Settings', // Title
            array( __CLASS__, 'legit_local_general_callback' ), // Callback
            'll-general' // Page
        );
	}

	private static function add_fields(){
		/**
		 * GENERAL
		 */
		add_settings_field(
            'legit_local_api_key', // ID
            'Legit Local API Key', // Title
            array( __CLASS__, 'legit_local_api_key_callback' ), // Callback
            'll-general', // Page
            'legit_local_general' // Section
        );

        add_settings_field(
            'legit_local_business_id', // ID
            'Legit Local Business ID', // Title
            array( __CLASS__, 'legit_local_business_id_callback' ), // Callback
            'll-general', // Page
            'legit_local_general' // Section
        );
	}

	/**
	 * GENERAL
	 */
	public static function legit_local_general_callback(){
		echo '<hr>Connect with Legit Local to send customers an email to ask about their experience on your site.
		<br><em>(Access your API Key in your Legit Local dashboard: Accounts Settings > Integrations)</em>';
	}

	public static function legit_local_api_key_callback(){
	    printf(
            '<input type="text" size="35" id="legit_local_api_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxx" name="legit_local_options[legit_local_api_key]" value="%s" />',
            isset( self::$options['legit_local_api_key'] ) ? esc_attr( self::$options['legit_local_api_key'] ) : ''
        );
	}

	public static function legit_local_business_id_callback(){
	    printf(
            '<input type="text" size="6" id="legit_local_business_id" placeholder="xxxxxx" name="legit_local_options[legit_local_business_id]" value="%s" />',
            isset( self::$options['legit_local_business_id'] ) ? esc_attr( self::$options['legit_local_business_id'] ) : ''
        );
	}
}
