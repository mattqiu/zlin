<?php
/**
 * This client extends the ususal SoapClient to handle mtom encoding. Due
 * to mtom encoding soap body has test apart from valid xml. This extension
 * remove the text and just keeps the response xml.
 */
defined('InIMall') or exit('Access Invalid!');
 
class MTOMSoapClient extends SoapClient {
	public function __doRequest($request, $location, $action, $version, $one_way = 0) {
		$response = parent::__doRequest($request, $location, $action, $version, $one_way);
		//转换为xml格式,调试打印
		//$response = htmlentities($response);
		//print_r($response);
		//if resposnse content type is mtom strip away everything but the xml.
		if (strpos($response, "Content-Type: application/xop+xml") !== false) {
			//not using stristr function twice because not supported in php 5.2 as shown below
			//$response = stristr(stristr($response, "<s:"), "</s:Envelope>", true) . "</s:Envelope>";
			$tempstr = stristr($response, "<soap:Envelope");
			$response = substr($tempstr, 0, strpos($tempstr, "</soap:Envelope>")) . "</soap:Envelope>";
		}
		//print_r("<br>new:<br>".$response);
		return $response;
	}
}
?>