# Docker-LNMP
使用`docker-compose`快速部署`lnmp(Linux, Nginx, MySQL, PHP7+Swoole)`

基本服务 `PHP7, Nginx, MySQL, Redis`  
其他服务 `OpenResty, Mysql-slave, MongoDB, Memcached, RabbitMQ, ElasticStack(Elasticsearch+Logstash+Kibana)`

PHP常用扩展 `pdo_mysql, redis, swoole`  
其他扩展 `mongodb, memcached, apcu, yaf, yaconf, xdebug`

# 1.目录结构

<details>
<summary>点击查看详细内容</summary>

```
dnmp
├── docker-compose.yml              # 编排文件
├── .env                            # 环境变量
├── elasticsearch
│   ├── conf                        # elasticsearch 配置
│   ├── data                        # elasticsearch 数据目录
│   └── logs                        # elasticsearch 日志
├── memcached
│   └── logs                        # memcached 日志
├── mongodb
│   ├── conf                        # mongodb 配置
│   ├── data                        # mongodb 数据目录
│   └── logs                        # mongodb 日志
├── mysql
│   ├── conf                        # mysql 配置
│   ├── data                        # mysql 数据目录
│   └── logs                        # mysql 日志
│   └── slave                       # mysql 从库数据和日志
├── nginx
│   ├── conf                        # nginx 配置
│   ├── html                        # 前台 静态文件
│   └── logs                        # nginx 日志
├── php
│   ├── conf                        # php 配置
│   ├── Dockerfile                  # php 安装 pdo_mysql、redis、swoole 等扩展
│   ├── logs                        # php 日志
│   └── www                         # php 动态脚本
├── rabbitmq
│   ├── conf                        # rabbitmq 配置
│   ├── logs                        # rabbitmq 日志
└── redis
    ├── conf                        # redis 配置
    ├── data                        # redis 数据目录
    └── logs                        # redis 日志
```
</details>

# 2.快速使用

<details>
<summary>环境准备 Git Docker Docker-compose</summary>

### Ubuntu 18.04
```
更换源（不换可忽略）
cp /etc/apt/sources.list /etc/apt/sources.list.bak
sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
apt-get update

1.安装Git
apt-get install git

2.1.安装依赖包
apt-get install curl software-properties-common

2.2.添加GPG密钥
curl -fsSL https://mirrors.ustc.edu.cn/docker-ce/linux/ubuntu/gpg | apt-key add -

2.3.添加软件源
add-apt-repository "deb [arch=amd64] https://mirrors.ustc.edu.cn/docker-ce/linux/ubuntu $(lsb_release -cs) stable"

2.4.安装docker和docker-compose
apt-get install docker-ce
apt-get install docker-compose

2.5.添加服务和启动docker
systemctl enable docker
systemctl start docker

2.6.添加组和加入组
groupadd docker
usermod -aG docker $USER
```
</details>

## 2.1 安装
```
git clone https://github.com/lauchunwa/misc -b dnmp dnmp    # 克隆dnmp分支并放至dnmp目录
```

## 2.2 启动
```
cd dnmp                                                     # 进入目录

# 编辑.env文件，可修改服务的版本和端口，PHP扩展的版本，以及MySQL初始密码等
# 编辑docker-compose.yml文件，对需要开启的服务，去掉前面的注释符号
# 编辑php/Dockerfile文件，对需要安装的PHP扩展，去掉前面的注释符号

docker-compose up -d                                        # 构建、创建、启动
```

<details>
<summary>常用命令</summary>

