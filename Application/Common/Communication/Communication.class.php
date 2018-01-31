<?php
namespace Common\Communication;
class Communication{
	public static function request($url,$requestData=array()){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_AUTOREFERER,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		if(!empty($requestData)){
		    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
		    curl_setopt($ch,CURLOPT_POSTFIELDS,$requestData);
		}else{
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		}
		$output = curl_exec($ch);
	    if(curl_errno($ch)){
	        echo curl_error($ch);
	    }
	    return json_decode($output);
    }
}