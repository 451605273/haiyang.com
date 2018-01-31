<?php
namespace Home\Controller;
use Common\Common\publicMethod;
use Common\Communication\Communication;
class WeChatController extends IndexController {
    private $appid = 'wx8756da0fb4beab21';
    private $appsecret = '1eee0ff03496add86a30813e8d862fbb';
    public function getStr(){
        phpinfo();die;
        $s = I('echostr');

		return $s;
    }

    // 获取access_token保存文件
    public function setAccessToken(){
    	$accessToken = publicMethod::build_access_token();
        echo 'seccuss';die;
    }

    //从文件获取access_token
    public function getAccessToken(){
       return publicMethod::read_token();
    }

    //获取微信服务器的ip地址
    public function getServerIp(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $access_token;
        $data = Communication::request($url);
        dump($data);
    }

    //自定义菜单
    public function customMenu(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
        $requestData = '{
             "button":[
             {
                  "type":"view",
                  "name":"琳琳",
                   "url":"http://www.soso.com/"
              },
              {
                  "type":"view",
                  "name":"海洋",
                  "url":"http://www.soso.com/"
              },
              {
                "name":"宝宝们",
                "sub_button":[{
                   "type":"view",
                   "name":"大宝",
                   "url":"http://www.soso.com/"
                },
                {
                    "type":"view",
                   "name":"小宝",
                   "url":"http://www.soso.com/"
                }]
            }
        ]}';

        $data = Communication::request($url,  $requestData,'post');
        dump($data);
    }

    //获取用户消息
    public function weChatResponse(){
      // $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
      $postStr = file_get_contents("php://input");
      file_put_contents('./aaaaaaaaaa.txt',$postStr);
            if(!empty($postStr)){
                 $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                  $RX_TYPE = trim($postObj->MsgType);
                  switch ($RX_TYPE) {
                       case 'event':
                             $result = $this->receiverEvent($postObj);
                             break;
                       }
                    echo $result;
            }else{
                 echo "";
                exit;
            }

    }

    //处理用户消息事件
    public function receiverEvent($object){
        $content = '';
        switch($object->Event){
            case "subscribe":
                // $content = "那一年你起航，未曾欢送"."\n";
                // $content .= "而今你归港，必定十里相迎。"."\n";
                // $content .=  "亲爱的，欢迎回家！".'[em]e121[/em]'."\n";
                // $content .= "<a href='wwww.baidu.com'>"."返校指南"."</a>"."\n";
                // $content .= "<a href='wwww.baidu.com'>"."理工家训"."</a>"."\n";
                // $content .= "<a href='wwww.baidu.com'>"."校友会章程"."</a>"."\n";
                // $content .= "<a href='wwww.baidu.com'>联系我们</a>"."\n";
                $content ="欢迎您关注";
                break;
            case "unsubscribe":
                $content = "";
                break;
        }//switch
        $result = $this->transmitText($object,$content);
        return $result;

    }
     private function transmitText($object, $content, $flag = 0)
    {
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>%d</FuncFlag></xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }

    //授权页面获取用户的openID
    public function getOpenId(){
        $access_token = getAccessToken();
        $SERVER_NAME = $_SERVER['SERVER_NAME'];
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        $redirect_uri = urlencode('http://' . $SERVER_NAME . $REQUEST_URI);
        $code = $_GET['code'];
        if (! $code) {
            // 网页授权
            $autourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
            header("location:$autourl");
        } else {
            // 获取openid
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
            $data = Communication::request($url);
            return ($data['openid']);
        }
    }

    //获取用户的基本信息
    public function getUserInfo(){
       $accessToken= $this->getAccessToken();
        $openId = $this->getOpehnId();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openId&lang=zh_CN";
        $data = Communication::request($url);
        return $data;
    }




}




   