```
# docker-compose ps     # 查看容器
        Name                      Command               State                                 Ports
---------------------------------------------------------------------------------------------------------------------------------
dnmp_elasticsearch_1   /usr/local/bin/docker-entr ...   Up      0.0.0.0:32780->9200/tcp, 0.0.0.0:32779->9300/tcp
dnmp_kibana_1          /usr/local/bin/dumb-init - ...   Up      0.0.0.0:32777->5601/tcp
dnmp_logstash_1        /usr/local/bin/docker-entr ...   Up      0.0.0.0:32776->5044/tcp, 0.0.0.0:32778->9600/tcp
dnmp_memcached_1       docker-entrypoint.sh memca ...   Up      0.0.0.0:32772->11211/tcp
dnmp_mongodb_1         docker-entrypoint.sh mongo ...   Up      0.0.0.0:32770->27017/tcp
dnmp_mysql-slave_1     docker-entrypoint.sh mysqld      Up      0.0.0.0:32771->3306/tcp, 33060/tcp
dnmp_mysql_1           docker-entrypoint.sh mysqld      Up      0.0.0.0:32769->3306/tcp, 33060/tcp
dnmp_nginx_1           nginx -g daemon off;             Up      0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp
dnmp_rabbitmq_1        docker-entrypoint.sh rabbi ...   Up      0.0.0.0:32775->15672/tcp, 25672/tcp, 4369/tcp, 5671/tcp, 5672/tcp
dnmp_redis_1           docker-entrypoint.sh redis ...   Up      0.0.0.0:32768->6379/tcp
dnmp_web_1             docker-php-entrypoint php-fpm    Up      9000/tcp

# docker images         # 查看镜像
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
lauchunwa/php       7.2-with-swoole     c6ac0aae0786        2 minutes ago       105MB
rabbitmq            3.8                 392a144b8991        2 days ago          150MB
mongo               4.2                 965553e202a4        2 days ago          363MB
logstash            7.4.2               642b82780655        6 days ago          889MB
kibana              7.4.2               230d3ded1abc        6 days ago          1.1GB
elasticsearch       7.4.2               b1179d41a7b4        6 days ago          855MB
php                 7.2-fpm-alpine      bd2347230416        8 days ago          76.4MB
redis               5.0-alpine          6f63d037b592        11 days ago         29.3MB
memcached           1.5-alpine          e0ec4da6251b        11 days ago         9.08MB
nginx               1.16-alpine         aaad4724567b        11 days ago         21.2MB
mysql               5.7                 cd3ed0dfff7e        2 weeks ago         437MB
openresty/openresty 1.15.8.1-alpine     470dd2afc586        2 months ago        57.9MB

docker search IMAGE         # 查询镜像
docker pull IMAGE           # 拉取镜像
docker tag SOURCE TARGET    # 镜像打标签
docker push IMAGE           # 提交镜像
docker image ls             # 查看本地的镜像，加上-a参数可显示所以的镜像，简版docker images
docker image rm IMAGE       # 删除镜像，多个用空格隔开，简版docker rmi IMAGE
docker inspect IMAGE        # 查看镜像信息，多个用空格隔开，--format可按需显示字段及排版

docker run -d IMAGE                     # 基于镜像创建容器并后台运行容器，运行成功则输出容器ID
docker logs CONTAINER                   # 查看容器的日志，遇到错误时方便找原因，多个用空格隔开
docker container ls                     # 查看运行的容器，加上-a参数也显示未运行的容器，简版docker ps
docker container rm CONTAINER           # 删除未运行容器，多个用空格隔开，rm -f表示强制删除(运行中的)，简版docker rm CONTAINER
docker exec -it CONTAINER bash          # 在运行中的容器中执行命令，-it粗浅表示交互式伪终端，不加的话命令执行了就退出
docker start|stop|restart CONTAINER     # 启动|停止|重启 容器，多个用空格隔开
docker run -it --rm IMAGE bash          # 基于镜像临时启动一个容器，可测试安装软件，或者满足一些好奇，比如rm -rf /，--rm表示退出就销毁容器

docker run -d 
-p HOST_PORT:CONTAINER_PORT 
-v HOST_PATH:CONTAINER_PATH 
-w WORD_DIR 
-e VARIABLE_NAME=VALUE 
--link TARGET_CONTAINER_NAME:ALIAS 
--name NEW_CONTAINER_NAME 
IMAGE 
COMMAND ARG...                          # docker run 完整命令

docker-compose up -d                    # 包含构建(拉取)镜像、创建容器、启动容器，可单独运行某个或多个，在后边列出服务名称即可
docker-compose logs SERVICE             # 查看服务容器的日志，遇到错误时方便找原因，多个用空格隔开
docker-compose ps                       # 查看编排文件下的所有服务容器，可单独显示某个或多个，在后边列出服务名称即可
docker-compose exec SERVICE bash        # 在运行中的服务容器中执行命令，exec -d表示后台运行，不加参数默认exec -it
docker-compose start|stop|restart       # 启动|停止|重启 编排文件下的所有服务服务容器，可单独执行某个或多个，在后边列出服务名称即可
docker-compose down                     # 停止并移除编排文件下的所有服务容器，包括networks

docker network ls           # 查看网络列表

# 通用好习惯
docker --help
docker image --help
docker container --help
docker network --help
docker-compose --help
```
</details>

## 2.3 测试
执行以下命令输出如下结果，说明扩展都安装成功，可以愉快玩耍了
```
# docker-compose exec -d web php index.php                  # 执行测试脚本，并后台启动一个server服务
# docker-compose exec web curl 127.0.0.1:9501               # 客户端请求
<h1>Hello Swoole. #5294</h1>                                # 返回结果
```

# 3.配置改动

所有服务的配置文件都是从基础镜像中拷贝出来的，然后在此基础上新增或修改配置项目

<details>
<summary>点击查看详细内容</summary>

