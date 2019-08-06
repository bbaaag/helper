<?php

namespace bbaaag\Hepler;



/**
 * Class Helper
 * 辅助处理类，一般用来定义数组公共方法       自定义的数组代码片段
 * @package common\helpers
 */
class CArrayHelper
{



    /**
     * ---------------------数组排序------
     */


    /**
     * 对多维数组进行排序进行排序
     * @return mixed
     * $sorted = array_orderby($data, 'volume', SORT_DESC,  'edition', SORT_ASC);
     */

    function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }





































    /**
     * ------------------------数组操作--------
     */

    /**
     * 返回一个新数组，其中左侧删除了n个元素。
     * @param $items
     * @param int $n
     * @return array
     */
    public  function drop($items, $n = 1)
    {
        return array_slice($items, $n);
    }


    /**
     * 改变原始数组以过滤掉指定的值。
     * @param $items
     * @param mixed ...$params
     * @return array
     */
    function pull(&$items, ...$params)
    {
        $items = array_values(array_diff($items, $params));
        return $items;
    }


    function pluck($items, $key)
    {
        return array_map( function($item) use ($key) {
            return is_object($item) ? $item->$key : $item[$key];
        }, $items);
    }


















    /**
     * ------------------------数组判读（过滤）--------
     */

    /**
     * 如果提供的函数对数组的所有元素返回true，则返回true，否则返回false
     * @param $items
     * @param $func
     * @return bool
     */
    public function all($items, $func)
    {
        return count(array_filter($items, $func)) === count($items);

    }

    /**
     * 如果提供的函数对于数组的至少一个元素返回true，则返回true，否则返回false。
     * @param $items
     * @param $func
     * @return bool
     */

    public  function any($items, $func)
    {
        return count(array_filter($items, $func)) > 0;
    }








}