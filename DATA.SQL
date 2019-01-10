create user'caseone'@'%' identified by 'Wmxp_hjhi0:A'; //新建用户
grant select,insert,update,alter on *.* to caseone@'%';
grant create on *.* to caseone@'%';

//参考 https://www.cnblogs.com/SQL888/p/5748824.html

//用户注册记录表 总表 自增表
CREATE TABLE case_center_user_record_1(
  id INT NOT NULL AUTO_INCREMENT,
  user_name CHAR(32) NOT NULL comment '用户账号，通常应该是用户手机号码 长度不能超过32位',
  mobile CHAR(11) NOT NULL COMMIT '用户电话号码',
  pass VARCHAR(255) DEFAULT NULL '密码 默认没有密码 为null没有开启密码登陆';
  register_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '创建时间',
  register_ip CHAR(128) NOT NULL comment '注册时使用的ip地址',
)engine=innodb DEFAULT charset=utf8mb4 comment '用户注册记录表';

//用户表 拆分表  账号前三位分子表  如138 131 总表按照数量切分 总表只给后台记录方便使用 被动创建表
CREATE TABLE case_center_user_XXX(
  id INT NOT NULL AUTO_INCREMENT,
  user_name CHAR(32) NOT NULL comment '用户账号，通常应该是用户手机号码 长度不能超过32位',
  mobile CHAR(11) NOT NULL COMMIT '用户电话号码',
  pass VARCHAR(255) DEFAULT NULL '密码 默认没有密码 为null没有开启密码登陆';
  register_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '创建时间',
  last_login_time int(11) NOT NULL comment '用户最后登陆时间',
  last_login_ip CHAR(128) NOT NULL comment '最后登陆的IP地址',
  status tinyint NOT NULL DEFAULT 1 comment '账户状态，1 启用 2安全停用 3账户禁止使用',

  PRIMARY KEY (id),
  KEY user_name(user_name),
  KEY status(status)
)engine=innodb DEFAULT charset=utf8mb4 comment '用户表';

//token 登陆表 拆分表 跟用户拆分方式相同 账号前三位如 138 131  被动创建 同一个用户只有一条 保证查询速度 写入应用insert OR update 数据产生的时候入库  使用中热更
CREATE TABLE case_center_user_token_XXX(
   user_id INT NOT NULL comment '用户ID',
   token char(32) NOT NULL comment '当前token',
   token_create_time int(11) not null comment 'token生成时间',
   token_expire int(11) not null comment 'token过期时间',
   refresh_token char(32) not null comment '刷新token',
   refresh_token_time int(11) not null comment '刷新token生成时间',
   refresh_token_use tinyint not null DEFAULT 0 comment '刷新token是否使用  默认为0:未使用 1:为已经使用',
   token_action_ip CHAR(128) not null comment 'token最后登陆的ip地址，当核对token成功时发现ip有变化即时更新'
)engine=innodb DEFAULT charset=utf8mb4 comment '用户token表';

/*
  用户登陆记录表 拆分表+自增表 拆分默认自增表为1 热更redis系统+系统表查找
  记录用户的所有token记录 记录用户所有的登陆记录 只有查询的时候才会使用 平时多是写入

  通过该表可以追踪用户位置变化记录
  每次用户token过来 如果ip不是和上次相同都应记录在redis 然后在定时更新进此表 若直接进表会导致并发问题

*/
CREATE TABLE case_center_user_token_record_XXX_X(
   token CHAR(32) NOT NULL comment '用户token',
   ip CHAR(128) NOT NULL comment '请求时携带的IP',
   before_ip CHAR(128) NOT NULL comment '上次IP',
   user_id INT NOT NULL comment '用户ID',
   update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '写入时间',

   KEY user_id(user_id)
)engine=innodb DEFAULT charset=utf8 comment '用户登陆记录表';

/*
  自增表  当数据量达到一定数量的时候进行分表操作  具体在system 表中配置 分表的工作在定时任务系统中进行操作 另外一套维护系统
    create table tb_member2 like tb_member1;
*/
//系统表  记录当时正在运行的一些配置 这个表应该热更在redis 避免系统初始化的时候需要进行该表的查询
CREATE TABLE case_center_system(
  table_name VARCHAR(32) NOT NULL comment '表名 格式为 case_center_user_token_record_XXX 自增表的最后数字连同下划线不要',
  current_table VARCHAR(32) NOT NULL comment '当前使用中的表',
  update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '更新时间',
  desc VARCHAR(64) DEFAULT NULL comment '备注',

  KEY table_name(table_name)
)engine=innodb DEFAULT charset=utf8mb4 comment '系统当前配置表';


//系统表通知消息
CREATE TABLE case_center_system_manager(
  `name` VARCHAR(20) not null comment '姓名',
  mobile CHAR(11) NOT NULL comment  '管理员手机号码',

  UNIQUE KEY mobile(mobile)
)engine=innodb DEFAULT charset=utf8mb4 comment '系统更新时候通知的联系人';

//用户短信验证 自增表 热更redis
CREATE TABLE case_center_msg_1(
  id INT NOT NU1LL AUTO_INCREMENT,
  mobile CHAR(11) NOT NULL comment '请求验证码的手机',
  chk INT NOT NULL comment '验证码 数字类型',
  site tinyint NOT NULL comment '请求验证码位置 1：登陆',
  use tinyint NOT NULL DEFAULT 0 comment '此验证码有没有使用 0:未使用 1:已经使用',
  create_time INT(11) null comment '创建时间',

   KEY mobile(mobile),
   KEY chk(chk),
   KEY site(site),
   KEY use(use),
)engine=innodb DEFAULT charset=utf8mb4 comment '用户短信验证表';

//图形验证码  自增表 热更redis
CREATE TABLE case_center_chk_1(
  id INT NOT NULL AUTO_INCREMENT,
  user_name CHAR(32) NOT NULL comment '用户账号，通常应该是用户手机号码 长度不能超过32位',
  chk CHAR(8) NOT NULL comment '验证码 数字字母类型',
  site tinyint NOT NULL comment '请求验证码位置 1：短信请求时候的图文验证码',
  use tinyint NOT NULL DEFAULT 0 comment '此验证码有没有使用 0:未使用 1:已经使用',
  create_time INT(11) null comment '创建时间',

   KEY user_name(user_name),
   KEY chk(chk),
   KEY site(site),
   KEY use(use)
)engine=innodb DEFAULT charset=utf8mb4 comment '图形验证码表';
