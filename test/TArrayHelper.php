<?php

namespace bbaaag\Test;


use bbaaag\Hepler\CArrayHelper;
/**
 * Class Helper
 * 辅助处理类，一般用来定义数组公共方法       自定义的数组代码片段
 * @package common\helpers
 */
class TArrayHelper extends CArrayHelper
{

    public $data = [[
        'carrier_id' => 80015,
        'company_name' => '县万泰80015',
        'type1_waybill_number' => '',
        'type2_waybill_number' => 0,
        'waybill_number' => 2,
        'total_quantity' => 50.000000,
        ],[
            'carrier_id' => 82504,
            'company_name' => '徽森普82504',
            'type1_waybill_number' => 1,
            'type2_waybill_number' => 0,
            'waybill_number' => 1,
            'total_quantity' => 10.000001,
        ]];



    public  $user = array(
        0 => array('id' => 100, 'username' => 'a1'),
        6 => array('id' => 101, 'username' => 'a2'),
        2 => array('id' => 102, 'username' => 'a3'),
        3 => array('id' => 103, 'username' => 'a4'),
        4 => array('id' => 104, 'username' => 'a5'),
        5 => array('id' => 104, 'username' => 'a5'),
    );




    function test_all(){
        $this->all($this->user,function ($item){
           return $item['id']>100;
        });
    }






}