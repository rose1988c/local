<?php
/**
 * 自动回帖1024 - fsockopen 方式
*
* @author: Cyw
* @email: rose1988.c@gmail.com
* @created: 2013-9-25
* @logs:
*
*/
class Reply
{

    protected $_user;

    protected $_password;

    protected $_cookie = '';

    protected $_uids = array();

    protected $_csrfToten = null;

    protected $_host = 'cl.man.lv';

    public function __construct($mobile, $password)
    {
        if ($mobile === '' || $password === '')
        {
            return;
        }

        $this->_user = $mobile;
        $this->_password = $password;

        $this->_login();
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        //$this->_logout();
    }

    /**
     * 登录
     * @return string
     */
    protected function _login()
    {

        $uri = '/login.php';

        $param = array(
            'pwuser'=> $this->_user,
            'pwpwd'=> $this->_password,
            'forward'=> urlencode($this->_host . '/post.php?'),
            'jumpurl'=> urlencode($this->_host . '/post.php?'),
            'jumpurl'=> '',
            'step'=> '2',
            'cktime'=> 31536000,
        );

        $key = array();
        foreach ((array)$param as $field => $value) {
            $key[] = $field . '=' . $value;
        }
        $data = implode('&', $key);

        $result = $this->_postWithCookie($uri, $data);

        //解析Cookie
        preg_match_all('/.*?\r\nSet-Cookie: (.*?);.*?/si', $result, $matches);
        if (isset($matches[1]))
        {
            $this->_cookie = implode('; ', $matches[1]);
        }

        //$result = $this->_postWithCookie('/im/login/cklogin.action', '');

        return true;
    }

    public function send($message = '1024')
    {
        $tid = $this->getTid();

        if ($message === '')
        {
            return '';
        }

        $param = array(
            'atc_usesign'=> '1',
            'atc_convert'=> '1',
            'atc_autourl'=> '1',
            'atc_title'=> '1024',
            'atc_content'=> $message,
            'step'=> '2',
            'action'=> 'reply',
            'fid'=> '7',
            'tid'=> $tid,
            'atc_attachment'=> 'none',
            'verify'=> 'verify'
        );

        $key = array();
        foreach ((array)$param as $field => $value) {
            $key[] = $field . '=' . $value;
        }
        $data = implode('&', $key);

        $uri = '/post.php';
        $result = $this->_postWithCookie($uri, $data);

        echo "<pre>";
        print_r($result);
        echo "<pre>";
    }

    protected function getTid(){

        $url = "http://cl.man.lv/thread0806.php?fid=7";
        $get_url = $this->curl_get($url);

        preg_match_all('/<a href="htm_data(.*?)"/', $get_url, $matches);

        $url = $matches[0][8];

        $url = substr($url, 9);
        $url = rtrim($url, '"');//htm_data/7/1309/957717.html

        $urls = explode('/', $url);

        $urls = explode('.', $urls[3]);
        $urls = $urls[0];

        return $urls;
    }

    function curl_get($url,$ua=false,$post_data=''){
        global $cookie;
        $ch=curl_init($url);
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
        curl_setopt($ch,CURLOPT_COOKIE,$cookie);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * 携带Cookie发送POST请求
     * @param string $uri
     * @param string $data
     */
    protected function _postWithCookie($uri, $data)
    {
        $fp = fsockopen($this->_host, 80);
        fputs($fp, "POST $uri HTTP/1.1\r\n");
        fputs($fp, "Host: {$this->_host}\r\n");
        fputs($fp, "Cookie: {$this->_cookie}\r\n");
        fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1\r\n");
        fputs($fp, "Content-Length: ".strlen($data)."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);

        $result = '';
        while (!feof($fp))
        {
            $result .= fgets($fp);
        }

        fclose($fp);

        return $result;
    }

}

//----------------------------------------------------------
$fetion = new Reply('user', 'xxxxxxxxx');

$fetion->send('1024');