<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/3/22
 *
 * Time: 11:24
 * sql 注入检查
 */

foreach($_POST as $k=>$v){
    //所有的key转换成小写
    $k = strtolower($k);

    $_POST[$k]=trim(addslashes($v));
}