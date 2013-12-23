<?php
$deviceToken = "设备令牌";
$body = array (
    "aps" => array (
        "alert" => 'message',
        "badge" => 1,
        "sound" => 'received5.caf' 
    ) 
);
$ctx = stream_context_create();
stream_context_set_option($ctx, "ssl", "local_cert", "ck.pem");

$fp = stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
if (! $fp) {
    print "Failed to connect $err $errstrn";
    return;
}
print "Connection OK/n";
$payload = json_encode($body);
$msg = chr . pack("n", 32) . pack("H*", $deviceToken) . pack("n", strlen($payload)) . $payload;
print "sending message :" . $payload . "/n";
fwrite($fp, $msg);
fclose($fp);