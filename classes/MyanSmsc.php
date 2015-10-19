<?php 

/**
* 
*/
class MyanSmsc
{
	public static $plugin_file = NULL,
			$plugin_dir = NULL,
			$plugin_path = NULL,
			$plugin_url = NULL,
			$forms_ids = array(),
			$add_script = false;
	
	function __construct($file)
	{	
		self::$plugin_file = $file;
		self::$plugin_path = plugin_dir_path($file);
		self::$plugin_dir = plugin_basename(self::$plugin_path);
		self::$plugin_url = plugins_url('', self::$plugin_file);
		
		include_once self::$plugin_path . "assets/smsc_api.php";
		
		add_action('frm_registered_form_actions', array(__CLASS__, 'register_myan_smsc_action'));
		//add_action('frm_trigger_myan_smsc_action_name_create_action', array(__CLASS__, 'myan_smsc_action_create_trigger'), 10, 3);
	}

	function register_myan_smsc_action( $actions )
	{
	    $actions['myan_smsc_action_name'] = 'MyanSmscFormidable';
	    include_once(self::$plugin_path . 'classes/MyanSmscFormidable.php');
	    return $actions;
	}
	
	function myan_smsc_action_create_trigger($action, $entry, $form) {
		$sms_body = $action->post_content['sms_body'];
		$frm_fields_values = $entry->metas;
		//wp_mail( 'dg@myanyname.com', 'The subject', 'The message' );
		preg_match_all('/\[(\d.)\]/', $sms_body, $fields_ids);
		if(!count($fields_ids[1]))
			return;
		foreach ($fields_ids[1] as $fields_id) {
			$preg = '/\['.$fields_id.'\]/';
			$sms_body = preg_replace($preg, $frm_fields_values[$fields_id], $sms_body);
		}
		echo '<pre>$sms_body = '.htmlspecialchars(print_r($sms_body, true)).'</pre>';
		//list($sms_id, $sms_cnt, $cost, $balance) = send_sms("380936253140", $sms_body);
	}
}
?>