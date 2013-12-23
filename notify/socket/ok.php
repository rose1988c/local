<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <form action="" method="get">
        
        <input type="text" name="ip" value="<?php echo isset($_GET['ip']) ? $_GET['ip'] : '';?>" placeholder="ip地址" />

        <input type="text" name="port" value="<?php echo isset($_GET['port']) ? $_GET['port'] : '';?>" placeholder='端口' />
        
        <input type="text" name="account" value="" placeholder="账号" />

        <input type="text" name="awardid" value="" placeholder="奖品id" />

        <select name="type">
            <option value="1">获取次数</option>
            <option value="2">发送获奖信息</option>
        </select>

        <input type="submit" value="提交" />
    </form>
    
    
</body>
</html>

<?php
class Byte{
    //长度
    private $length=0;
    
    private $byte='';
    //操作码
    private $code;
    public function setBytePrev($content){
        $this->byte=$content.$this->byte;
    }
    public function getByte(){
        return $this->byte;
    }
    public function getLength(){
        return $this->length;
    }
    public function writeByte($str){
        $this->length+=1;
        $this->byte.=pack('c',$str);
    }
    public function writeChar($string){
        $this->length+=strlen($string);
        $str=array_map('ord',str_split($string));
        foreach($str as $vo){
            $this->byte.=pack('c',$vo);
        }
        //$this->byte.=pack('c','0');
        //$this->length++;
    }
    public function writeInt($str){
        $this->length+=4;
        $this->byte.=pack('L',$str);
    }
    public function writeShortInt($interge){
        $this->length+=2;
        $this->byte.=pack('v',$interge);
    }
}


class GameSocket{
    private $socket;
    private $port = 9991;
    private $host = '192.168.211.231';
    public $byte;
    private $code;
    const CODE_LENGTH=2;
    const FLAG_LENGTH=4;
    public function __set($name,$value){
        $this->$name=$value;
    }
    public function __construct($host='192.168.211.231',$port=9991){
        $this->host=$host;
        $this->port=$port;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(!$this->socket){
            exit('创建socket失败');
        }
        $result = socket_connect($this->socket,$this->host,$this->port);
        if(!$result){
            exit('连接不上目标主机'.$this->host);
        }
        $this->byte=new Byte();
    }
    public function write($data){
        if(is_string($data)||is_int($data)||is_float($data)){
            $data[]=$data;
        }
        if(is_array($data)){
            foreach($data as $vo){
                //$this->byte->writeShortInt(strlen($vo));
                $this->byte->writeChar($vo);
            }
        }
        //$this->setPrev();
        $this->send();
    }
    /*
     *设置表头部分
     *表头=length+code+flag
     *length是总长度(4字节)  code操作标志(2字节)  flag暂时无用(4字节)
     */
    private function getHeader(){
        $length=$this->byte->getLength();
        $length=intval($length)+self::CODE_LENGTH+self::FLAG_LENGTH;
        return pack('L',$length);
    }
    private function getCode(){
        return pack('v',$this->code);
    }
    private function getFlag(){
        return pack('L',24);
    }
    
    private function setPrev(){
        $this->byte->setBytePrev($this->getHeader().$this->getCode().$this->getFlag());
    }

    public function send(){
        $result=socket_write($this->socket,$this->byte->getByte());
        if(!$result){
            exit('发送信息失败');
        }
    }

    public function read() {
        //读取指定长度的数据
        if($buffer = socket_read($this->socket, 1024)) {  
            if($buffer == "NO DATA") {  
                printf("NO DATA");
            }else{  
                // 输出 buffer  
                printf("Buffer Data: " . $buffer . "");  
            }  
        }  
    }
        
    public function __desctruct(){
        socket_close($this->socket);
    }
}


$ip = $_GET['ip'];
$port = $_GET['port'];
$account = $_GET['account'];
$awardid = $_GET['awardid'];
$type = $_GET['type'];

if ($type == 1 ) {
    $msg_json = json_encode(array('cmd' => 'getcount' , 'account' => $account));
} else {
    $msg_json = json_encode(array('cmd' => 'additem' , 'account' => $account, 'awardid' => $awardid));
}

//'cmd' => 'awardcount' , 'count' => 3
//'cmd' => 'awardresult' , 'count' => 2 , 'state' => 1

//print_r($msg_json);die();

$len_msg = strlen($msg_json) + 2;

$flag = ($len_msg^0xBBCC) & 0x88AA;
$data[] = $msg_json;

$gameSocket=new GameSocket($ip, $port);
$gameSocket->code=11;
$gameSocket->byte->writeShortInt(0xAAEE);
$gameSocket->byte->writeShortInt($len_msg);
$gameSocket->byte->writeShortInt($flag);
$gameSocket->byte->writeByte(0);
$gameSocket->byte->writeShortInt(10085);
$gameSocket->byte->writeChar($msg_json);

$gameSocket->send();

$gameSocket->read();
?>