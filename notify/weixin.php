<?php
// 使用说明：
// 开始要登录
$param = array();
$param['username'] = 'xxxx@qq.com';
$param['pwd'] = 'xxxx';

echo '<pre>';

$wx = new Weixin();
$flag = $wx->login($param);

echo "登录:\n";
var_dump($flag);

echo "\n";
echo "获取分组:\n";
$group = $wx->getGroup();
var_dump($group);

echo "\n";
echo "分组成员:\n";
$group = $wx->getFriendByGroup('2');
var_dump($group);

echo "\n";
echo "最新消息\n";
$msg = $wx->newmesg();
var_dump($msg);

echo "\n";
echo "获取图文:\n";
$mesg = $wx->getMsg();
var_dump($mesg);

echo "\n";
echo "发送消息:\n";

// 说明：如果$content为文字发送文本消息
// 说明：如果$content为图文ID则发送图文消息
//$content = '测试文本'; // 文本
//$content = '10000004'; // 图文
//$mesg = $wx->battchMesgByGroup('101', $content);
//var_dump($mesg);


$param = array(
    'fakeId' => 69720240,
    'content' => '嘿嘿嘿,嘿嘿嘿',
    'msgId' => ''
);
$mesg = $wx->sendmesg($param);
var_dump($mesg);

/**
 * 微信公众平台操作
 * 基本于PHP-CURL
 *
 * @author phpbin
 *
 */
class Weixin
{

    /**
     * PHP curl头部分
     *
     * @var array
     */
    private $_header;

    /**
     * 通讯cookie
     *
     * @var string
     */
    private $_cookie;

    /**
     * 令牌
     *
     * @var string
     */
    private $_token;

    /**
     * 初始化，设置header
     */
    public function __construct()
    {
        $this->_header = array();
        $this->_header[] = "Host:mp.weixin.qq.com";
        $this->_header[] = "Referer:https://mp.weixin.qq.com/cgi-bin/getmessage";
    }

    /**
     * 用户登录
     * 结构 $param = array('username'=>'', 'pwd'=>'');
     *
     * @param array $param
     * @return boolean
     */
    public function login($param)
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN';
        $post = 'username='.urlencode($param['username']).'&pwd='.md5($param['pwd']).'&imgcode=&f=json';
        $stream = $this->_html($url, $post);

        // 判断是不是登录成功
        $html = preg_replace("/^.*\{/is", "{", $stream);
        $json = json_decode($html, true);
        //获取 token
        preg_match("/lang=zh_CN&token=(\d+)/is", $json['ErrMsg'], $match);
        $this->_token = $match[1];

