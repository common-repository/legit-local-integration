<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: Legit Local WordPress Integration
 * Plugin URI: https://www.legitlocal.com
 * Description: A plugin to integrate Legit Local with your WordPress installation
 * Version: 1.1.2
 * Author: Legit Local
 * License: Proprietary
 */

define( 'LEGIT_LOCAL_DIR', plugin_dir_path( __FILE__ ) );
define( 'LEGIT_LOCAL_URL', plugin_dir_url( __FILE__ ) );
define( 'LEGIT_LOCAL_FILE', __FILE__ );
define( 'LEGIT_LOCAL_BASENAME', plugin_basename( __FILE__ ) );
define( 
	'LL_WOOCOMMERCE_ACTIVE', 
	in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) 
);
define( 
	'LL_NINJA_FORMS_ACTIVE', 
	in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) 
);

require_once LEGIT_LOCAL_DIR . 'includes/classes/init/legit-local-settings.php';