## 3.1 PHP
复制
```
docker run -d --name tmp-php php:7.2-fpm-alpine
docker cp tmp-php:/usr/local/etc/php-fpm.d/ ./php/conf/
docker cp tmp-php:/usr/local/etc/php/php.ini-development ./php/conf/php.ini
docker rm $(docker stop tmp-php)
```
改动
```
# egrep -n '^(|;)error_log.*\.log' php/conf/php.ini
583:error_log = /var/log/php/php_errors.log

# egrep -in 'track_errors ?= ?' php/conf/php.ini
532:track_errors = Off

# grep -n 'apc' php/conf/php.ini
1957:[apc]
1958:apc.enabled = on
1959:apc.shm_size = 64M
1960:apc.enable_cli = on
```

## 3.2 Nginx
复制
```
docker run -d --name tmp-nginx nginx:1.16-alpine
docker cp tmp-nginx:/etc/nginx/conf.d/ ./nginx/conf/conf.d/
docker cp tmp-nginx:/etc/nginx/nginx.conf ./nginx/conf/nginx.conf
docker rm $(docker stop tmp-nginx)
```
改动
```
# grep -n 'server_tokens' -B1 nginx/conf/nginx.conf
31-    # 隐藏版本号
32:    server_tokens off;

# egrep -n '~ \\.php\$' nginx/conf/conf.d/default.conf -A6
30:    location ~ \.php$ {
31-    #    root           html;
32-        fastcgi_pass   web:9000;
33-    #    fastcgi_index  index.php;
34-        fastcgi_param  SCRIPT_FILENAME  /www$fastcgi_script_name;
35-        include        fastcgi_params;
36-    }

# https wss proxy balance
nginx/conf/conf.d/laucw.com.conf
```

## 3.3 Mysql
复制
```
docker run -d -e MYSQL_ROOT_PASSWORD=123456 --name tmp-mysql mysql:5.7
docker cp tmp-mysql:/etc/mysql/my.cnf ./mysql/conf/my.cnf
docker rm $(docker stop tmp-mysql)
```
改动
```
# sed ':a;N;$!ba;s/.*conf\.d\/\n//g' mysql/conf/my.cnf
[mysqld]
explicit_defaults_for_timestamp = true
# 错误日志
log_error = /var/log/mysql/error.log
# pid_file = /var/run/mysqld/mysqld.pid
# 通用日志
# general_log = on
# general_log_file = /var/log/mysql/general.log
# 慢日志
slow_query_log = on
slow_query_log_file = /var/log/mysql/slow.log
# 主从复制
# master
server_id = 1
log_bin = bin_log
# slave
server_id = 2
relay_log = relay_log
# gtid
gtid_mode = on
enforce_gtid_consistency = on
```
主从复制相关配置
<details>
<summary>点击查看详细内容</summary>

```
# master: 172.19.0.2  slave: 172.19.0.3
# master配置
server_id = 2                       # 全局唯一，主从、从从都不能一样，通常设置为IP尾段
log_bin = bin_log_2                 # 开启二进制日志功能，可以随便取，最好有含义（如：bin_log_${ip}）
binlog_do_db = test                 # 要记录日志的库，默认全部
binlog_ignore_db = mysql            # 不记录日志的库，即不需要同步的库，多项用逗号隔开（基础表默认不记录）
sync_binlog = 1                     # 开启控制缓冲刷写磁盘的事务累积量（默认0表示MySQL不控制，为1是最安全也是最耗性能的，通常喜欢设置100）
auto_increment_offset = 1           # 偏移（每台服务器依次增加）
auto_increment_increment = 2        # 步长（大小为服务器的数量）
# slave配置
server_id = 3                       # 全局唯一，主从、从从都不能一样，通常设置为IP尾段
log_bin = bin_log_3                 # 开启二进制日志功能，可以随便取，最好有含义（如：bin_log_${ip}）
relay_log = relay_log               # 配置中继日志，从库io线程在主库上读取到新的日志内容后，写入中继日志，sql线程监测到更新后解析并写入数据库
replicate_do_db = test              # 要同步的库，默认全部
replicate_ignore_db = mysql         # 不同步的库，即不需要同步的库，多项用逗号隔开（基础表默认不同步）
log_slave_updates = 1               # 配置从库上的更新操作是否记录到二进制文件中（级联时，中级slave必须开启）
sync_binlog = 1                     # 开启控制缓冲刷写磁盘的事务累积量（默认0表示MySQL不控制，为1是最安全也是最耗性能的，通常喜欢设置100）
auto_increment_offset = 2           # 偏移（每台服务器依次增加）
auto_increment_increment = 2        # 步长（大小为服务器的数量）
read_only = 1                       # 防止改变数据(除了特殊的线程)，比如错误地把写操作请求到从库，该配置对root无效
# 其他配置
binlog_format = mixed               # 主从复制的格式，基于SQL语句statement，基于行row，混合模式mixed，默认格式是statement，通常用mixed
binlog_cache_size = 1M              # 默认32K，为每个session分配的内存，在事务过程中用来存储二进制日志的缓存
max_binlog_cache_size = 512M        # binlog最大缓存大小
max_binlog_size = 100M              # 日志文件的大小，默认最大1G
max_binlog_files = 20               # 日志文件的最大数量
expire_logs_days = 7                # 二进制日志过期清理时间。默认值为0，表示不自动删除。
slave_skip_errors = 1062            # 跳过主从复制中遇到的所有错误或指定类型的错误，避免slave端复制中断。# 如：1062错误是指一些主键重复，1032错误是因为主从数据库数据不一致

# 主库数据迁移 # 无业务数据可忽略
flush tables with read lock;
mysqldump -uroot -p --databases test > /tmp/backup.sql;
unlock tables;
# 在主库上创建一个用于复制的用户，注意：允许访问的ip填从库的或者通配
grant replication slave on *.* to repl@'mysql-slave' identified by '123456';
flush privileges;
# 在从库上进行主从关联的SQL语句
change master to master_host='mysql', master_user='repl', master_password='123456', master_log_file='bin_log_${ip}.000003', master_log_pos=154;
# 重启slave服务
show slave status\G; # 重启前状态
stop slave;
start slave;
show slave status\G; # 重启后状态
# 在主库做修改，在从库查看是否同步
```
</details>

