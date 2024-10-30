<?php

namespace LegitLocal;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class LegitLocalSettings
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'include_legit_local_admin_scripts' ) );

        add_filter( 'plugin_action_links_' . LEGIT_LOCAL_BASENAME, array( $this, 'include_settings_link_on_plugins_page' ) );
    }

    public function include_legit_local_admin_scripts(){
		wp_enqueue_style( 'legit_local_admin_css', LEGIT_LOCAL_URL . 'assets/css/legit-local-admin.css' );
	}

    public function add_plugin_page(){
    	add_options_page(
    	   'Legit Local Settings',
    	   'Legit Local',
    	   'manage_options',
    	   'legit-local-settings',
    	   array( $this, 'legit_local_settings_page' )
    	);
    }

    public function legit_local_settings_page(){
    	?>
    	<div class="wrap">
    		<h2>Legit Local Settings</h2>
    	</div>
    	<form method="post" action="options.php">
    	<?php
    		settings_fields( 'legit_local_fields' );

    		do_settings_sections( 'll-general' );
    		do_settings_sections( 'll-woocommerce' );
    		do_settings_sections( 'll-ninja-forms' );

    		submit_button();
    	?>
    	</form>
    	<?php
    }

    /**
     * Adds a "Settings" link on the Legit Local WordPress Integration row of the Installed Plugins page.
     */
    public function include_settings_link_on_plugins_page( $links ){
    	$settings_link = '<a href="options-general.php?page=legit-local-settings">' . __( 'Settings' ) . '</a>';

    	array_unshift( $links, $settings_link );

    	return $links;
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
    	/** Instantiate Sections **/
        GeneralSection::init();
        NinjaFormsSection::init();
        WooCommerceSection::init();
        
        /** Get the marketing options object to use when saving the settings **/
        $options_singleton = LegitLocalOptions::get_instance();

        /** Save the settings **/
        register_setting(
            'legit_local_fields', // Option group
            'legit_local_options', // Option name
            array( 'LegitLocal\LegitLocalOptions', 'sanitize' ) // Sanitize
        );
    }
}

/** Set up Singleton **/
require_once LEGIT_LOCAL_DIR . 'includes/classes/singleton/options-singleton.php';

/** Call Other Required Files **/
require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/base-integration.php';
require_once LEGIT_LOCAL_DIR . 'includes/required-files/required-files.php';
require_once LEGIT_LOCAL_DIR . 'includes/classes/init/error-table-setup.php';

if( is_admin() ){
	/** Set up Sections **/
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/general/general-section.php';
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/woocommerce/woocommerce-section.php';
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/ninja-forms/ninja-forms-section.php';

	$legit_local_settings = new LegitLocalSettings();
}
