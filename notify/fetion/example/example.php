<?php
require '../lib/PHPFetion.php';

$fetion = new PHPFetion('158xxxx7803', 'xxxx');	// 手机号、飞信密码
$fetion->send('xxxx', 'Hello!');	// 接收人手机号、飞信内容

