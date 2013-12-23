<?php
include '../../db2models/2013-12-12 v1/database.php';
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
function db2functions($host, $port, $username, $password, $dbname, $prefix, $table, $funcattr) {
    $db = new Database($host, $port, $dbname, $username, $password);

    $table_name = $table;
    $fields = $db->get_rows("SHOW FULL FIELDS FROM `{$table_name}`");
    
    $model = table2model($table_name, $fields);
    
    $object_name = guess_object_name($table_name, $prefix);

    echo "<ul>\n";
    print_model($table_name, $object_name, $model);
    echo "\n</ul>\n";
    
    echo "<ul>\n";
    print_class($table_name, $object_name, $model, $prefix);
    echo "\n</ul>\n";
        
    echo "<ul>\n";
    print_functions($table_name, $object_name, $model, $prefix, $funcattr);
    echo "\n</ul>\n";
   
}

/**
 * rose1988.c@gmail.com
 * 
 * @param unknown $table_name
 * @param unknown $object_name
 * @param unknown $model
 * @param unknown $prefix
 * 2013-9-24 13:37:24
 */
function print_functions($table_name, $object_name, $model, $prefix, $funcattr = 'private static '){
    
    $prefix_func = 'bf_';
    
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

    echo "<li><a href=\"#\" onclick=\"document.getElementById('defc2_$table_name').style.display = '';return false;\">$table_name functions - rose1988.c@gmail.com</a>\n";
    echo "<div id=\"defc2_$table_name\" style=\"display:none;\"><textarea rows=\"15\" cols=\"100\">";
    
    $fuctable_name = str_replace($prefix, "", $table_name);
    
    if (empty($prefix)) {
        $object_name = substr($object_name, 3);
    }
    
    //注释{{{
    echo '/* ' . str_pad(' Table:' . $object_name . ' ', 50,"-",STR_PAD_BOTH) . ' */' . "\n";
    
    $timestamp = date('Y-m-d H:i:s');
    
    //增
    echo $funcattr . 'function '. $prefix_func . 'create_' . $fuctable_name . ' ($data) {'. "\n";
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
            echo '        ' .str_pad("'$datetime'", $flen + 2) . ' => date("Y-m-d H:i:s")' . $str;
        }
        echo '    );' . "\n";
        echo '    $data = array_merge($query, $data);'. "\n\n";
    }
    echo '    return self::$db_' . $fuctable_name . '->insert' . "\n";
    echo '        ->value($data)' . "\n";
    echo '        ->exec();' . "\n";
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
    echo $funcattr . 'function '. $prefix_func . 'load_' . $fuctable_name . ' (' . $str . ') {'. "\n";
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
    
    echo '    $key = array();' . "\n";
    
    echo '    foreach ((array)$query as $field => $value) {'. "\n";
    echo '        if ($value !== false) {' . "\n";
    echo '            $key[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
    echo '        } else {' . "\n";
    echo '            unset($param[$field]);' . "\n";
    echo '        }' . "\n";
    echo '    }' . "\n";
    
    echo '    $key = implode(" AND ", $key);'. "\n";
    
    echo '    return self::$db_' . $fuctable_name . '->select' . "\n";
    echo '        ->where($key)' . "\n";
    echo '        ->param($query)' . "\n";
    echo '        ->get_one();' . "\n";
    
    echo '}' . "\n\n";
    //fetch_one
    echo $funcattr . 'function '. $prefix_func . 'get_' . $fuctable_name . ' ($criteria = false) {'. "\n";
    if (isset($col_datetime[0])){
        echo "    \$query = array('order_by' => 'created_at desc');". "\n\n";
    } else {
         echo '    $query = array(); ' . "\n\n";
    }
    echo '    if ($criteria != false) {'. "\n";
    echo '        $query = array_merge($query, $criteria);'. "\n";
    echo '    }' . "\n\n";
    
    echo '    $order = false;'. "\n";
    echo '    if (isset($query["order_by"])){'. "\n";
    echo '        $order = $query["order_by"];'. "\n";
    echo '        unset($query["order_by"]);'. "\n";
    echo '    }'. "\n";
    
    echo '    $key = array();' . "\n";
    echo '    foreach ((array)$query as $field => $value) {'. "\n";
    echo '        if ($value !== false) {' . "\n";
    echo '            $key[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
    echo '        } else {' . "\n";
    echo '            unset($param[$field]);' . "\n";
    echo '        }' . "\n";
    echo '    }' . "\n";
    
    echo '    $key = implode(" AND ", $key);'. "\n";
    
    echo '    return self::$db_' . $fuctable_name . '->select' . "\n";
    echo '        ->where($key)' . "\n";
    echo '        ->param($query)' . "\n";
    echo '        ->order("$order")'. "\n";
    echo '        ->get_one();' . "\n";
    echo '}' . "\n\n";
    //fetch
    echo $funcattr . 'function '. $prefix_func . 'get_' . $fuctable_name . '_lists ($criteria = false, $page = 1, $per_page = 10) {'. "\n";
    if (isset($col_datetime[0])){
        echo "    \$query = array('order_by' => 'created_at desc');". "\n\n";
    } else {
        echo "    \$query = array(); " . "\n\n";
    }       
    echo '    if ($criteria != false) {'. "\n";
    echo '        $query = array_merge($query, $criteria);'. "\n";
    echo '    }' . "\n\n";

    echo '    $order = false;'. "\n";
    echo '    if (isset($query["order_by"])){'. "\n";
    echo '        $order = $query["order_by"];'. "\n";
    echo '        unset($query["order_by"]);'. "\n";
    echo '    }'. "\n";
    
    echo '    $key = array();' . "\n";
    echo '    foreach ((array)$query as $field => $value) {'. "\n";
    echo '        if ($value !== false) {' . "\n";
    echo '            $key[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
    echo '        } else {' . "\n";
    echo '            unset($param[$field]);' . "\n";
    echo '        }' . "\n";
    echo '    }' . "\n";
    
    echo '    $key = implode(" AND ", $key);'. "\n";
    
    echo '    return self::$db_' . $fuctable_name . '->select' . "\n";
    echo '        ->where($key)' . "\n";
    echo '        ->param($query)' . "\n";
    echo '        ->order("$order")'. "\n";
    echo '        ->limit(($page - 1) * $per_page, ($page - 1) * $per_page + $per_page)' . "\n";
    echo '        ->exec();' . "\n";
    
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
    echo $funcattr . 'function '. $prefix_func . 'delete_' . $fuctable_name . ' (' . $str . ') {'. "\n";
    
    echo '    $query = array('. "\n";
    $flen = 0;
    foreach ($col_pri_key as $field) {
        if (strlen($field) > $flen)
            $flen = strlen($field);
    }
    foreach ($col_pri_key as $key => $col_pri) {
        $str2 = "\n";
        if ($dtcount -1 != $key) {
            $str2 = ",\n";
        }
        echo '        ' .str_pad("'$col_pri'", $flen + 2) . ' => $' . "$col_pri" . $str2;
    }
    echo '    );' . "\n\n";
    
    echo '    $key = array();' . "\n";
    echo '    foreach ((array)$query as $field => $value) {'. "\n";
    echo '        if ($value !== false) {' . "\n";
    echo '            $key[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
    echo '        } else {' . "\n";
    echo '            unset($param[$field]);' . "\n";
    echo '        }' . "\n";
    echo '    }' . "\n";
    
    echo '    $key = implode(" AND ", $key);'. "\n";
    
    echo "    \$$fuctable_name = self::" . $prefix_func. "load_$fuctable_name(". $str .");". "\n\n";
    echo "    if (!empty(\$$fuctable_name)) {\n";
    echo '    return self::$db_' . $fuctable_name . '->delete' . "\n";
    echo '        ->where($key)' . "\n";
    echo '        ->param($query)' . "\n";
    echo '        ->exec();' . "\n";
    echo "    }\n\n";
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
    echo $funcattr . 'function '. $prefix_func . 'update_' . $fuctable_name . ' (' . $str . ', $data) {'. "\n";
    
    echo '    $query = array('. "\n";
    $flen = 0;
    foreach ($col_pri_key as $field) {
        if (strlen($field) > $flen)
            $flen = strlen($field);
    }
    foreach ($col_pri_key as $key => $col_pri) {
        $str2 = "\n";
        if ($dtcount -1 != $key) {
            $str2 = ",\n";
        }
        echo '        ' .str_pad("'$col_pri'", $flen + 2) . ' => $' . "$col_pri" . $str2;
    }
    echo '    );' . "\n\n";
    
    echo '    $term = array();' . "\n";
    echo '    foreach ((array)$query as $field => $value) {'. "\n";
    echo '        if ($value !== false) {' . "\n";
    echo '            $term[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
    echo '        } else {' . "\n";
    echo '            unset($param[$field]);' . "\n";
    echo '        }' . "\n";
    echo '    }' . "\n";
    
    echo '    $term = implode(" AND ", $term);'. "\n";
    
    
    echo "    \$$fuctable_name = self::" . $prefix_func. "load_$fuctable_name(". $str .");". "\n\n";
    echo "    if (!empty(\$$fuctable_name) && is_array(\$data)) {\n";
    
    echo "        foreach (\$data as \$key => \$value) {\n";
    
    echo "            if (isset(\$query[\$key])){\n";
    echo "                unset(\$data[\$key]);\n";
    echo "            }\n";
    
    echo "        }\n";
    if (in_array('updated_at', $col_datetime)){
        echo "        \$data" . "['updated_at'] = date('Y-m-d H:i:s');\n";
        //echo "        \$key .= ' AND `updated_at` = :updated_at ';\n";
    }
    echo '        return self::$db_' . $fuctable_name . '->update' . "\n";
    echo '            ->set($data)' . "\n";
    echo '            ->param($query)' . "\n";
    echo '            ->where($term)' . "\n";
    echo '            ->exec();' . "\n";
    
    echo "        }\n\n";
    
    echo "    return \$$fuctable_name;\n";
    
    echo "}\n\n";
    
    //选项
    $incr     = isset($_POST['incr']) ? $_POST['incr'] : 0;
    $count    = isset($_POST['count']) ? $_POST['count'] : 0;
    
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
        
        echo $funcattr . 'function '. $prefix_func . 'incr_' . $fuctable_name . ' (' . $str . ', $num = 1, $field) {'. "\n";
        
        //计算主键
        echo '    $query = array('. "\n";
        $flen = 0;
        foreach ($col_pri_key as $field) {
            if (strlen($field) > $flen)
                $flen = strlen($field);
        }
        foreach ($col_pri_key as $key => $col_pri) {
            $str2 = "\n";
            if ($dtcount -1 != $key) {
                $str2 = ",\n";
            }
            echo '        ' .str_pad("'$col_pri'", $flen + 2) . ' => $' . "$col_pri" . $str2;
        }
        echo '    );' . "\n\n";
        
        echo '    $key = array();' . "\n";
        echo '    foreach ((array)$query as $row => $value) {'. "\n";
        echo '        if ($value !== false) {' . "\n";
        echo '            $key[] ="' . '`' . '$row' . '`' . ' = :' . '$row";' . "\n";
        echo '        } else {' . "\n";
        echo '            unset($param[$row]);' . "\n";
        echo '        }' . "\n";
        echo '    }' . "\n";
        
        echo '    $key = implode(" AND ", $key);'. "\n";
        //计算主键end
        
        echo "    \$$fuctable_name = self::" . $prefix_func. "load_$fuctable_name(". $str .");". "\n\n";
        echo "    if(empty(\$$fuctable_name)) {\n";
        echo "        \$$fuctable_name = self::" . $prefix_func. "create_$fuctable_name({$insertinto}        );\n";
        echo "    } else {\n";
        echo "        if(isset(\$$fuctable_name" . '[' . "\$field" . ']'. ")){\n";
        echo "            \$num = \$$fuctable_name" . '[' . "\$field" . ']'. " + \$num;\n";
        echo "            \$$fuctable_name = self::" . $prefix_func. "update_$fuctable_name($str, array( \$field => \$num));\n";
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
            echo $funcattr . 'function '. $prefix_func . 'get_count_' . $fuctable_name . ' ($user, $criteria = false) {'. "\n";
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
            echo $funcattr . 'function '. $prefix_func . 'get_count_' . $fuctable_name . ' ($criteria = false) {'. "\n";
            echo '    global $' . $object_name . ';' . "\n\n";
            
            echo '    $query = array('. "\n";
            echo "    );". "\n";
            
            echo '    if ($criteria != false) {'. "\n";
            echo '        $query = array_merge($query, $criteria);'. "\n";
            echo '    }' . "\n\n";
            
            echo '    $key = array();' . "\n";
            echo '    foreach ((array)$query as $field => $value) {'. "\n";
            echo '        if ($value !== false) {' . "\n";
            echo '            $key[] ="' . '`' . '$field' . '`' . ' = :' . '$field";' . "\n";
            echo '        } else {' . "\n";
            echo '            unset($param[$field]);' . "\n";
            echo '        }' . "\n";
            echo '    }' . "\n";
            
            echo '    $key = implode(" AND ", $key);'. "\n";
            
            echo '    return self::$db_' . $fuctable_name . '->select' . "\n";
            echo '        ->param($query)' . "\n";
            echo '        ->where($key)' . "\n";
            echo '        ->count();' . "\n";
            echo "}\n\n";     
        }
    }
    
    //}}}
    echo "</textarea></div></li>";
}

