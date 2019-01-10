<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/6/26
 * Time: 9:29
 *
 * 裂变表处理中心 自增表不是在此处理  而是在维护系统处理
 * 当有新的数据属于裂变字表的时候提示不存在就会调用该类
 * 类将问题指派给子文件通过引入调用的方式
 *
 * 处理的表有：
 * 用户登陆表     case_center_user_XXX
 * 用户token表    case_center_user_token_XXX
 *
 *
 */
