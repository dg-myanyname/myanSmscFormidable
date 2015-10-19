<?php

class MyanSmscFormidableAction extends FrmFormAction {

	function __construct() {
		$action_ops = array(
			'classes'   => 'dashicons dashicons-format-aside myan-smsc',
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
			<th><label>Content</label></th>
			<td>
				<textarea class="large-text" rows="5" cols="50" name="<?php echo $action_control->get_field_name('sms_body_template') ?>"><?php echo esc_attr($form_action->post_content['sms_body_template']); ?></textarea>
			</td>
		</tr>
		</tbody>
		</table>
<?php }
	
	/**
	* Add the default values for the options
	*/
	function get_defaults() {
		return array(
			'template_name' => '',
			'sms_body_template'=> '',
		);
	}
}