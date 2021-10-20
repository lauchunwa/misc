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
docker-compose up -d                                        # 构建、创建、启动
```

<details>
<summary>常用命令</summary>

```
# docker-compose ps     # 查看容器
    Name                  Command               State                    Ports                  
------------------------------------------------------------------------------------------------
dnmp_mysql_1   docker-entrypoint.sh mysqld      Up      0.0.0.0:32769->3306/tcp, 33060/tcp      
dnmp_nginx_1   nginx -g daemon off;             Up      0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp
dnmp_redis_1   docker-entrypoint.sh redis ...   Up      0.0.0.0:32768->6379/tcp                 
dnmp_web_1     docker-php-entrypoint php-fpm    Up      9000/tcp                                

# docker images         # 查看镜像
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
lauchunwa/php       7.2-with-swoole     c6ac0aae0786        2 minutes ago       105MB
php                 7.2-fpm-alpine      bd2347230416        8 days ago          76.4MB
redis               5.0-alpine          6f63d037b592        11 days ago         29.3MB
nginx               1.16-alpine         aaad4724567b        11 days ago         21.2MB
mysql               5.7                 cd3ed0dfff7e        2 weeks ago         437MB

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
docker-compose start|stop|restart       # 启动|停止|重启 编排文件下的所有服务容器，可单独执行某个或多个，在后边列出服务名称即可
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
