<?php

namespace bbaaag\Hepler;


/**
 * Class Helper
 * 辅助处理类，一般用来定义数组公共方法       自定义的数组代码片段
 * @package common\helpers
 */
class CArrayHelper
{

    /*
     * 总述
     * 命名规则 仅适用二维数组 two_ 开头,多维multi_,索引数组以  _assoc 结尾
     *尽量贴合mysql 这种语言 可阅读性
     *
     * */



    //增

    public static function insert_before(array $arr, $offset, array $inserted)
    {
        return array_merge(array_slice($arr, 0, $offset, true) , $inserted , array_slice($arr, $offset, count($arr), true));
    }

    public static function insert_after(array $arr, $offset, array $inserted)
    {
        $offset = $offset === false ? count($arr) : $offset + 1;
        return array_merge(array_slice($arr, 0, $offset, true) , $inserted , array_slice($arr, $offset, count($arr), true));
    }


    /**
     * 在指定key 之前进行添加数组
     * Inserts new array before item specified by key.
     * @return void
     */
    public static function insert_before_accoc(array &$arr, $key, array $inserted)
    {
        $offset = (int)self::search_key($arr, $key);
        $arr = array_slice($arr, 0, $offset, true) + $inserted + array_slice($arr, $offset, count($arr), true);
    }


    /**
     * 在指定key 之后进行添加数组
     * Inserts new array after item specified by key.
     * @return void
     */
    public static function insert_after_accoc(array &$arr, $key, array $inserted)
    {
        $offset = self::search_key($arr, $key);
        $offset = $offset === false ? count($arr) : $offset + 1;
        $arr = array_slice($arr, 0, $offset, true) + $inserted + array_slice($arr, $offset, count($arr), true);
    }


    /**
     * Add cell to the start of assoc array
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     * @return array
     */
    public static function unshift_assoc(array &$array, $key, $value): array
    {
        $array = array_reverse($array, true);
        $array[$key] = $value;
        $array = array_reverse($array, true);

        return $array;
    }

    //删

    /**
     * 返回一个新数组，其中左侧删除了n个元素。
     * @param $items
     * @param int $n
     * @return array
     */
    public function drop($items, $n = 1)
    {
        return array_slice($items, $n);
    }

    public function delete()
    {

    }


    /**
     * 删除满足 回调方法的值
     * @param $items
     * @param $func
     * @return array
     */
    function remove($items, $func)
    {
        $filtered = array_filter($items, $func);

        return array_diff_key($items, $filtered);
    }


    //改
    /**
     *  降维
     */

    public static function flatten(array $arr, $preserveKeys = false)
    {
        $res = [];
        $cb = $preserveKeys
            ? function ($v, $k) use (&$res) { $res[$k] = $v; }
            : function ($v) use (&$res) { $res[] = $v; };
        array_walk_recursive($arr, $cb);
        return $res;
    }


