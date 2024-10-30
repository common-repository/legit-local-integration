<?php

namespace LegitLocal;

class WooCommerceSection{

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
		 * WOOCOMMERCE
		 */
		add_settings_section(
            'legit_local_woocommerce', // ID
            'WooCommerce Integration', // Title
            array( __CLASS__, 'legit_local_woocommerce_callback' ), // Callback
            'll-woocommerce' // Page
        );
	}

	private static function add_fields(){
		/**
		 * WOOCOMMERCE
		 */
		add_settings_field(
            'enable_woocommerce_integration', // ID
            'Enable WooCommerce Integration', // Title
            array( __CLASS__, 'enable_woocommerce_integration_callback' ), // Callback
            'll-woocommerce', // Page
            'legit_local_woocommerce' // Section
        );

        add_settings_field(
            'woocommerce_days_to_wait',
            'Days to wait before sending contact email',
            array( __CLASS__, 'woocommerce_days_to_wait_callback' ),
            'll-woocommerce',
            'legit_local_woocommerce'
        );

        add_settings_field(
            'woocommerce_contact_method',
            'Contact Method',
            array( __CLASS__, 'woocommerce_contact_method_callback' ),
            'll-woocommerce',
            'legit_local_woocommerce'
        );
	}

	/**
	 * WOOCOMMERCE
	 */
	public static function legit_local_woocommerce_callback(){
		$feature_description = '<hr>Connect with customers through WooCommerce.';
		if( ! LL_WOOCOMMERCE_ACTIVE ){
			$feature_description .= '<br><span style="color:red;">WARNING: WooCommerce is not active. WooCommerce must be installed and activated before WooCommerce Integration can be used</span>';
		}

		echo $feature_description;
	}

    public static function enable_woocommerce_integration_callback(){
    	if( ! isset( self::$options['enable_woocommerce_integration'] ) ) self::$options['enable_woocommerce_integration'] = 0;
    	
        printf(
            '<input type="checkbox" id="enable_woocommerce_integration" name="legit_local_options[enable_woocommerce_integration]" %s />',
            checked( self::$options['enable_woocommerce_integration'], true, false )
        );
    }

    public static function woocommerce_days_to_wait_callback(){
    	printf(
    		'<input type="text" id="woocommerce_days_to_wait" name="legit_local_options[woocommerce_days_to_wait]" placeholder="3" size="3" value="%s" />',
    		isset( self::$options['woocommerce_days_to_wait'] ) ? esc_attr( self::$options['woocommerce_days_to_wait'] ) : ''
    	);
    }

    public static function woocommerce_contact_method_callback(){
    	if( ! isset( self::$options['woocommerce_contact_method'] ) ) self::$options['woocommerce_contact_method'] = '';
    	$contact_methods = array( 'email' => 'Email', 'phone' => 'Phone' );
    	
    	?>
    		<select name="legit_local_options[woocommerce_contact_method]">
    			<option value="">Select a Contact Method</option>
    			<?php
    			foreach( $contact_methods as $key => $contact_method ){
    				printf(
    					'<option value="%s" %s>%s</option>',
    					$key,
    					( self::$options['woocommerce_contact_method'] == $key ) ? 'selected' : '',
    					$contact_method
    				);
    			}
    			?>
    		</select>
    	<?php
    }
}
