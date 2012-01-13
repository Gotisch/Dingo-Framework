<?php if(!defined('DINGO')){die('External Access to File Denied');}
/**
 * Request library for Dingo framework that gets and validates $_POST and $_GET parameters.
 * see http://wiki.phpbb.com/Function.request_var for documentation.
 * most code is shamelessly stolen from phpbb source. 
 *
 * @author          mostly phpbb team, Gotisch
 * @copyright       2011
 */
class request
{
	public static function get($var_name, $default, $multibyte = true, $cookie = false)
	{
		if (!$cookie && isset($_COOKIE[$var_name])) {
			if (!isset($_GET[$var_name]) && !isset($_POST[$var_name])) {
				return (is_array($default)) ? array() : $default;
			}
			$_REQUEST[$var_name] = isset($_POST[$var_name]) ? $_POST[$var_name] : $_GET[$var_name];
		}
		$super_global = ($cookie) ? '_COOKIE' : '_REQUEST';
		if (!isset($GLOBALS[$super_global][$var_name]) || is_array($GLOBALS[$super_global][$var_name]) != is_array($default)) {
			return (is_array($default)) ? array() : $default;
		}
		$var = $GLOBALS[$super_global][$var_name];
		if (!is_array($default)) {
			$type = gettype($default);
		} else {
			list($key_type, $type) = each($default);
			$type = gettype($type);
			$key_type = gettype($key_type);
			if ($type == 'array') {
				reset($default);
				$default = current($default);
				list($sub_key_type, $sub_type) = each($default);
				$sub_type = gettype($sub_type);
				$sub_type = ($sub_type == 'array') ? 'NULL' : $sub_type;
				$sub_key_type = gettype($sub_key_type);
			}
		}
		if (is_array($var))	{
			$_var = $var;
			$var = array();
			foreach ($_var as $k => $v)	{
				request::set_var($k, $k, $key_type);
				if ($type == 'array' && is_array($v)) {
					foreach ($v as $_k => $_v) {
						if (is_array($_v)) {
							$_v = null;
						}
						request::set_var($_k, $_k, $sub_key_type, $multibyte);
						request::set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
					}
				} else {
					if ($type == 'array' || is_array($v)) {
						$v = null;
					}
					request::set_var($var[$k], $v, $type, $multibyte);
				}
			}
		} else {
			request::set_var($var, $var, $type, $multibyte);
		}
		return $var;
	}
	/**
	 * request::set_var
	 *
	 * Set variable, used by {@link request_var the request_var function}
	 *
	 * @access private
	 */
	static function set_var(&$result, $var, $type, $multibyte = false)
	{
		settype($var, $type);
		$result = $var;
		if ($type == 'string')
		{
			$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $result), ENT_COMPAT, 'UTF-8'));
			if (!empty($result))
			{
				// Make sure multibyte characters are wellformed
				if ($multibyte)
				{
					if (!preg_match('/^./u', $result))
					{
						$result = '';
					}
				}
				else
				{
					// no multibyte, allow only ASCII (0-127)
					$result = preg_replace('/[\x80-\xFF]/', '?', $result);
				}
			}
			//$result = (STRIP) ? stripslashes($result) : $result;
		}
	}
}
