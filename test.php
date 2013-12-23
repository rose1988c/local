<?PHP
    $op_left = '123456789123456789';
    $op_right = '123456789123456789';
    echo(mt_rand($op_right,$op_left));
    $rs = bcmul($op_left, $op_right);
    var_dump($rs);
    die();