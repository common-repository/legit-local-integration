<?php

namespace LegitLocal;

class NinjaFormsAjax{

	protected $options;
	protected $customer_info;
	public function __construct(){
		$options_singleton = LegitLocalOptions::get_instance();
		$this->options = $options_singleton->get_options();

		add_action( 'admin_enqueue_scripts', array( $this, 'include_ninja_forms_ajax_scripts' ) );
		add_action( 'wp_ajax_get_new_ninja_forms_fields', array( $this, 'get_new_ninja_forms_fields' ) );
	}

	public function include_ninja_forms_ajax_scripts(){
		wp_enqueue_script( 'll-integration-nf', LEGIT_LOCAL_URL . 'assets/js/NinjaFormsAjaxIntegration.js', array( 'jquery' ) );
	}

	public function get_new_ninja_forms_fields(){

		$name_field = $this->get_new_ninja_forms_name_field( $_POST['form_id'] );
		$contact_field = $this->get_new_ninja_forms_contact_field( $_POST['form_id'] );

		return wp_send_json_success( array( 'name_field' => $name_field, 'contact_field' => $contact_field ) );
	}

	protected function get_new_ninja_forms_name_field( $form_id ){
    	$fields = Ninja_Forms()->form( $form_id )->get_fields();

    	ob_start();
    	?>
    	<select name="legit_local_options[ninja_forms_name_field]">
    		<option value="">Select a Field</option>
    		<?php
    		foreach( $fields as $field ){
    			$id = $field->get_id();
    			$settings = $field->get_settings();

    			printf(
    				'<option value="%s">%s</option>',
    				$id,
    				$settings['label']
    			);
    		}
    		?>
    	</select>
    	<?php
    	return ob_get_clean();
	}

	protected function get_new_ninja_forms_contact_field( $form_id ){
    	$fields = Ninja_Forms()->form( $form_id )->get_fields();

    	ob_start();
    	?>
    	<select name="legit_local_options[ninja_forms_contact_field]">
    		<option value="">Select a Field</option>
    		<?php
    		foreach( $fields as $field ){
    			$id = $field->get_id();
    			$settings = $field->get_settings();

    			printf(
    				'<option value="%s">%s</option>',
    				$id,
    				$settings['label']
    			);
    		}
    		?>
    	</select>
    	<?php
    	return ob_get_clean();
	}
}

$ninja_forms_ajax = new NinjaFormsAjax;
