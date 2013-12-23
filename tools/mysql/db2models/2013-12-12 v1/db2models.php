<?php
require_once('../lib/bootstrap.inc.php');
function table2model($table_name, $fields) {
    $schema = array();
    
    $sharding_clue  = null;
    $isolate_column = null;

    foreach ($fields as $field) {
        $name    = $field['Field'];
        $type    = model_type($field);
        $pri     = $field['Key'] == 'PRI';
        $auto    = $field['Extra'] == 'auto_increment';
        $null    = $field['Null'] == 'YES';
        $default = model_default($type, $field['Default']);
        $global_auto = false;

        try {
            $meta = json_decode($field['Comment'], true);
            if (!is_null($meta) && is_array($meta)) {
                if (isset($meta['type']))
                    $type = $meta['type'];
                if (isset($meta['sharding_clue'])) {
                    $sharding_clue = $name;
                    $pri = true;
                }
                if (isset($meta['isolate_column']))
                    $isolate_column = $name;
                if (isset($meta['global_auto_increment']))
                    $global_auto = true;
            }
        } catch (Exception $e) {}

        $d = array();
        $d['type'] = $type;
        if ($pri)
            $d['primary'] = true;
        if ($auto)
            $d['auto_increment'] = true;
        if ($global_auto)
            $d['global_auto_increment'] = true;
        if ($null)
            $d['null'] = true;
        if ($default)
            $d['default'] = $default;

        $schema[$name] = $d;
    }

    return array('schema' => $schema, 
        'sharding_clue'  => $sharding_clue, 
        'isolate_column' => $isolate_column);
}

function model_default($type, $value) {
    if ($value == 'NULL') return false;

    switch ($type) {
        case 'int': return intval($value);
        case 'float': return floatval($value);
        case 'decimal':
        case 'double': return doubleval($value);
        default:
            return $value;
    }
}

function model_type($field) {
    $mysql_type = $field['Type'];
    @list($t, $l) = explode('(', strtolower($mysql_type), 2);
	$l = intval($l);
    switch ($t) {
        case 'int':
        case 'tinyint':
        case 'smallint':
        case 'mediumint':
        case 'bigint':
			if ($l < 11)
				return 'integer';
			else
				return 'long';
        case 'float':
            return 'float';
        case 'double':
            return 'double';
        case 'decimal':
            return 'decimal';
        case 'varchar':
        case 'char':
        case 'text':
        case 'enum':
            return 'string';
        case 'date':
            return 'date';
        case 'datetime':
        case 'timestamp':
            return 'datetime';
        default:
            return $t;
    }
}

function db2models($host, $port, $username, $password, $dbname, $prefix) {
    $db = new Database($host, $port, $dbname, $username, $password);

    $rows = $db->get_array('SHOW TABLES');

    foreach ($rows as $row) {
        $table_name = $row[0];
        $fields = $db->get_rows("SHOW FULL FIELDS FROM `{$row[0]}`");
        $model = table2model($table_name, $fields);

        $object_name = guess_object_name($table_name, $prefix);

        echo "<ul>\n";
        print_model($table_name, $object_name, $model);
        echo "\n</ul>\n";
    }
}

/**
 * 表名前缀控制
 *
 */
function guess_object_name($table_name, $prefix) {
    if ($prefix && strpos($table_name, $prefix) === 0) {
        $table_name = substr($table_name, strlen($prefix));
    }
    $table_name = ucwords(str_replace('_', ' ', $table_name));
    return str_replace(' ', '', $table_name);
}

function print_model($table_name, $object_name, $model) {/*{{{*/
    $sharding_clue  = $model['sharding_clue'];
    $isolate_column = $model['isolate_column'];
    $model = $model['schema'];

    $table_type = $sharding_clue ? 'ShardedDBTable' : 'DBTable';

    if ($sharding_clue)
        $sharding_clue = " '$sharding_clue',";
    if ($isolate_column)
        $isolate_column = ", '$isolate_column'";

    echo "<li><a href=\"#\" onclick=\"document.getElementById('def_$table_name').style.display = '';return false;\">$table_name</a>\n";
    echo "<div id=\"def_$table_name\" style=\"display:none;\"><textarea rows=\"15\" cols=\"100\">";
    echo "\$GLOBALS['$object_name'] = new $table_type('$object_name', '$table_name',$sharding_clue array(\n";

    $flen = 0;
    foreach ($model as $field_name => $field) {
        if (strlen($field_name) > $flen)
            $flen = strlen($field_name);
    }

    foreach ($model as $field_name => $field) {
        $fn = str_pad("'$field_name'", $flen + 2);
        echo "        $fn => array(";
        $keys  = array_keys($field);
        $count = count($keys);
        for ($i = 0; $i < $count; $i++) {
            $k = $keys[$i];
            $v = $field[$k];
            if (is_string($v))
                echo "'$k' => '$v'";
            else if (is_bool($v))
                echo "'$k' => " . ($v ? 'true' : 'false');
            else
                echo "'$k' => $v";
            if ($i < $count - 1)
                echo ", ";
        }
        echo "),\n";
    }

    echo "    )$isolate_column);";
    echo "</textarea></div></li>";
}/*}}}*/


