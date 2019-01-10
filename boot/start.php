<?php
/**
 * Created by 黄臣 GOOD SCRIP.
 * User: Dev02
 * Date: 2018/3/26
 * Time: 9:43
 * 启动文件
 */


include BOOT.'route.php';

//调试开关
$debug = GetSystem('sys','debug');
if($debug != 'false' && $debug!='true')
    EchoError(10004,'sys.conf配置文件错误 请检查');
$res = ($debug=='false')?0 :-1;
error_reporting($res);

//建立路由对象
$route = new Route();

//进行检测 参数个数检查 注意Token在这里检查  中间件在这里检 查
$attr = $route->checkRoute();

//路由出现错误 直接返回
if($attr['code']!=10000){
    echo json_encode($attr);
}

// 动态的 引入脚本 建立对象 调用方法
else{
    $attr = $attr['routeArr'];
    $RouteSTR = constant($attr['AccessPermissions']).$attr['Controller'];

    //引入对应的控制器 动态引入脚本
    include $RouteSTR;

    //动态建立对象
    $class = new $attr['className']();

    //动态调用方法
    call_user_func( array($class,$attr['Method']) );die;
}
die;
