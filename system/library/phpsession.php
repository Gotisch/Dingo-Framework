<?php if(!defined('DINGO')){die('External Access to File Denied');}

/**
 * Session Library For Dingo Framework that replaces the Database based session.
 *
 * @author          Gotisch
 * @copyright       2011
 */
class session
{
	/**
	 * gets a value.
	 */
	public static function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return FALSE;
		}
	}
	/**
	 * sets a value
	 */
	public static function set($name,$value,$cookie=FALSE)
	{
		$_SESSION[$name] = $value;
	}
	/**
	 * updates a value (same results as set)
	 */
	public static function update($name,$value)
	{
		session::set($name, $value);
	}
	/**
	 * deletes a value
	 */
	public static function delete($name,$cookie=FALSE)
	{
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}
}
session_start();
?>