/*
 * mcc - functions.php
 *
 * Created on 2012-8-21 下午05:12:45
 * Created by cyw
 *
 */
function db2functions($host, $port, $username, $password, $dbname, $prefix, $table) {
    $db = new Database($host, $port, $dbname, $username, $password);

    $table_name = $table;
    $fields = $db->get_rows("SHOW FULL FIELDS FROM `{$table_name}`");
    
    $model = table2model($table_name, $fields);
    
    $object_name = guess_object_name($table_name, $prefix);

    echo "<ul>\n";
    print_model($table_name, $object_name, $model);
    echo "\n</ul>\n";
    
    echo "<ul>\n";
    print_functions($table_name, $object_name, $model, $prefix);
    echo "\n</ul>\n";
   
}

/**
 * 增删改查
 *
 * cyw
 * 2012-8-21 下午02:47:52
 * @param unknown_type $table_name
 * @param unknown_type $object_name
 * @param unknown_type $model
 */
function print_functions($table_name, $object_name, $model, $prefix){
    
    $sharding_clue  = $model['sharding_clue'];
    $isolate_column = $model['isolate_column'];
    $model = $model['schema'];

    $table_type = $sharding_clue ? 'ShardedDBTable' : 'DBTable';

    if ($sharding_clue)
        $sharding_clue = " '$sharding_clue',";
    if ($isolate_column)
        $isolate_column = ", '$isolate_column'";
        
    $col_pri_key = array();
    $col_datetime = array();
    $col_notnull = array();
    
    foreach ($model as $field_name => $field) {
        
        if (isset($field['primary']) && $field['primary'] == 1) {
            $col_pri_key [] = $field_name;
        }
        
        if (isset($field['type']) && $field['type'] == 'datetime') {
            $col_datetime [] = $field_name;
        }
        
        if (isset($field['type']) && $field['type'] != 'datetime' && !isset($field['null'])) {
            $col_notnull [] = $field_name;
        }
        
    }

    echo "<li><a href=\"#\" onclick=\"document.getElementById('defc_$table_name').style.display = '';return false;\">$table_name functions</a>\n";
    echo "<div id=\"defc_$table_name\" style=\"display:none;\"><textarea rows=\"15\" cols=\"100\">";
    
    $fuctable_name = str_replace("mcc_","",$table_name);
    
    if (empty($prefix)) {
        $object_name = substr($object_name, 3);
    }
    
    //注释{{{
    echo '/*' . str_pad($object_name, 50,"*",STR_PAD_BOTH) . '*/' . "\n";
    //增
    echo 'function mcc_create_' . $fuctable_name . ' ($data) {'. "\n";
    echo '    global $' . $object_name . ';' . "\n\n";
    if (count($col_datetime) > 0 ) {
        $flen = 0;
        foreach ($col_datetime as $field) {
            if (strlen($field) > $flen)
                $flen = strlen($field);
        }
        echo '    $query = array (' . "\n";
        $dtcount = count($col_datetime);
        foreach ($col_datetime as $key => $datetime) {
            $str = "\n";
            if ($dtcount -1 != $key) {
                $str = ",\n";
            }
            echo '        ' .str_pad("'$datetime'", $flen + 2) . ' => time()' . $str;
        }
        echo '    );' . "\n";
        echo '    $data = array_merge($query, $data);'. "\n\n";
    }
    echo '    $' . $fuctable_name . ' = ' . '$' . $object_name . '->new_object($data);' . "\n\n";
    echo '    $' . $fuctable_name . '->insert();'. "\n\n";
    echo '    return ' . '$' . $fuctable_name . ';' . "\n";
    echo '}' . "\n\n";
    //load
    $str = '';
    $dtcount = count($col_pri_key);
    foreach ($col_pri_key as $key => $field) {
        if ($dtcount -1 != $key) {
            $str .= "$$field" . ", ";
        } else {
            $str .= "$$field";
        }
    }
    echo 'function mcc_load_' . $fuctable_name . ' (' . $str . ') {'. "\n";
    echo '    global $' . $object_name . ';' . "\n\n";
    echo '    $query = array('. "\n";
    $flen = 0;
    foreach ($col_pri_key as $field) {
        if (strlen($field) > $flen)
            $flen = strlen($field);
    }
    foreach ($col_pri_key as $key => $col_pri) {
        $str = "\n";
        if ($dtcount -1 != $key) {
            $str = ",\n";
        }
        echo '        ' .str_pad("'$col_pri'", $flen + 2) . ' => $' . "$col_pri" . $str;
    }
    echo '    );' . "\n\n";
    
    echo '    return $' . $object_name . '->load($query);'. "\n\n";
    
    echo '}' . "\n\n";
    //fetch_one
    echo 'function mcc_get_' . $fuctable_name . ' ($criteria = false) {'. "\n";
    echo '    global $' . $object_name . ';' . "\n\n";
    if (isset($col_datetime[0])){
        echo "    \$query = array('order_by' => 'created_at desc');". "\n\n";
    } else {
         echo '    $query = array(); ' . "\n\n";
    }
    echo '    if ($criteria != false) {'. "\n";
    echo '        $query = array_merge($query, $criteria);'. "\n";
    echo '    }' . "\n\n";
	echo '    return ' . '$' . $object_name . '->fetch_one($query);' . "\n";
    echo '}' . "\n\n";
    //fetch
    echo 'function mcc_get_' . $fuctable_name . '_lists ($criteria = false, $page = false, $per_page = false) {'. "\n";
    echo '    global $' . $object_name . ';' . "\n\n";
    if (isset($col_datetime[0])){
        echo "    \$query = array('order_by' => 'created_at desc', 'page' => \$page, 'per_page' => \$per_page );". "\n\n";
    } else {
        echo "    \$query = array('page' => \$page, 'per_page' => \$per_page ); " . "\n\n";
    }       
    echo '    if ($criteria != false) {'. "\n";
    echo '        $query = array_merge($query, $criteria);'. "\n";
    echo '    }' . "\n\n";
	echo '    return ' . '$' . $object_name . '->fetch($query);' . "\n";
    echo '}' . "\n\n";
    //delete
    $str = '';
    $dtcount = count($col_pri_key);
    foreach ($col_pri_key as $key => $field) {
        if ($dtcount -1 != $key) {
            $str .= "$$field" . ", ";
        } else {
            $str .= "$$field";
        }
    }
    echo 'function mcc_delete_' . $fuctable_name . ' (' . $str . ') {'. "\n";
    echo "    \$$fuctable_name = mcc_load_$fuctable_name($str);". "\n\n";
    echo "    if (is_object(\$$fuctable_name)) {\n";
    echo "        $$fuctable_name" . "->delete();\n";
    echo "    }\n\n";
    echo "    return \$$fuctable_name;\n";
    echo '}' . "\n\n";    
    //update
    $str = '';
    $dtcount = count($col_pri_key);
    foreach ($col_pri_key as $key => $field) {
        if ($dtcount -1 != $key) {
            $str .= "$$field" . ", ";
        } else {
            $str .= "$$field";
        }
    }
    echo 'function mcc_update_' . $fuctable_name . ' (' . $str . ', $data) {'. "\n";
    echo "    \$$fuctable_name = mcc_load_$fuctable_name($str);". "\n\n";
    echo "    if (is_object(\$$fuctable_name) && is_array(\$data)) {\n";
    
    echo "        foreach (\$data as \$key => \$value) {\n";
    echo "            \$$fuctable_name" . "->{\$key} = \$value;\n";
    echo "        }\n";
    if (in_array('updated_at', $col_datetime)){
        echo "        \$$fuctable_name" . "->updated_at = time();\n";
    }
    echo "        \$$fuctable_name" . "->update();\n";
    echo "    }\n\n";
    
    echo "    return \$$fuctable_name;\n";
    
    echo "}\n\n";
    
    //选项
    $incr     = param_int($_POST, 'incr', false, 0);
    $count    = param_int($_POST, 'count', false, 0);
    if($incr == 1) {
        $str = '';
        $flen = 0;
        
        $dtcount = count($col_pri_key);
        foreach ($col_pri_key as $key => $field) {
            if ($dtcount -1 != $key) {
                $str .= "$$field" . ", ";
            } else {
                $str .= "$$field";
            }
            
            if (strlen($field) > $flen)
            $flen = strlen($field);
        }
        
        $insertinto = "\n" . '            array(' . "\n";
        $dtcount = count($col_notnull);
        foreach ($col_notnull as $key => $field) {
            $quote = '';
            if ($dtcount -1 != $key) {
                $quote = ',';
            } else {
                $quote = ',';
            }
            //echo '        ' .str_pad("'$field'", $flen + 2) . ' =>
            if (!in_array($field, $col_pri_key)){
                $insertinto .= '                ' .str_pad("'$field'", $flen + 2) . ' => 0' . $quote . '/*please check you code, this fileld is not null but not value to it!*/' . "\n"; 
            } else {
                $insertinto .= '                ' .str_pad("'$field'", $flen + 2) . ' => $' . $field . $quote . "\n"; 
            }
        }
        $insertinto .=  '                ' .str_pad('$field', $flen + 2) . ' => $num '. "\n" .'            )'. "\n";
        
        echo 'function mcc_incr_' . $fuctable_name . ' (' . $str . ', $num = 1, $field) {'. "\n";
        echo "    \$$fuctable_name = mcc_load_$fuctable_name($str);". "\n\n";
        echo "    if(!is_object(\$$fuctable_name)) {\n";
        echo "        \$$fuctable_name = mcc_create_$fuctable_name({$insertinto}        );\n";
        echo "    } else {\n";
        echo "        if(isset(\$$fuctable_name->{\$field})){\n";
        echo "            \$num = \$$fuctable_name->{\$field} + \$num;\n";
        echo "            \$$fuctable_name = mcc_update_$fuctable_name($str, array( \$field => \$num));\n";
        echo "        };\n";
        echo "    }\n\n";
        echo "    return \$$fuctable_name;\n";
        echo "}\n\n";   
    }
    if($count == 1) {
        
        $has_key_user_id = false;
        foreach ($col_pri_key as $value) {
            if ($value == 'user_id'){
                $has_key_user_id = true;
            }
        }
        if ($has_key_user_id) {
            echo 'function mcc_get_count_' . $fuctable_name . ' ($user, $criteria = false) {'. "\n";
            echo '    global $' . $object_name . ';' . "\n\n";
            echo '    $user_id = is_object($user) ? $user->user_id : $user;' . "\n";
            echo '    $query = array('. "\n";
            echo "        'user_id'	=>	\$user_id". "\n";
            echo "    );". "\n";
            
            echo '    if ($criteria != false) {'. "\n";
            echo '        $query = array_merge($query, $criteria);'. "\n";
            echo '    }' . "\n\n";
            
            echo '    return $' . $object_name . '->count($query);'. "\n\n";
            echo "}\n\n";       
        } else {
            echo 'function mcc_get_count_' . $fuctable_name . ' ($criteria = false) {'. "\n";
            echo '    global $' . $object_name . ';' . "\n\n";
            
            echo '    return $' . $object_name . '->count($criteria);'. "\n\n";
            echo "}\n\n";     
        }
    }
    
    //}}}
    echo "</textarea></div></li>";
}

