<?php 

/**
* 
*/
class MyanSmsc
{
	public static 
			$plugin_path = NULL,
			$option_group = 'myan-smsc-options',
			$sms_phones = array();
			/*$plugin_file = NULL,
			$plugin_dir = NULL,
			$plugin_url = NULL,;*/
	
	function __construct($file)
	{	
		self::$plugin_path = plugin_dir_path($file);
		/*self::$plugin_file = $file;
		self::$plugin_dir = plugin_basename(self::$plugin_path);
		self::$plugin_url = plugins_url('', self::$plugin_file);*/
		
		if(is_admin()){
			add_action( 'admin_menu', array(__CLASS__, 'register_myan_smsc_menu_page') );
			add_action( 'admin_init', array(__CLASS__,'register_myan_smsc_setting') );
		}
		
		$noSms = get_option('myan-smsc-nosms', 0);
		if (!$noSms) {
			self::$sms_phones = explode(",", get_option('myan-smsc-phone'));
			foreach (self::$sms_phones as $key => $phone) {
				$phone = trim($phone);
				if(!preg_match('/^380\d{9}/', $phone)) unset(self::$sms_phones[$key]);
				else self::$sms_phones[$key] = $phone;
			}
			if (!empty(self::$sms_phones)) {
				include_once self::$plugin_path . "assets/smsc_api.php";
				define("SMSC_LOGIN", get_option('myan-smsc-login'));
				define("SMSC_PASSWORD", get_option('myan-smsc-pass'));
				//define("SMSC_MYAN_PHONE", get_option('myan-smsc-phone'));
				include_once self::$plugin_path . "classes/MyanSmscFormidable.php";
				$myanSmsc = new MyanSmscFormidable(self::$plugin_path);
			}
		}
	}

	public function register_myan_smsc_menu_page()
	{
		add_menu_page( 'Myan smsc options', 'Myan smsc', 'manage_options', self::$option_group, array( __CLASS__,'myan_smsc_menu_page'));
	}

	public function myan_smsc_menu_page()
	{ ?>
		<div class="wrap">
		<h2>Myan smsc options</h2>

		<form method="post" action="options.php">
			<?php settings_fields( self::$option_group ); ?>
			<?php do_settings_sections( self::$option_group ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Номер телефона</th>
					<td>
						<input type="text" name="myan-smsc-phone" value="<?php echo esc_attr( get_option('myan-smsc-phone') ); ?>" size="50" />
						<p class="description">Несколько телефонов должны быть разделены запятой (380112223344, 380556667788)</p>
					</td>
				</tr>
				 
				<tr valign="top">
					<th scope="row">Smsc логин</th>
					<td>
						<input type="text" name="myan-smsc-login" value="<?php echo esc_attr( get_option('myan-smsc-login') ); ?>" />
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Smsc пароль (или MD5-хеш пароля в нижнем регистре)</th>
					<td>
						<input type="text" name="myan-smsc-pass" value="<?php echo esc_attr( get_option('myan-smsc-pass') ); ?>" size="35" />
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Отключить отправку всех смс</th>
					<td>
						<input type="checkbox" name="myan-smsc-nosms" value="1" <?php checked( get_option('myan-smsc-nosms', 0));?> />
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>

		</form>
		</div>
	<?php
	}

	public function register_myan_smsc_setting()
	{
		register_setting( self::$option_group, 'myan-smsc-phone' );
		register_setting( self::$option_group, 'myan-smsc-login' );
		register_setting( self::$option_group, 'myan-smsc-pass' );
		register_setting( self::$option_group, 'myan-smsc-nosms' );
	}
}
?>