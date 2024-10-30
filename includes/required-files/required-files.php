<?php

$options_singleton = LegitLocal\LegitLocalOptions::get_instance();
$options = $options_singleton->get_options();

// WooCommerce
if( isset( $options['enable_woocommerce_integration'] ) && $options['enable_woocommerce_integration'] && LL_WOOCOMMERCE_ACTIVE ){
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/woocommerce/woocommerce.php';
}

// Ninja Forms
if( isset( $options['enable_ninja_forms_integration'] ) && $options['enable_ninja_forms_integration'] && LL_NINJA_FORMS_ACTIVE ){
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/ninja-forms/ninja-forms.php';
	require_once LEGIT_LOCAL_DIR . 'includes/classes/sections/ninja-forms/ninja-forms-ajax.php';
}