    /**
     * 递归使用array_map
     * @param $function
     * @param $array
     * @return array
     */
    public static function array_map_recursive($function, $array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::array_map_recursive($function, $value);
            } else {
                $result[$key] = $function($value);
            }
        }

        return $result;
    }




    //查
    /**
     * 查询一个值，否则返回默认值
     * @param $var
     * @param null $default
     * @return null
     */
    public static function get(&$var, $default = null)
    {
        if (isset($var)) {
            return $var;
        }

        return $default;
    }

    /**
     * Returns the first element in an array.
     *
     * @param  array $array
     * @return mixed
     */
    public static function first(array $array)
    {
        return reset($array);
    }

    /**
     *获取数组的第一个键
     */
    function array_key_first()
    {
        if (!function_exists('array_key_first')) {
            function array_key_first(array $arr)
            {
                reset($arr);
                return key($arr);
            }
        }
    }

    /**
     * Returns the last element in an array.
     *
     * @param  array $array
     * @return mixed
     */
    public static function last(array $array)
    {
        return end($array);
    }

    /**
     * 获取数组的最后一个键
     */
    function array_key_last()
    {
        if (!function_exists("array_key_last")) {
            function array_key_last($array)
            {
                end($array);
                return key($array);
            }
        }
    }

    /**
     * key 是否存在
     * @param $key
     * @param $array
     * @param bool $returnValue
     * @return bool|null
     */
    function key_exist($key, $array, $returnValue = false)
    {
        $isExists = array_key_exists((string)$key, (array)$array);
        return $returnValue?($isExists?$array[$key]:null):$isExists;
    }

    /**
     * @param $value
     * @param array $array
     * @param bool $returnKey
     * @return bool|false|int|string|null
     */

    public static function in($value, array $array, $returnKey = false)
    {
        $inArray = in_array($value, $array, true);
        return $returnKey?($inArray?array_search($value, $array, true):null): $inArray;
    }

    /**
     * 查询索引数组指定key 返回数字下标值
     * Searches the array for a given key and returns the offset if successful.
     * @return int|false offset if it is found, false otherwise
     */

    public static function search_key(array $arr, $key,$retunrValue = false)
    {
        $foo = [$key => null];
        $index = array_search(key($foo), array_keys($arr), true);
        return $retunrValue?$arr[$key]:$index;
    }

    /**
     * Searches for a given value in an array of arrays, objects and scalar
     * values. You can optionally specify a field of the nested arrays and
     * objects to search in.
     *
     * @param  array   $array  The array to search
     * @param  scalar  $search The value to search for
     * @param  string  $field  The field to search in, if not specified
     *                         all fields will be searched
     * @return boolean|scalar  False on failure or the array key on success
     */
    public static function search_deep(array $array, $search, $field = false)
    {
        // *grumbles* stupid PHP type system
        $search = (string) $search;

        foreach ($array as $key => $elem) {
            // *grumbles* stupid PHP type system
            $key = (string) $key;

            if ($field) {
                if (is_object($elem) && $elem->{$field} === $search) {
                    return $key;
                } elseif (is_array($elem) && $elem[$field] === $search) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            } else {
                if (is_object($elem)) {
                    $elem = (array) $elem;

                    if (in_array($search, $elem)) {
                        return $key;
                    }
                } elseif (is_array($elem) && in_array($search, $elem)) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            }
        }

        return false;
    }





    /**
     * 是否关联数组
     * Check is array is type assoc
     *
     * @param $array
     * @return bool
     */
    public static function isAssoc($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }









    /* ------------------------------判读------------------------*/

    /**
     * 如果提供的函数对数组的所有元素返回true，则返回true，否则返回false
     * @param $items
     * @param $func
     * @return bool
     */

    public static function all($items, $func)
    {
        return count(array_filter($items, $func)) === count($items);
    }


    /**
     * 如果提供的函数对于数组的至少一个元素返回true，则返回true，否则返回false。
     * @param $items
     * @param $func
     * @return bool
     */

    public static function any($items, $func)
    {
        return count(array_filter($items, $func)) > 0;
    }








    /*-------------------查询相关(获取值)-------------*/
    /**
     * 更加函数或者 key 进行分组
     * @param $items
     * @param $func
     * @return array
     */

    public static function groupBy($items, $func)
    {
        $group = [];
        foreach ($items as $item) {
            if ((!is_string($func) && is_callable($func)) || function_exists($func)) {
                $key = call_user_func($func, $item);
                $group[$key][] = $item;
            } elseif (is_object($item)) {
                $group[$item->{$func}][] = $item;
            } elseif (isset($item[$func])) {
                $group[$item[$func]][] = $item;
            }
        }

        return $group;
    }







    /*------------------------对键key 的操作----------------------------------*/

    /**
     *   将多维数组中的所有键名修改为全大写或小写
     * @param $arr
     * @param int $case
     * @return array
     */
    function multi_array_change_key_case($arr, $case = CASE_LOWER)
    {
        return array_map(function ($item) use ($case) {
            if (is_array($item))
                $item = $this->multi_array_change_key_case($item, $case);
            return $item;
        }, array_change_key_case($arr, $case));
    }


    /**
     * 获取二维数组中  第二维度的所有keys
     * @param $arr
     * @param bool $loop_all 是否循环全部
     * @return array
     *
     * res Array([0] => id[1] => username)
     */
    function two_array_keys($arr, $loop_all = false)
    {
        $keys = [];
        if (!$loop_all) {
            foreach ($arr as $val) {
                return array_keys($val);
            }
        }

        array_map(function ($val) use (&$keys) {
            foreach ($val as $item => $ndval) {
                in_array($item, $keys) ?: $keys[] = $item;
            }

        }, $arr);
        return $keys;
    }

    /**
     * 多维数组中的key
     * @param $ar
     * @return array
     */
    function multi_array_keys($ar)
    {
        foreach ($ar as $k => $v) {
            $keys[] = $k;
            if (is_array($ar[$k]))
                $keys = array_merge($keys, $this->multi_array_keys($ar[$k]));
        }
        return $keys;
    }


    /**
     * 一维数组 key加前缀
     * @param $arr
     * @param string $pref
     * @return array
     */
    function array_keys_prefix($arr, $pref = "")
    {
        $rarr = array();
        foreach ($arr as $key => $val) {
            $rarr[$pref . $key] = $val;
        }
        return $rarr;
    }

    /**
     * 多维维数组 key加前缀
     * @param $arr
     * @param string $pref
     * @return array
     */
    function array_keys_prefix_multi($arr, $pref = "")
    {
        $rarr = array();
        foreach ($arr as $key => $val) {
            $rarr[] = $this->array_keys_prefix($val, $pref);
        }
        return $rarr;
    }


    /**
     * 在二维数组中搜索给定的值，如果成功则返回首个相应的键名
     * @param $parents
     * @param $searched
     * @return bool|int|string
     * two_array_search($user,['id'=>101,'name'=>'a2'])
     */

    function two_array_search($parents, $searched)
    {
        if (empty($searched) || empty($parents)) {
            return false;
        }

        foreach ($parents as $key => $value) {
            $exists = true;
            foreach ($searched as $skey => $svalue) {
                $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
            }
            if ($exists) {
                return $key;
            }
        }

        return false;
    }

    /**
     *  二维删除指定的值  two_array_remove($arr,['id'=>]
     * @return bool|mixed
     */

    function two_array_remove($parents, $searched)
    {
        $index = $this->two_array_search($parents, $searched);
        if ($index != false) {
            unset($parents[$index]);
        }
        return $parents;
    }


    /**
     *  使用array_column时  保留数组的原始键
     * @param $array
     * @param $column
     * @return array|false
     *
     *res Array( [test] => 100,[6] => 101)
     */
    function array_column_assoc($array, $column)
    {
        return array_combine(array_keys($array), array_column($array, $column));
    }

    /**
     * 适用于多维数组（不仅仅是二维）的array_column实现：
     * @param array $haystack
     * @param $needle
     * @return array
     * https://github.com/NinoSkopac/array_column_recursive
     */

    function array_column_recursive(array $haystack, $needle)
    {
        $found = [];
        array_walk_recursive($haystack, function ($value, $key) use (&$found, $needle) {
            if ($key == $needle)
                $found[] = $value;
        });
        return $found;
    }


    /**
     * 返回两个数组的 全部不同
     * @param $left
     * @param $right
     * @return array
     */
    function array_diff_both($left, $right)
    {
        return array_diff(array_merge($left, $right), array_intersect($left, $right));
    }














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
     *对二维数组进行  统计求和
     * @param $data
     * @param array $del_fields
     * @param string $prefix 前缀
     * @return array
     */
    public function array_total($data, $del_fields = [], $prefix = '')
    {
        if (!$data) return [];
        $fields = function ($data, $del_fields) {
            foreach ($data as $items) {
                return array_diff(array_keys($items), $del_fields);
            }
            return [];
        };
        $total = [];
        array_walk($fields($data, $del_fields), function ($key) use ($data, &$total, $prefix) {
            $total[$prefix . $key] = array_sum(array_column($data, $key));
        });

        return $total;
    }


    function multi_array_search()
    {

    }

    /**
     * 二维数组去除
     * @param $array2D
     * @param bool $stkeep
     * @param bool $ndformat
     * @return mixed
     */

    function multi_array_unique($array2D, $stkeep = false, $ndformat = true)
    {
        // 判断是否保留一级数组键 (一级数组键可以为非数字)
        if ($stkeep) $stArr = array_keys($array2D);

        // 判断是否保留二级数组键 (所有二级数组键必须相同)
        if ($ndformat) $ndArr = array_keys(end($array2D));

        //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        foreach ($array2D as $v) {
            $v = join(",", $v);
            $temp[] = $v;
        }

        //去掉重复的字符串,也就是重复的一维数组
        $temp = array_unique($temp);

        //再将拆开的数组重新组装
        foreach ($temp as $k => $v) {
            if ($stkeep) $k = $stArr[$k];
            if ($ndformat) {
                $tempArr = explode(",", $v);
                foreach ($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
            } else $output[$k] = explode(",", $v);
        }

        return $output;
    }












































    /**
     * ------------------------数组操作--------
     */




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
        return array_map(function ($item) use ($key) {
            return is_object($item) ? $item->$key : $item[$key];
        }, $items);
    }


    /**
     * ------------------------数组判读（过滤）--------
     */


}