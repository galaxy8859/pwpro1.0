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

//是正斜杠  反斜杠参数进入的时候会将但斜杠过滤成为双斜杠

$GLOBALS['ROUTE'] =
    array(
        'in/checkCode'=>array(),
        'aa/phoneCode'=>array(),




        'aa/login'=>array(
            'AccessPermissions'=>'PUB',//文件夹 同时代表访问等级 大写 PUB PRI 两个
            'Controller'=>'LoginController.php',//模块文件名
            'className'=>'Login',//类名
            'Method'=>'loginCheck',//方法 登陆
            'AccessType'=>'post',//请求方法
            'parameter'=>'username,password',//固定参数
            'optional'=>'VerificationCode'//选填参数
        ),


        //获取用户的跟单记录
        'ac/follow'=>array(
            'AccessPermissions'=>'PRI',//文件夹 同时代表访问等级 大写 PUB PRI 两个
            'Controller'=>'userFollowController.php',//模块文件名
            'className'=>'Follow',//类名
            'Method'=>'getFollow',//方法 登陆
            'AccessType'=>'post',//请求方法
            'parameter'=>'token,user_id',//固定参数
            'optional'=>'username,trade_category,start_time,end_time,sort,sort_type,excl,rows,page',//选填参数
            //中间件验证 在生产环境的时候千万要打开
            //'middleware'=>'userCheckMiddleware.php'
            'middleware'=>'checkFieldMiddleware.php', //userCheckMiddleware.php',
            //如果添加了自动检测字段类型的功能需要指定参数信息
            'checkField'=>array(
                'start_time'=>array(
                    'type'=>'numeric',
                ),

                'end_time'=>array(
                    'type'=>'numeric',
                )
            ),
        ),

        //开设后台分销商接口
        'ab/sys_user'=>array(
            'AccessPermissions'=>'PRI',//文件夹 同时代表访问等级 大写 PUB PRI 两个
            'Controller'=>'sysUserController.php',//模块文件名
            'className'=>'sysUser',//类名
            'Method'=>'createSysUser',//方法 登陆
            'AccessType'=>'post',//请求方法
            'parameter'=>'token,username,password,true_name,mobile,parent_account',//固定参数
            'optional'=>'level',//选填参数
            //中间件验证 在生产环境的时候千万要打开
            //'middleware'=>'userCheckMiddleware.php'
            'middleware'=>'checkFieldMiddleware.php', //userCheckMiddleware.php',
            //如果添加了自动检测字段类型的功能需要指定参数信息
            'checkField'=>array(
                'password'=>array(
                    'length'=>'6',
                ),

                'mobile'=>array(
                    'type'=>'numeric',
                )
            ),
        ),


    );



