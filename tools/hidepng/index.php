<?php
//from:http://www.codepearl.com
include 'Simple.Stegonography.class.php';


$a = new Stego();

//先把hello写到logo.png图片里面 保存生成的图片
header('Content-Type: image/png');
//imagepng($a -> stegoIt('hello','logo.png',1000));


//根据上一步生成的图片获取里面的内容。记得先注释掉上一步哦
echo $a -> unStegoIt('example.php.png',1000)

?>