//cyw


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host     = param_string($_POST, 'host', false, 'localhost');
    $port     = param_int   ($_POST, 'port', false, 3306);
    $db       = param_string($_POST, 'db', true);
    $username = param_string($_POST, 'username', false, 'root');
    $password = param_string($_POST, 'password', true);
    $prefix   = param_string($_POST, 'prefix', false, '');
    $table    = param_string($_POST, 'table', false, '');

    if (!empty($table)){
        db2functions($host, $port, $username, $password, $db, $prefix, $table);
    } else {
        db2models($host, $port, $username, $password, $db, $prefix);
    }
?>

<?php
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Db2models</title>
    <style type="text/css" media="screen">
        li { list-style: none; width: 500px; margin-bottom: 15px; }
        label { display: block; width: 100px; display: block; float: left; }
    </style>
</head>

<body>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">                        
        <ul>
            <li><label>Host: </label><input type="text" name="host" value="localhost" /></li>
            <li><label>Port: </label><input type="text" name="port" value="3306" /></li>
            <li><label>DB: </label><input type="text" name="db" value="mcc_cluster" /></li>
            <li><label>Username: </label><input type="text" name="username" value="root" /></li>
            <li><label>Password: </label><input type="text" name="password" value="123123" /></li>
            <li><label>Table: </label><input type="text" name="table" value="" /><input type="checkbox" value="1" name="incr" />incr&nbsp;<input value="1" type="checkbox" name="count" />count</li>
            <li><label>Table Prefix: </label><input type="text" name="prefix" value="" />(for object name guessing)</li>
            <li><label>&nbsp;</label><input type="submit" name="submit" value="CREATE MODELS" /></li>
        </ul>
    </form>
</body>
</html>

<?php
}
?>
