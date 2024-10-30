<?php

namespace LegitLocal;

final class LegitLocalOptions{

	protected $options = array();
    
    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function get_instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new self();
        }
        return $inst;
    }

    public function get_options(){
    	if( ! $this->options ) $this->options = get_option( 'legit_local_options' );
    	return get_option( 'legit_local_options' );
    }

    /**
     * Private constructor so nobody else can instance it
     */
    private function __construct(){}

    /**
     * Private clone so nobody else can instance it
     */
    private function __clone(){}


    /**
     * Throw exception on wakeup attempt
     */
    public function __wakeup(){
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public static function sanitize( $input ){
        $new_input = array();

        // sanitize the array
        foreach ($input as $key => $value) {
            $new_input[$key] = sanitize_text_field( $value );
        }

        // figure out which checkboxes are checked
        $checkboxArray = array( 'enable_woocommerce_integration', 'enable_ninja_forms_integration' );
        foreach( $checkboxArray as $checkboxField ) {
            $new_input[$checkboxField] = isset( $input[$checkboxField] ) ? 1 : 0;
        }

        return $new_input;
    }
}