<?php

$table = isset($_GET['table']) ? $_GET['table'] : 'people';

$con = mysql_connect("localhost", "root", "") or die('Could not connect: ' . mysql_error());

$db_selected = mysql_select_db("test", $con) or die ("Can\'t use test_db : " . mysql_error());

mysql_close($con);

$rs = $db_selected->queryAll("SELECT column_name,column_type,column_comment,data_type  
FROM  information_schema.`COLUMNS` WHERE `TABLE_NAME` LIKE  '$table'");
$output = '';
foreach ($rs as $r) {
 // 下划线转驼峰
    $r['column_name'] = lcfirst(implode('', array_map('ucfirst', explode('_', $r['column_name']))));
    $output .=<<<EOF
\n
/**
 * {$r['column_comment']}
 * @var {$r['data_type']}
 */
public \${$r['column_name']}; \n               
EOF;
}
echo '<pre>' . $output . '</pre>';