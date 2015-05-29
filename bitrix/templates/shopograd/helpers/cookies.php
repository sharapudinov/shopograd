<?
// Cookies are set using this two URI params: set_cookie_name and set_cookie_value
// We must validate processed data using additional set_cookie_hash param
// Hash is generated according to this rule: md5(set_cookie_name . set_cookie_value . secret_word)
$cookies_secret_word = 'setcookie';
if ($_REQUEST['set_cookie_name'] and $_REQUEST['set_cookie_value']) {
	if ($_REQUEST['set_cookie_hash'] == md5($_REQUEST['set_cookie_name'] . $_REQUEST['set_cookie_value'] . $cookies_secret_word)) {
		$APPLICATION->set_cookie($_REQUEST['set_cookie_name'], $_REQUEST['set_cookie_value']);
		$_COOKIE[$_REQUEST['set_cookie_name']]	= $_REQUEST['set_cookie_value'];
	}
}
?>