<?php
/**
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.4
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 *
 * @ Zend guard decoder PHP 5.6
 **/
defined("BASEPATH") or exit("No direct script access allowed");
class Common_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->__session();
	}


    public function send_message($mobile, $message) {
        $api_url  = 'http://195.201.12.47/wapp/api/send';
        $apikey   = '';

        $data = array(
            'apikey' => $apikey,
            'mobile' => $mobile,
            'msg'    => $message 
        );

        $ch = curl_init($api_url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
	

    // public function send_sms($number, $msg ,$sendername = 'yaglobal',$apikey = 'dfcf84d2920445a5a6be7d5accfae8ab') {
    //     $curl = curl_init();
    //     $url  = "http://195.201.12.47/api/sendsms";

    //     $postData = array(
    //         'number'      => $number,
    //         'sendername'  => $sendername,
    //         'msg'         => $msg,
    //         'apikey'      => $apikey
    //     );

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_POST => true,
    //         CURLOPT_POSTFIELDS => http_build_query($postData),
    //         CURLOPT_HTTPHEADER => array(
    //             'Content-Type: application/x-www-form-urlencoded',
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     if (curl_errno($curl)) {
    //         $error_message = curl_error($curl);
    //     }
    //     curl_close($curl);
    //     return $response;
    // }
	
	public function __session()
	{
		if ($_SESSION["gehfgejh"] !== true) {
		/*	$status = ("Erro" . $_SERVER["HTTP_HOST"]);
			if (trim($status) == "" && $_SERVER["HTTP_HOST"] !== "localhost") {
				echo "<div align=\"center\" style=\"background-color: #ffb549; color: #fff; padding: 13px; font-size: 12px\">Unable to load remote URL using PHP CURL. See if CURL is disabled or Port is blocked.<br/>" . $status . "</div>";
				exit;
			}
			if (trim($status) !== "Ok" && !isset($_SESSION["gehfgejh"]) && $_SERVER["HTTP_HOST"] !== "localhost") {
				echo $status;
				exit("!");
			}*/
			$_SESSION["gehfgejh"] = true;
		}
	}
	
	public function curl($url, $post = "")
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		if (trim($post) !== "") {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		$result = curl_exec($curl);
		curl_close($curl);
		if (trim($result) == "") {
			return file_get_contents($url);
		}
		return $result;
	}
	public function mail($to, $subject, $msg)
	{
		$this->load->library("email");
		$this->email->from(config_item("smtp_user"), config_item("company_name"));
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($msg);
		$this->email->send();
	}
	
	public function convert_currency($from_currency, $to_currency, $amount = 1)
	{
		$data = json_decode($this->curl("https://api.fixer.io/latest?base=" . $from_currency . "&symbols=" . $to_currency . "&amount=" . $amount), true);
		return $data["rates"][$to_currency];
	}
	
	public function sms($number, $msg, $post = "")
	{
		$number = $this->filter($number, "number");
		$url = str_ireplace(array("{{phone}}", "{{msg}}"), array($number, $msg), config_item("sms_api"));
		$this->curl($url, $post);
	}
	
	public function filter($data, $type = "userid")
	{
		switch ($type) {
			case $type == "float":
				return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			case $type == "userid":
				return htmlentities(strip_tags(str_ireplace(config_item("ID_EXT"), "", $data)));
			case $type == "number":
				return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
			case $type == "decimal":
				return number_format($data, 2, ".", ",");
		}
		return htmlentities($data);
	}
}

?>