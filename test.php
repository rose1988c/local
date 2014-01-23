<?PHP

    require_once ('service_center/lib/LocalServiceCenter.class.php');
    
    
    $data = (array) LocalServiceCenter::instance('Prop.Info.Get')
    ->args(array(
        'areaid' => 1088,
        'numid' => 11625804,
        'propids' => 50,
    ))
    ->get_data();
    
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
    
    
    $data = (array) LocalServiceCenter::instance('Match.Sub.Get')
    ->args(array(
        'type' => 'NEWPT',
        'matchid' => 110,
    ))
    ->get_data();
    
    echo '<pre>';
    print_r($data);
    
    
    $data = (array) LocalServiceCenter::instance('Match.Rank.Get')
    ->args(array(
            'type' => 'NEWPT',
            'matchid' => 110,
            'subid' => 1388361600,
    ))
    ->get_data();
    
    echo '<pre>';
    print_r($data);
    echo '<pre>';
    die();
    

    $op_left = '123456789123456789';
    $op_right = '123456789123456789';
    echo(mt_rand($op_right,$op_left));
    $rs = bcmul($op_left, $op_right);
    var_dump($rs);
    die();