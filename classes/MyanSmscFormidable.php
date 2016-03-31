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
		if(!MyanSmsc::$noSms)
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
		$frm_phones = $action->post_content['form_phone_numbers'];
		$frm_phones_array = MyanSmsc::csv_phones_to_array($frm_phones);
		// массив с id полей и значениями (39 => Юрий)
		$frm_fields_values = $entry->metas;
		// найдем в теле сообщения шорткоды полей (например [39])
		preg_match_all('/\[(\d.)\]/', $sms_body, $field_ids);
		// если нашли шорткоды
		if(count($field_ids[1])){
			foreach ($field_ids[1] as $field_id) {
				$preg = '/\['.$field_id.'\]/';
				// заменим шорткоды на соответствующие значения
				$sms_body = preg_replace($preg, $frm_fields_values[$field_id], $sms_body);
			}
		}
		$sms_phones = false;
		// если у формы номера заполнены, то отправлять на них
		// если нет, то взять номера из общего поля
		if($frm_phones_array) $sms_phones = $frm_phones_array;
		elseif (MyanSmsc::$sms_phones) $sms_phones = MyanSmsc::$sms_phones;
		// отправляем смс на каждый номер
		if($sms_phones){
			foreach ($sms_phones as $phone) {
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $sms_body);
			}
		}
	}
}