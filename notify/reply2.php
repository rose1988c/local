<?php
/**
 * 自动回帖1024 - curl 方式
 * 
 * @author: Cyw
 * @email: rose1988.c@gmail.com
 * @created: 2013-9-25
 * @logs: 
 * header("content-Type: text/html; charset=Utf-8");
 */


//$cookie = '227c9_ck_info=%2F%09; 227c9_winduser=UFADU1RRPAUGBwAGCQFWBgQHU1QABAEEDQBXXVcAAVdSB1NQVwYC; 227c9_groupid=8; 227c9_ipfrom=76f39ddf391e3c4b62c62d6176951d22%09%D5%E3%BD%AD%CA%A1%B5%E7%D0%C5; 227c9_lastfid=0; 227c9_lastvisit=0%091379936314%09%2Fread.php%3Ftid%3D957730%26page%3De%26; CNZZDATA950900=cnzz_eid%3D1328083174-1379934639-http%253A%252F%252Fcl.man.lv%26ntime%3D1379934639%26cnzz_a%3D12%26retime%3D1379939415767%26sin%3D%26ltime%3D1379939415767%26rtime%3D0';
//$host = 'cl.man.lv';

class AutoReply
{
    protected $_user, $_password;
    protected $_cookie = '', $_host = 'cl.man.lv';
    
    /**
     * 构造函数
     * 
     * @param unknown $user
     * @param unknown $password
     */
    public function __construct($user, $password)
    {
        if ($user === '' || $password === '')
        {
            return;
        }
    
        $this->_user = $user;
        $this->_password = $password;
    
        $this->_login();
    }
    
    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->_logout();
    }
    
    protected function _checkLogin()
    {
        //获取cookie
        
        //返回cookie
        
        //如果回帖不成功，删除cookie
    }
    
    /**
     * 登录
     * @return string
     */
    protected function _login()
    {
    
       $uri = 'http://' . $this->_host . '/login.php';
        
       $param = array(
            'pwuser'=> $this->_user,
            'pwpwd'=> $this->_password,
            'forward'=> urlencode($this->_host . '/post.php?'),
            'jumpurl'=> urlencode($this->_host . '/post.php?'),
            'jumpurl'=> '',
            'step'=> '2',
            'cktime'=> 31536000,
        );
        
        $get_login = $this->curl_get($uri, true, $param);
    }
    
    /**
     * 公共方法
     */
    public function reply($msg = '1024')
    {
        $uri = 'http://' . $this->_host;
        $replyurl = $uri . '/post.php';
    
        $randTid = $this->randTid();
    
        $postarr = array(
            'atc_usesign'=> '1',
            'atc_convert'=> '1',
            'atc_autourl'=> '1',
            'atc_title'=> '1024',
            'atc_content'=> $msg,
            'step'=> '2',
            'action'=> 'reply',
            'fid'=> '7',
            'tid'=> $randTid,
            'atc_attachment'=> 'none',
            'verify'=> 'verify'
        );
    
        $get_reply = $this->curl_get($replyurl, true, $postarr);
        
        // 模式定界符后面的 "i" 表示不区分大小写字母的搜索
        if (preg_match ("/發貼完畢/i", $get_reply)) {
            echo 'success!';
        } else if (preg_match ("/1024/i", $get_reply)) {
            echo '灌水預防機制已經打開，在1024秒內不能發貼';
        } else if (strpos($get_reply, '登陆')) {
            echo '登陆失败';
        } else {
            echo "<pre>";
            print_r($get_reply);
            echo "<pre>";
            die();
        }
        //echo iconv('GBK', 'UTF-8', urldecode($value))."-未知错误<br/>";
    }
    
    /**
     * 随机帖子id
     * @return unknown
     */
    protected function randTid()
    {
        //技术讨论区
        $uri = 'http://' . $this->_host . '/thread0806.php?fid=7';
        
        $get_url = $this->curl_get($uri);
        
        preg_match_all('/<a href="htm_data(.*?)"/', $get_url, $matches);
        
        $random = rand(7, 10);
        
        $url = $matches[0][$random];
        
        $url = substr($url, 9);
        $url = rtrim($url, '"');
        
        $urls = explode('/', $url);
        $urls = explode('.', $urls[3]);
        
        $tid = $urls[0];
        
        return $tid;
    }
    
    /**
     * base
     *
     * @param unknown $url
     * @param string $ua
     * @param string $post_data
     * @return mixed
     */
    function curl_get($url, $ua=false, $post_data=''){
        
        $ch = curl_init($url);
        
        if ($ua){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Mozilla/5.0 (Linux; U; Android 2.3.4; zh-cn; W806 Build/GRJ22) AppleWebKit/530.17 (KHTML, like Gecko) FlyFlow/2.4 Version/4.0 Mobile Safari/530.17 baidubrowser/042_1.8.4.2_diordna_008_084/AIDIVN_01_4.3.2_608W/1000591a/9B673AC85965A58761CF435A48076629%7C880249110567268/1'));
        }
        else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0','Connection:keep-alive','Referer:http://wapp.baidu.com/'));
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        
        if (is_array($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        
        curl_setopt($ch, CURLOPT_COOKIE, $this->_cookie);
        
        //启用时会将头文件的信息作为数据流输出
        curl_setopt($ch,CURLOPT_HEADER, 1);
        
        $content = curl_exec($ch);
        
        //解析Cookie - 需要开启头信息
        preg_match_all('/.*?\r\nSet-Cookie: (.*?);.*?/si', $content, $matches);
        if (isset($matches[1]) && $this->_cookie == '')
        {
            $this->_cookie = implode('; ', $matches[1]);
        }
        curl_close($ch);
        
        return $content;
    }
    

    /**
     * 获取cookie
     *
     * @param unknown $url
     */
    protected function getCookie($url = false)
    {
        $url = $url === false ? $this->_host : $url;
    
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
    
        //解析Cookie
        preg_match_all('/.*?\r\nSet-Cookie: (.*?);.*?/si', curl_exec($ch), $matches);
        if (isset($matches[1]))
        {
            $this->_cookie = implode('; ', $matches[1]);
        }
    
        return $this->_cookie;
    }
    
    protected function _logout()
    {
        //$uri = '/im/index/logoutsubmit.action';
        //$result = $this->_postWithCookie($uri, '');
        //
        //return $result;
    }
    
}

//----------------------------------------------------------
$reply = new AutoReply('user', 'xxxx...');
$reply->reply();
