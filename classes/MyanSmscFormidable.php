<?php

class MyanSmscFormidable extends FrmFormAction {

	function __construct() {
		$action_ops = array(
		    'classes'   => 'myan_sms',
		    'limit'     => 99,
		    'active'    => true,
		    'priority'  => 50,
		);
		
	    $this->FrmFormAction('myan_smsc_action_name', __('Myan smsc', 'formidable'), $action_ops);
	}

	/**
	* Get the HTML for your action settings
	*/
	function form( $form_action, $args = array() ) {
	    extract($args);
	    $action_control = $this; ?>

	    <table class="form-table frm-no-margin">
	    <tbody>
	    <tr>
		    <th>
			    <label>Номер получателя</label>
		    </th>
		    <td>
		    	<input type="text" class="large-text" value="<?php echo esc_attr($form_action->post_content['reciepient_number']); ?>" name="<?php echo $action_control->get_field_name('reciepient_number') ?>">
		    </td>
	    </tr>
	    <tr>
		    <th>
		    	<label>Сообщение></label>
		    </th>
		    <td>
		    	<textarea class="large-text" rows="5" cols="50" name="<?php echo $action_control->get_field_name('sms_body') ?>"><?php echo esc_attr($form_action->post_content['sms_body']); ?></textarea>
		    </td>
	    </tr>
	    </tbody>
	    </table>
	    <?php

	    // If you have scripts to include, you can include theme here

	}

	function get_defaults() {
	    return array(
            'reciepient_number'	=> '',
            'sms_body'			=> '',
        );
	}
}