<?php
/**
* 
*/
class MyanSmscFormidable
{
	public static $plugin_path = NULL;
	
	function __construct($plugin_path)
	{
		self::$plugin_path = $plugin_path;
		add_action('frm_registered_form_actions', array(__CLASS__, 'register_myan_smsc_frm_action'));
		add_action('frm_trigger_myan_smsc_action_name_create_action', array(__CLASS__, 'myan_smsc_action_create_frm_trigger'), 10, 3);
	}

	function register_myan_smsc_frm_action( $actions )
	{
	    $actions['myan_smsc_action_name'] = 'MyanSmscFormidableAction';
	    include_once(self::$plugin_path . 'classes/MyanSmscFormidableAction.php');
	    return $actions;
	}
	
	function myan_smsc_action_create_frm_trigger($action, $entry, $form) {
		$sms_body = $action->post_content['sms_body_template'];
		$frm_fields_values = $entry->metas;
		preg_match_all('/\[(\d.)\]/', $sms_body, $field_ids);
		if(!count($field_ids[1]))
			return;
		foreach ($field_ids[1] as $field_id) {
			$preg = '/\['.$field_id.'\]/';
			$sms_body = preg_replace($preg, $frm_fields_values[$field_id], $sms_body);
		}
		list($sms_id, $sms_cnt, $cost, $balance) = send_sms(SMSC_MYAN_PHONE, $sms_body);
	}
}