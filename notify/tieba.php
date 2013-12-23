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
    protected $_cookie = '', $_host = 'tieba.baidu.com';
    
    /**
     * 构造函数
     * 
     * @param unknown $user
     * @param unknown $password
     */
    public function __construct()
    {
        //$this->_login();
        
        $this->_cookie = 'WATER_CHECK=1; BAIDUID=27681E0736A63C82C631BE129A4B0B13:FG=1; TIEBA_USERTYPE=68763e20f213d3420b0d56e0; bdshare_firstime=1372044635505; SSUDBTSP=1372136332; SSUDB=05wc3lVSjlxNGo4dWZCN0NkRnNSTEdFWFh3ZmJEUE9kajFzdmZEUzFRaU1zUEJSQVFBQUFBJCQAAAAAAAAAAAEAAAAPMasH0NDX39Ta16q9xwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIwjyVGMI8lRR; locale=zh; TIEBAUID=7d1745759925abb7af100dce; BAIDU_WISE_UID=44B58CB95617D23B1055D2D2CDB58430; poptip_1369124870=1; BDUSS=UteUh6cVlQbFpTYno1UFFyVmVnSzh0NmljRzQydlkzZnRVcHFCWE55dTNjRTVTQVFBQUFBJCQAAAAAAAAAAAEAAAAPMasH0NDX39Ta16q9xwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALfjJlK34yZSaX; bacard=1; BDREFER=%7Burl%3A%22http%3A//sports.baidu.com/%22%2Cword%3A%22%22%7D; cflag=65535%3A1; MCITY=-%3A; GET_TOPIC=128659727; H_PS_PSSID=3407_2776_1431_3090; wise_device=0';
    }
    
    /**
     * 析构函数
     */
    public function __destruct()
    {
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
        $replyurl = $uri . '/f/commit/post/add';
    
        $postarr = array(
            'kw'    => '死神',
            'ie'    => 'utf-8',
            'rich_text' => '1',
            'floor_num' => '60',
            'fid'   => '122873',
            'tid'   => '2614438822',
            'lp_type'   => '0',
            'lp_sub_type'   => '0',
            'content'   => '写的不错',
            'sign_id'   => '10923902',
            'anonymous' => '0',
            'tbs'   => '30640e3ab1e2ed441380100007',
            'tag'   => '11',
            'new_vcode' => '1',
        );
    
        $get_reply = $this->curl_get($replyurl, true, $postarr);
        
        echo "<pre>";
        print_r($get_reply);
        echo "<pre>";
        die();
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
$reply = new AutoReply();
$reply->reply();
