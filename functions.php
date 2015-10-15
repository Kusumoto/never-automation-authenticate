<?php
include('simple_html_dom.php');

function Authenticate()
{
	/* Get Global Variable */
	global $LineLoginURL;
	global $LineAuthorizeURL;
	global $LineGetRSA;
	global $LineAccountEmail;
	global $LineAccountPassword;
	/* Line Authentication Zone */
	$LineLogin = GetDataFromURL($LineLoginURL,null,null);
	$LineLoginForm = ExtractFormByNameNoName($LineLogin); 
	$GetLineRSAKey = GetDataFromURL($LineGetRSA . '?_=' . time() ,null,$LineLoginURL);
	$GetLineRSAKey = json_decode($GetLineRSAKey,true);
	$LineRSAKey = explode(',', $GetLineRSAKey['rsa_key']);
	/* 
		-----------------------------------------------
		NEVER LINE RSA Algorithrm (Removed)
		-----------------------------------------------
	*/
	$LineLoginForm['userId'] = $LineAccountEmail;
	$LineLoginForm['id'] = $LineRSAKey[0];
	$LineLoginForm['password'] = $PasswordEncoded;
	$LineLoginForm['idProvider'] = 1;
	unset($LineLoginForm['tid']);
	unset($LineLoginForm['tpasswd']);
	unset($LineLoginForm['0']);
	unset($LineLoginForm['captcha']);
	$LineAuthorize = GetDataFromURL($LineAuthorizeURL,$LineLoginForm,$LineLoginURL);
}

function ExtractFormByID($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[id="'.$formname.'"] input, form[id="'.$formname.'"] select') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}

function ExtractFormByName($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[name="'.$formname.'"] input, form[name="'.$formname.'"] select') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}

function ExtractFormByNameNoName($contents)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form input') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}


function GetDataFromURL($url,$parameter,$refer_url)
{
	$COOKIEFILE = 'linetv-cookies.txt';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $COOKIEFILE);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $COOKIEFILE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_URL, $url);
	if ($refer_url) curl_setopt($ch, CURLOPT_REFERER, $refer_url);
	if (count($parameter) != 0)
	{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
	} 
	$data = curl_exec($ch);
	return $data;
}

function getTimeNow()
{
	// Set Date-Time Zone
	date_default_timezone_set('Asia/Bangkok');
	return date('Y-m-d H:m:s');
}

?>

