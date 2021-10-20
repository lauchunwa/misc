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
Git Docker Docker-compose

<details>
<summary>点击查看详细内容</summary>

## Ubuntu 18.04
1.更换源（不换可忽略）
```
cp /etc/apt/sources.list /etc/apt/sources.list.bak
sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
apt-get update
```
2.安装Git
```
apt-get install git
```
3.安装依赖包
```
apt-get install curl software-properties-common -y
```
4.添加GPG密钥
```
curl -fsSL https://mirrors.ustc.edu.cn/docker-ce/linux/ubuntu/gpg | apt-key add -
```
5.添加软件源
```
add-apt-repository "deb [arch=amd64] https://mirrors.ustc.edu.cn/docker-ce/linux/ubuntu $(lsb_release -cs) stable"
```
6.安装docker和docker-compose
```
apt-get install docker-ce -y
apt-get install docker-compose -y
```
7.添加服务和启动docker
```
systemctl enable docker
systemctl start docker
```
8.添加组和加入组
```
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
