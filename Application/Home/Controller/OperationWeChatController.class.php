<?php
namespace Home\Controller;

class OperationWeChatController extends WeChatController{
	//获取微信服务器数据
	public function getWeChatRespons(){
		// echo $this->getStr();die;
		$this->weChatResponse();
		
	}
}

// $token_file = fopen("content.txt","w") or die("Unable to open file!");//打开token.txt文件，没有会新建
//             fwrite($token_file,$data->access_token);//重写tken.txt全部内容
//             fclose($token_file);//关闭文件流