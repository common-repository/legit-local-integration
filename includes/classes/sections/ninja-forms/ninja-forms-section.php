<?php

namespace LegitLocal;

class NinjaFormsSection{

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
		 * NINJA FORMS
		 */
		add_settings_section(
            'legit_local_ninja_forms', // ID
            'Ninja Forms Integration', // Title
            array( __CLASS__, 'legit_local_ninja_forms_callback' ), // Callback
            'll-ninja-forms' // Page
        );
	}

	private static function add_fields(){
		/**
		 * NINJA FORMS
		 */
		add_settings_field(
            'enable_ninja_forms_integration', // ID
            'Enable Ninja Forms Integration', // Title
            array( __CLASS__, 'enable_ninja_forms_integration_callback' ), // Callback
            'll-ninja-forms', // Page
            'legit_local_ninja_forms' // Section
        );

        add_settings_field(
            'ninja_forms_days_to_wait',
            'Days to wait before sending contact email',
            array( __CLASS__, 'ninja_forms_days_to_wait_callback' ),
            'll-ninja-forms',
            'legit_local_ninja_forms'
        );

        if( ! function_exists( 'Ninja_Forms' ) ){
        	return;
        }

        add_settings_field(
            'ninja_forms_selected_form',
            'Select Form<br><span style="font-weight:normal; color:orange" id="form-ajax-error-message"></span>',
            array( __CLASS__, 'ninja_forms_selected_form_callback' ),
            'll-ninja-forms',
            'legit_local_ninja_forms'
        );

        if( ! self::$options['ninja_forms_selected_form'] || ! Ninja_Forms()->form( self::$options['ninja_forms_selected_form'] )->get() ){
        	return;
        }

        add_settings_field(
            'ninja_forms_name_field',
            'Name Field:<br/><span style="font-weight:normal;">(Select the field of this form to use for the contact name in Legit Local)</span>',
            array( __CLASS__, 'ninja_forms_name_field_callback' ),
            'll-ninja-forms',
            'legit_local_ninja_forms'
        );

        add_settings_field(
            'ninja_forms_contact_field',
            'Contact Field:<br/><span style="font-weight:normal;">(Select the field of this form to use for the contact method in Legit Local)</span>',
            array( __CLASS__, 'ninja_forms_contact_field_callback' ),
            'll-ninja-forms',
            'legit_local_ninja_forms'
        );
	}

	/**
	 * NINJA FORMS
	 */
	public static function legit_local_ninja_forms_callback(){
		$feature_description = '<hr>Connect with customers through Ninja Forms.';
		if( ! LL_NINJA_FORMS_ACTIVE ){
			$feature_description .= '<br><span style="color:red;">WARNING: Ninja Forms is not active. Ninja Forms must be installed and activated before Ninja Forms Integration can be used</span>';
		}

		echo $feature_description;
	}

    public static function enable_ninja_forms_integration_callback(){
    	if( ! isset( self::$options['enable_ninja_forms_integration'] ) ) self::$options['enable_ninja_forms_integration'] = 0;

        printf(
            '<input type="checkbox" id="enable_ninja_forms_integration" name="legit_local_options[enable_ninja_forms_integration]" %s />',
            checked( self::$options['enable_ninja_forms_integration'], true, false )
        );
    }

    public static function ninja_forms_days_to_wait_callback(){
    	printf(
    		'<input type="text" id="ninja_forms_days_to_wait" name="legit_local_options[ninja_forms_days_to_wait]" placeholder="3" size="3" value="%s" />',
    		isset( self::$options['ninja_forms_days_to_wait'] ) ? esc_attr( self::$options['ninja_forms_days_to_wait'] ) : ''
    	);
    }

    public static function ninja_forms_selected_form_callback(){
    	if( ! isset( self::$options['ninja_forms_selected_form'] ) ) self::$options['ninja_forms_selected_form'] = '';

    	?>
    	<select id="js-ll-ninja-form-select" name="legit_local_options[ninja_forms_selected_form]">
    		<option value="">Select a Form</option>
	    	<?php 
	    	foreach( Ninja_Forms()->form()->get_forms() as $form ){
	    		printf(
	    			'<option value="%s" %s>%s</option>',
	    			$form->get_id(),
	    			( self::$options['ninja_forms_selected_form'] == $form->get_id() ) ? 'selected' : '',
	    			$form->get_settings()['title']
	    		);
	    	}
	    	?>
	    </select>
	    <?php
    }

    public static function ninja_forms_name_field_callback(){
    	if( ! isset( self::$options['ninja_forms_name_field'] ) ) self::$options['ninja_forms_name_field'] = '';
    	$fields = Ninja_Forms()->form( self::$options['ninja_forms_selected_form'] )->get_fields();

    	?>
    	<select id="js-ll-name-select" name="legit_local_options[ninja_forms_name_field]">
    		<option value="">Select a Field</option>
    		<?php
    		foreach( $fields as $field ){
    			$id = $field->get_id();
    			$settings = $field->get_settings();

    			printf(
    				'<option value="%s" %s>%s</option>',
    				$id,
    				( self::$options['ninja_forms_name_field'] == $id ) ? 'selected' : '',
    				$settings['label']
    			);
    		}
    		?>
    	</select>
    	<?php
    }

    public static function ninja_forms_contact_field_callback(){
    	if( ! isset( self::$options['ninja_forms_contact_field'] ) ) self::$options['ninja_forms_contact_field'] = '';
    	$fields = Ninja_Forms()->form( self::$options['ninja_forms_selected_form'] )->get_fields();

    	?>
    	<select id="js-ll-contact-select" name="legit_local_options[ninja_forms_contact_field]">
    		<option value="">Select a Field</option>
    		<?php
    		foreach( $fields as $field ){
    			$id = $field->get_id();
    			$settings = $field->get_settings();

    			printf(
    				'<option value="%s" %s>%s</option>',
    				$id,
    				( self::$options['ninja_forms_contact_field'] == $id ) ? 'selected' : '',
    				$settings['label']
    			);
    		}
    		?>
    	</select>
    	<?php
    }
}