## 3.4 Redis
复制
```
wget http://download.redis.io/releases/redis-5.0.0.tar.gz
tar -zxvf redis-5.0.0.tar.gz
cp ./redis-5.0.0/redis.conf ./redis/conf/redis.conf
rm -rf ./redis-5.0.0 redis-5.0.0.tar.gz
```
改动
```
# egrep -n '^(|;)bind|^(|;)logfile' redis/conf/redis.conf
69:bind 0.0.0.0
171:logfile "/var/log/redis/redis.log"
```

## 3.5 MongoDB
复制
```
docker run -d --name tmp-mongodb mongo:4.2
docker cp tmp-mongodb:/etc/mongod.conf.orig ./mongodb/conf/mongod.conf
docker rm $(docker stop tmp-mongodb)
```
改动
```
# egrep -n '^ *(|# *)bindIp|^ *(|# *)port' mongodb/conf/mongod.conf 
23:  port: 27017
24:  bindIp: 127.0.0.1
```

## 3.6 Memcached
复制
```
```
改动
```
```

## 3.7 RabbitMQ
复制
```
docker run -d --name tmp-rabbitmq rabbitmq:3.8
docker cp tmp-rabbitmq:/etc/rabbitmq/rabbitmq.conf ./rabbitmq/conf/rabbitmq.conf
docker rm $(docker stop tmp-rabbitmq)
```
改动
```
# cat rabbitmq/conf/rabbitmq.conf
loopback_users.guest = false
listeners.tcp.default = 5672
```

## 3.8 Elasticsearch
复制
```
docker run -d --name tmp-elasticsearch elasticsearch:7.4.2
docker cp tmp-elasticsearch:/usr/share/elasticsearch/config/elasticsearch.yml ./elasticsearch/conf/elasticsearch.yml
docker cp tmp-elasticsearch:/usr/share/elasticsearch/config/jvm.options ./elasticsearch/conf/jvm.options
docker rm $(docker stop tmp-elasticsearch)
```
改动
```
# cat config/elasticsearch.yml
cluster.name: "docker-cluster"
network.host: 0.0.0.0

# egrep -n '\-Xm' config/jvm.options
22:-Xms256m
23:-Xmx256m
```

## 3.9 Logstash
复制
```
docker run -d --name tmp-logstash logstash:7.4.2
docker cp tmp-logstash:/usr/share/logstash/config/logstash.yml ./logstash/conf/logstash.yml
docker cp tmp-logstash:/usr/share/logstash/config/jvm.options ./logstash/conf/jvm.options
docker rm $(docker stop tmp-logstash)
```
改动
```
# cat config/logstash.yml
http.host: "0.0.0.0"
xpack.monitoring.elasticsearch.hosts: [ "http://elasticsearch:9200" ]

# egrep -n '\-Xm' config/jvm.options
6:-Xms256m
7:-Xmx256m
```

## 3.10 Kibana
复制
```
docker run -d --name tmp-kibana kibana:7.4.2
docker cp tmp-kibana:/usr/share/kibana/config/kibana.yml ./kibana/conf/kibana.yml
docker rm $(docker stop tmp-kibana)
```
改动
```
# egrep -nv '^$|#' config/kibana.yml
6:server.name: kibana
7:server.host: "0"
8:elasticsearch.hosts: [ "http://elasticsearch:9200" ]
9:xpack.monitoring.ui.container.elasticsearch.enabled: true
```

</details>
