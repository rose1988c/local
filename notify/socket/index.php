<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <form action="" method="get">
        <input type="text" name="ip" value="<?php echo isset($_GET['ip']) ? $_GET['ip'] : '';?>" placeholder="ip地址" />
        <input type="text" name="port" value="<?php echo isset($_GET['port']) ? $_GET['port'] : '';?>" placeholder='端口' />

        
        <input type="text" name="msg" value="<?php echo isset($_GET['msg']) ? $_GET['msg'] : '';?>" placeholder="发送内容" />

        <input type="text" name="sendMsgId" value="<?php echo isset($_GET['sendMsgId']) ? $_GET['sendMsgId'] : '';?>" placeholder="sendMsgId" />
        
        
		<input type="radio" class="radio ml10 dv" id="sex1" name="type" value="1" <?php echo isset($_GET['type']) && $_GET['type'] == 1 ? ' checked=""' : '';?>>
		<label for="sex1" class="dv">发送</label>
		
		
		<input type="radio" class="radio dv" id="sex2" name="type" value="2" <?php echo isset($_GET['type']) && $_GET['type'] == 2 ? ' checked=""' : '';?>>
		<label for="sex2" class="dv">获取次数</label>
		
        <input type="submit" value="提交" />
    </form>
    
    
</body>
</html>

<?php
require 'class/BigEndianBytesBuffer.php';
require 'class/BigEndianSocketBuffer.php';

if(isset($_GET['ip'])){
    $ip = $_GET['ip'];
    $port = $_GET['port'];
    $msg = $_GET['msg'];
    $type = $_GET['type'];
    $sendMsgId = $_GET['sendMsgId'];
    
    $bigSocket = new BigEndianSocketBuffer($ip, $port);
    
    if ($type == 1) {
        // 发送数据
        $bigSocket->writeShort( 0xAAEE );//包标识
        $bigSocket->writeShort( 89 );//包长度(不包括此包头)
        $bigSocket->writeShort( (89^0xBBCC) & 0x88AA );//校验位
        $bigSocket->writeShort( 0 );//标志位
        $bigSocket->writeBytes( 0xAAEE );
        
        if($sendMsgId == 1001)
        {
            $bigSocket->writeInt(0);
        }
        $bigSocket->writeShort( $sendMsgId );				//messageID
        $bigSocket->writeBytes(byte, 0, byte.length);		//字符串，json结构
        
    } else {
        $bigSocket->readChar();
    }
    
}
