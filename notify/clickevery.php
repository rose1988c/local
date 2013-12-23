<?php
$GLOBALS['cookie'] = '';

$url = 'http://cl.cn.mu/index.php?u=225503&ext=b005f';

curl_get($url, true);

function curl_get($url, $ua = false, $post_data=''){
    global $cookie;

    $ch = curl_init($url);

    if ($ua){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent:Mozilla/5.0 (Linux; U; Android 2.3.4; zh-cn; W806 Build/GRJ22) AppleWebKit/530.17 (KHTML, like Gecko) FlyFlow/2.4 Version/4.0 Mobile Safari/530.17 baidubrowser/042_1.8.4.2_diordna_008_084/AIDIVN_01_4.3.2_608W/1000591a/9B673AC85965A58761CF435A48076629%7C880249110567268/1',
            'X-FORWARDED-FOR:8.8.8.8',
            'CLIENT-IP:8.8.8.8'
        ));
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0','Connection:keep-alive','Referer:http://wapp.baidu.com/'));
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    if (is_array($post_data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    curl_setopt($ch, CURLOPT_COOKIE, $cookie);

    //启用时会将头文件的信息作为数据流输出
    curl_setopt($ch,CURLOPT_HEADER, 1);

    $content = curl_exec($ch);

    //解析Cookie - 需要开启头信息
    preg_match_all('/.*?\r\nSet-Cookie: (.*?);.*?/si', $content, $matches);
    if (isset($matches[1]) && $cookie == '')
    {
        $cookie = implode('; ', $matches[1]);
    }
    curl_close($ch);
    
    echo "<pre>";
    print_r($content);
    echo "<pre>";
    die();

    return $content;
}