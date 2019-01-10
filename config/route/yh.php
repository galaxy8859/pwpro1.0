<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/3/26
 * Time: 9:40
路由配置文件
 */

//路由定位参数  项目统一入口参数
$routeParameterName = 'type';

/*用户路由
 *
 * */

$GLOBALS['ROUTE'] =
    array(
        'yh/checkCode'=>array(),


        'yh/phoneCode'=>array(),

/*---------------------------------------------------------------------------------------------------*/
        'yh/login'=>array(
            'AccessPermissions'=>'PUB',//文件夹 同时代表访问等级 大写 PUB PRI 两个
            'Controller'=>'login_controller.php',//模块文件名
            'className'=>'Login',//类名
            'Method'=>'loginCheck',//方法 登陆
            'AccessType'=>'post',//请求方法
            'parameter'=>'login_type,pass',//固定参数
            'optional'=>'mobile',//选填参数
            'middleware'=>'checkFieldMiddleware.php,addCheckMiddleware.php' , //userCh
            'privileges'=>array(), //权限设定
            // eckMiddleware.php',//指定中间件文件多个用逗号隔开
            'checkFiled'=>array(


                /*检测字段格式应该为  字段名 =》数组（’检测方法 function‘ =》'方法名' ，’是否有固定值‘=》’false‘ false等于没有   ）
                 *addCheck 等于附加的检测方法， 使用这个方法需要引用addCheckMiddleware文件 参数以*连接在方法名后面
                 *'addCheck'=>'admin', //'admin*值1*值2'如果这个检测方法带有多个参数就这样依次连接
                 * */

                'mobile'=>array(   //字段名
                    'function'=>'mobileCheck@checkFieldMiddleware', //检测方法  = 方法名@对应中间件
                    'value'=>false //是否带有固定值  false 等于没有
                ),

                'login_type'=>array(
                    'function'=>'betweenCheck@checkFieldMiddleware',
                    'value'=>false,
                    'betweenCheck'=>array(1,3) //betweenCheck 的值是数组 包含前尾  如果需要是否在里面判断请用insideCheck
                ),

                'pass'=>array(

                    'function'=>'password@checkFieldMiddleware',
                    'value'=>false,
                ),

            )

        ),

/*---------------------------------------------------------------------------------------------------*/
        'yh/register'=>array(
            'AccessPermissions'=>'PUB',//文件夹 同时代表访问等级 大写 PUB PRI 两个
            'Controller'=>'login_controller.php',//模块文件名
            'className'=>'Login',//类名
            'Method'=>'loginCheck',//方法 登陆
            'AccessType'=>'post',//请求方法
            'parameter'=>'login_type,pass',//固定参数
            'optional'=>'mobile',//选填参数
            'middleware'=>'checkFieldMiddleware.php,addCheckMiddleware.php' , //userCh
            'privileges'=>array(), //权限设定
            // eckMiddleware.php',//指定中间件文件多个用逗号隔开
            'checkFiled'=>array(


                /*检测字段格式应该为  字段名 =》数组（’检测方法 function‘ =》'方法名' ，’是否有固定值‘=》’false‘ false等于没有   ）
                 *addCheck 等于附加的检测方法， 使用这个方法需要引用addCheckMiddleware文件 参数以*连接在方法名后面
                 *'addCheck'=>'admin', //'admin*值1*值2'如果这个检测方法带有多个参数就这样依次连接
                 * */

                'mobile'=>array(   //字段名
                    'function'=>'mobileCheck@checkFieldMiddleware', //检测方法  = 方法名@对应中间件
                    'value'=>false //是否带有固定值  false 等于没有
                ),

                'login_type'=>array(
                    'function'=>'betweenCheck@checkFieldMiddleware',
                    'value'=>false,
                    'betweenCheck'=>array(1,3) //betweenCheck 的值是数组 包含前尾  如果需要是否在里面判断请用insideCheck
                ),

                'pass'=>array(

                    'function'=>'password@checkFieldMiddleware',
                    'value'=>false,
                ),

            )

        ),


/*---------------------------------------------------------------------------------------------------*/





    );