        // 获取cookie
        $this->_cookie($stream);
        return (boolean)$this->_token;
    }

    /**
     * 获取图文消息
     *
     * @return array
     */
    public function getMsg()
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/operate_appmsg?token='.$this->_token.'&lang=zh_CN&sub=list&type=10&subtype=3&t=wxm-appmsgs-list-new&pagesize=10&pageidx=0&lang=zh_CN';
        $stream = $this->_html($url);

        // 分析分组中好友
        preg_match_all('/"appId"\:"(\d+)".*?"title"\:"(.*?)".*?/is', $stream, $matches);
        if ( !is_array($matches[1])) return false;

        $returns = array();
        foreach ( $matches[1] as $key=>$val) {
            $temp = array();
            $returns[$matches[1][$key]] = $matches[2][$key];
        }
        return $returns;
    }

    /**
     * 获取平台分组
     *
     * @return array
     */
    public function getGroup()
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=0&token='.$this->_token.'&lang=zh_CN';
        $stream = $this->_html($url);

        // 分组
        preg_match('/"groups"\:(.*?)\\}\).groups/is', $stream, $match);
        $jsonArr = json_decode($match[1], true);
        $returns = array();
        foreach ( $jsonArr as $key=>$val) {
            $returns[$val['id']] = $val['name'].'('.$val['cnt'].')';
        }
        return $returns;
    }

    /**
     * 获取分组成员
     *
     * @param integer $gId
     * @return array;
     */
    public function getFriendByGroup($gId)
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid='.$gId.'&token='.$this->_token.'&lang=zh_CN';
        $stream = $this->_html($url);

        // 分析分组中好友
        preg_match('/"contacts"\:(.*?)\\}\).contacts/is', $stream, $match);
        $jsonArr = json_decode($match[1], true);

        if ( !is_array($jsonArr)) return false;

        $returns = array();
        foreach ( $jsonArr as $key=>$val) {
            $temp = array();
            $temp['fakeId']     = $val['id'];
            $temp['nickName']   = $val['nick_name'];
            $temp['remarkName'] = $val['remark_name'];
            $returns[] = $temp;
        }
        return $returns;
    }

    /**
     * 批量按组发送
     *
     * @param integer $gId 分组ID
     * @param string $content
     * @return array
     */
    public function battchMesgByGroup($gId, $content)
    {
        $mebInfo = $this->getFriendByGroup($gId);

        if ( false == $mebInfo) return false;

        // 循环发送
        $returns = array();
        foreach ( $mebInfo as $key=>$val)
        {
            $val['content'] = $content;
            $this->sendmesg($val) ? $returns['succ'] ++ : $returns['err']++;
        }
        return $returns;
    }


    /**
     * 发送消息
     *
     * 结构 $param = array(fakeId, content, msgId);
     * @param array $param
     * @return boolean
     */
    public function sendmesg($param)
    {
        $url  = 'https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response';

        // 分类型进行推送
        if ( (int)$param['content']>100000)
        {
            $post = 'error=false&tofakeid='.$param['fakeId'].'&type=10&fid='.$param['content'].'&appmsgid='.$param['content'].'&quickreplyid='.$param['msgId'].'&token='.$this->_token.'&ajax=1';
        } else {
            $post = 'error=false&tofakeid='.$param['fakeId'].'&type=1&content='.$param['content'].'&quickreplyid='.$param['msgId'].'&token='.$this->_token.'&ajax=1';
        }
        echo "<pre>";
        print_r($post);
        echo "<pre>";
        die();

        $this->_header[1] = "Referer:https://mp.weixin.qq.com/cgi-bin/singlemsgpage?msgid=&source=&count=20&t=wxm-singlechat&fromfakeid=".$param['fakeId']."&token=".$this->_token;
        $stream = $this->_html($url, $post);

        // 是不是设置成功
        $html = preg_replace("/^.*\{/is", "{", $stream);
        $json = json_decode($html, true);
        return (boolean)$json['msg'] == 'ok';
    }

    /**
     * 从Stream中提取cookie
     *
     * @param string $stream
     */
    private function _cookie($stream)
    {
        //preg_match_all("/Set-Cookie: (.*?);/is", $stream, $matches);
        preg_match_all("/Set-Cookie: (.*?);/si", $stream, $matches);
        $this->_cookie = @implode(";", $matches[1]);
    }

    /**
     * 获取Stream
     *
     * @param string $url
     * @param string $post
     * @return mixed
     */
    private function _html($url, $post = FALSE)
    {
        ob_start();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        if ( $post){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIE, $this->_cookie);
        //curl_setopt($ch, CURLOPT_PROXY, 'http://10.100.10.100:3128');
        curl_exec($ch);
        curl_close($ch);
        $_str = ob_get_contents();
        $_str = str_replace("script", "", $_str);

        ob_end_clean();
        return $_str;
    }
    /**
     * 获取最新消息
     *
     * 返回结构:id:msgId; fakeId; nickName; content;
     *
     * @return array
     */
    public function newmesg()
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token='.$this->_token;

        $stream = $this->_html($url);

        preg_match('/"msg_item"\:(.*?)\\}\).msg_item/is', $stream, $match);
        $jsonArr = json_decode($match[1], true);

        $returns = array();
        foreach ( $jsonArr as $val){
            if ( isset($val['is_starred_msg'])) continue;
            $returns[] = $val;
        }
        return $returns;
    }
}