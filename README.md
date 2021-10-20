# Docker-LNMP
使用`docker-compose`快速部署`lnmp(Linux, Nginx, MySQL, PHP7+Swoole)`

# 1.目录结构

<details>
<summary>点击查看详细内容</summary>

```
dnmp
├── docker-compose.yml              # 编排文件
├── .env                            # 环境变量
├── mysql
│   ├── conf                        # mysql 配置
│   ├── data                        # mysql 数据目录
│   └── logs                        # mysql 日志
├── nginx
│   ├── conf                        # nginx 配置
│   ├── html                        # 前台 静态文件
│   └── logs                        # nginx 日志
├── php
│   ├── conf                        # php 配置
│   ├── Dockerfile                  # php 安装 pdo_mysql、redis、swoole 等扩展
│   ├── logs                        # php 日志
│   └── www                         # php 动态脚本
└── redis
    ├── conf                        # redis 配置
    ├── data                        # redis 数据目录
    └── logs                        # redis 日志
```
</details>

# 2.快速使用

环境准备
[Git](https://www.liaoxuefeng.com/wiki/896043488029600/896067074338496)
[Docker](https://docs.docker.com/install/)
[Docker-compose](https://docs.docker.com/install/#install-compose)

## 2.1 安装
```
git clone https://github.com/lauchunwa/misc -b dnmp dnmp    # 克隆dnmp分支并放至dnmp目录
```

## 2.2 启动
```
cd dnmp                                                     # 进入目录
docker-compose up -d                                        # 构建、创建、启动
```

<details>
<summary>点击查看详细内容</summary>

查看容器
```
# docker-compose ps
    Name                  Command               State                    Ports                  
------------------------------------------------------------------------------------------------
dnmp_mysql_1   docker-entrypoint.sh mysqld      Up      0.0.0.0:32769->3306/tcp, 33060/tcp      
dnmp_nginx_1   nginx -g daemon off;             Up      0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp
dnmp_redis_1   docker-entrypoint.sh redis ...   Up      0.0.0.0:32768->6379/tcp                 
dnmp_web_1     docker-php-entrypoint php-fpm    Up      9000/tcp                                
```
查看镜像
```
# docker image ls
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
lauchunwa/php       7.2-with-swoole     c6ac0aae0786        2 minutes ago       105MB
php                 7.2-fpm-alpine      bd2347230416        8 days ago          76.4MB
redis               5.0-alpine          6f63d037b592        11 days ago         29.3MB
nginx               1.16-alpine         aaad4724567b        11 days ago         21.2MB
mysql               5.7                 cd3ed0dfff7e        2 weeks ago         437MB
```
</details>

## 2.3 测试
执行以下命令输出如下结果，说明扩展都安装成功，可以愉快玩耍了
```
# docker-compose exec -d web php index.php                  # 执行测试脚本，并后台启动一个server服务
# docker-compose exec web curl 127.0.0.1:9501               # 客户端请求
<h1>Hello Swoole. #5294</h1>                                # 返回结果
```
