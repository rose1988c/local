<?php
    /**
     * file.php
     * 防盗链获取图片
     * 
     * @author: Cyw
     * @email: rose1988.c@gmail.com
     * @created: 2013-12-23 上午11:13:54
     * @logs: 
     *       
     */
    $picurl = stripcslashes($_REQUEST ["url"]);
    //$content = file_get_contents($picurl);
    
    $ch = curl_init();
    $timeout = 10;
    curl_setopt ($ch, CURLOPT_URL, $picurl);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.2) Gecko/20100115 Firefox/3.6 GTBDFff GTB7.0');
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $content = curl_exec($ch);
    curl_close($ch);
    
    header("Content-Type: image/jpeg; charset=UTF-8");
    echo $content;
?>