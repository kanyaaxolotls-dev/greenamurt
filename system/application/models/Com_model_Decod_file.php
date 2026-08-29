
<?php
/**
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.4
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 *
 * @ Zend guard decoder PHP 5.6
 **/

exit('No direct script access allowed');
class Common_model
{
	public function __construct()
	{
		parent::__construct();
		$this->__session();
	}

	public function __session()
	{
		if ($_SESSION['gehfgejh'] !== true) {
			$status = $this->curl('https://sign.exolim.com/license/check/' . $_SERVER['HTTP_HOST']);

			if ((trim($status) == '') && ($_SERVER['HTTP_HOST'] !== 'localhost')) {
				echo '<div align="center" style="background-color: #ffb549; color: #fff; padding: 13px; font-size: 12px">Unable to load remote URL using PHP CURL. See if CURL is disabled or Port is blocked.<br/>' . $status . '</div>';
				exit();
			}

			if ((trim($status) !== 'Ok') && !isset($_SESSION['gehfgejh']) && ($_SERVER['HTTP_HOST'] !== 'localhost')) {
				echo $status;
				exit('!');
			}
			else {
				$_SESSION['gehfgejh'] = true;
			}
		}
	}

	public function curl($url, $post = '')
	{
		$curl = curl_init(

		// This is the demo version. This version only decode 30 lines.