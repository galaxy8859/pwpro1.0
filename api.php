<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/5/25
 * Time: 18:12
 */
include "./boot/init.php";
include BOOT."pub_function.php";
include BOOT."protect.php";

//允许ajax跨域
header('Access-Control-Allow-Origin:*');

//引入启动文件
include BOOT.'start.php';