function print_class($table_name, $object_name, $model, $prefix)
{
    echo "<li><a href=\"#\" onclick=\"document.getElementById('defclass_$table_name').style.display = '';return false;\">$table_name Class</a>\n";
    echo "<div id=\"defclass_$table_name\" style=\"display:none;\"><textarea rows=\"15\" cols=\"100\">";
    
    if (empty($prefix)) {
        $object_name = substr($object_name, 3);
    }
    
    echo 'class ' . $object_name . "\n";
    echo '{'. "\n";
    echo '    private static $_instance = null;'. "\n";
    echo '    private static $areaid, $areatypeid, $db_' . strtolower($object_name) . ';'. "\n";
    
    echo '    public static function instance($areaid, $areatypeid)'. "\n";
    echo '    {'. "\n";
    
    echo '        if (self::$_instance == null)'. "\n";
    echo '        {'. "\n";
    echo '            self::$_instance = new self();'. "\n";
    echo '            self::$db_' . strtolower($object_name) . ' =  model::instance("' . strtolower($object_name) . '");'. "\n";
    echo '            self::$areaid = $areaid;'. "\n";
    echo '            self::$areatypeid = $areatypeid;'. "\n";
    echo '        }'. "\n";
    echo '        return self::$_instance;'. "\n";
    echo '    }'. "\n";
    
    echo '}'. "\n";
    
    echo "</textarea></div></li>";
}
//cyw


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host     = isset($_POST['host']) ? $_POST['host']: '';
    $port     = isset($_POST['port']) ? $_POST['port']:'';
    $db       = isset($_POST['db']) ? $_POST['db'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $prefix   = isset($_POST['prefix']) ?  $_POST['prefix']: '';
    $table    = isset($_POST['table']) ?  $_POST['table']: '';
    $funcattr = isset($_POST['funcattr']) ? $_POST['funcattr'] : '';

    if (!empty($table)){
        db2functions($host, $port, $username, $password, $db, $prefix, $table, $funcattr);
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
            <li><label>DB: </label><input type="text" name="db" value="" /></li>
            <li><label>Username: </label><input type="text" name="username" value="root" /></li>
            <li><label>Password: </label><input type="text" name="password" value="" /></li>
            <li><label>Table: </label><input type="text" name="table" value="" /><input type="checkbox" value="1" name="incr" />incr&nbsp;<input value="1" type="checkbox" name="count" />count</li>
            <li><label>Table Prefix: </label><input type="text" name="prefix" value="" />(for object name guessing)</li>
            <li><label>funcattr: </label><input type="text" name="funcattr" value="public static " /></li>
            <li><label>&nbsp;</label><input type="submit" name="submit" value="CREATE MODELS" /></li>
        </ul>
    </form>
</body>
</html>

<?php
}
?>
