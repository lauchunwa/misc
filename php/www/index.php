<?php
// php7.0
// echo 1 ?? 0;

// pdo_mysql 扩展
if (in_array('pdo_mysql', get_loaded_extensions())) {
    echo "mysql installed\n";
    try {
        $conn = new PDO('mysql:dbname=mysql;host=mysql;port=3306;', 'root', '123456');
        $query = $conn->query('select host, user from user');
        $ret = $query->fetch(PDO::FETCH_ASSOC);
    } catch(\Exception $e) {
        // echo " -- " . $e->getMessage() . "\n";
    }
} else {
    echo "mysql uninstall\n";
}

// redis 扩展
if (in_array('redis', get_loaded_extensions())) {
    echo "redis installed\n";
    try {
	// Key:         del dump exists expire expireAt pExpire pExpireAt keys move persist pTtl ttl randomKey rename renameNx type
	// String:      set get getRange getSet getBit mGet setBit setEx setNx setRange strLen mSet mSetNx pSetEx incr incrBy incrByFloat decr decrBy
	// Hash:        hDel hExists hGet hGetAll hIncrBy hIncrByFloat hKeys hLen hMGet hMSet hSet hSetNx hVals hScan
	// List:        bLPop bRPop bRPopLPush linDex linSert lLen lPop lPush lPushX lRange lRem lSet lTrim rPop rPopLPush rPush RPushX
	// Set:         sAdd sCard sDiff sDiffStore sInter sInterStore sIsMember sMember sMove sPop sRandomMember sRem sUnion sUnionStore sScan
	// Sorted Set:  zAdd zCard zCount zIncrBy zInterScore zLexCount zRange zRangeByLex zRank zRem zRemRangeByLex zRemRangeByRank zRemRangeByScore zRevRange zRevRangeByScore zRevRank zScore zUinonStore zScan
	// HyperLogLog: pfAdd pfCount pfMerge
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $redis->set('name', 'redis');
        $name = $redis->get('name');
    } catch(\Exception $e) {
        // echo " -- " . $e->getMessage() . "\n";
    }
} else {
    echo "redis uninstall\n";
}

// mongodb 扩展
if (in_array('mongodb', get_loaded_extensions())) {
    echo "mongodb installed\n";
    try {
        $conn = new MongoDB\Driver\Manager("mongodb://mongodb:27017");
    } catch(\Exception $e) {
        // echo " -- " . $e->getMessage() . "\n";
    }
} else {
    echo "mongodb uninstall\n";
}
/* $mongo = new MongoClient();
$db = $mongo->test;
$collection = $db->createCollection('demo_collection');
// $collection = $db->demo_collection;
$document = array(
    'title' => 'MongoDB',
    'description' => 'database',
);
$collection->insert($document);
// db.demo_collection.find().pretty();
// $collection->update(array("title"=>"MongoDB"), array('$set'=>array("title"=>"MongoDB 教程")));
// $collection->remove(array("title"=>"MongoDB 教程"), array("justOne" => true));
$cursor = $collection->find();
foreach ($cursor as $document) {
    echo $document['title'] . "\n";
} */

// memcached 扩展
if (in_array('memcached', get_loaded_extensions())) {
    echo "memcached installed\n";
    try {
	// add replace set get delete flush increment decrement setMulti getMulti deleteMulti getResultCode getRessultMessage
        $mem = new Memcached();
        $mem->addServer([['memcached', 11211]]);
        $mem->set('name', "memcached", 0);
        $name = $mem->get('name');
    } catch(\Exception $e) {
        // echo " -- " . $e->getMessage() . "\n";
    }
} else {
    echo "memcached uninstall\n";
}

// apcu 扩展
if (in_array('apcu', get_loaded_extensions())) {
    echo "apcu installed\n";
    // apcu_add apcu_cache_info apcu_cas apcu_clear_cache apcu_dec apcu_delete apcu_entry apcu_exists apcu_fetch apcu_inc apcu_sma_info apcu_store
    apcu_store('name', "apcu");
    apcu_fetch('name');
} else {
    echo "apcu uninstall\n";
}

// yaf 扩展
if (in_array('yaf', get_loaded_extensions())) {
    echo "yaf installed\n";
    $app = new Yaf_Application("/usr/local/etc/php/php.ini");
} else {
    echo "yaf uninstall\n";
}

// swoole 扩展
if (in_array('swoole', get_loaded_extensions())) {
    echo "swoole installed\n";
    if (PHP_SAPI == 'cli') {
        new ws;
    }
} else {
    echo "swoole uninstall\n";
}

class ws
{
    const SERVER_HOST = '0.0.0.0';
    const SERVER_POST = 9501;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::SERVER_HOST, self::SERVER_POST);

        $this->ws->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
        ]);

        $this->ws->on('start', [$this, 'onStart']);
        $this->ws->on('workerStart', [$this, 'onWorkerStart']);
        $this->ws->on('request', [$this, 'onRequest']);
        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->on('shutdown', [$this, 'onShutdown']);

        $this->ws->start();
    }

    // 服务启动时
    public function onStart($serv)
    {
        // 初始化
        try {
            $redis = new Redis();
            $redis->connect('redis');
            $redis->del('connections');
        } catch(\Exception $e) {
            // echo " -- " . $e->getMessage() . "\n";
        }

        echo "Swoole http server is started at http://" . self::SERVER_HOST . ":" . self::SERVER_POST . "\n";
    }

    // 进程启动时
    public function onWorkerStart($serv)
    {
        // 加载基础文件
        if (is_file($file = __DIR__ . '/micro/laucw/base.php')) {
            require_once $file;
        } else {
            echo " -- " . basename($file) . " missing.\n";
        }

        echo "WorekrStart {$serv->worker_id}\n";
    }

    // 客户端请求时
    public function onRequest($request, $response) {
        // $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        $_GET = [];
        if (isset($request->get)) {
            $_GET = $request->get;
        }
        $_GET ?: $_SERVER['QUERY_STRING'] = '';
        $_POST = [];
        if (isset($request->post)) {
            $_POST = $request->post;
        }
        $_REQUEST = array_merge($_GET, $_POST);
        $_FILES = [];
        if (isset($request->files)) {
            $_FILES = $request->files;
        }
        $_COOKIE = [];
        if (isset($request->cookie)) {
            $_COOKIE = $request->cookie;
        }
        // $_ENV = [];
        // $_SESSION = [];

        ob_start();
        try {
            if (class_exists('\laucw\lib\Kernel')) {
                $response->header("Content-Type", "text/plain");
                \laucw\lib\Kernel::run();
            } else {
                $response->header("Content-Type", "text/html");
                echo "<h1>Hello Swoole. #".rand(1000, 9999)."</h1>\n";
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
        $res = ob_get_contents();
        ob_end_clean();

        echo "New request\n";
        $response->end($res);
    }

    // 客户端连接时
    public function onOpen($serv, $req) {
        try {
            $redis = new Redis();
            $redis->connect('redis');
            $redis->hSet('connections', $req->fd, '');
        } catch(\Exception $e) {
            // echo " -- " . $e->getMessage() . "\n";
        }

        $message = $req->fd . ' come in.';
        $this->sendToAll($req->fd, $serv, $message);

        echo "connection open: {$req->fd}\n";
    }

    // 收到客户端消息时
    public function onMessage($serv, $frame) {
        $message = $frame->fd . ': ' . $frame->data;
        $this->sendToAll($frame->fd, $serv, $message);

        echo "received message: {$frame->data}\n";
        $serv->push($frame->fd, json_encode(["hello", "world"]));
    }

    // 广播消息
    public function sendToAll($fd, $serv, $message)
    {
        try {
            $redis = new Redis();
            $redis->connect('redis');

            $connections = $redis->hGetAll('connections');
            foreach ($connections as $key => $val) {
                if ($key != $fd) {
                    $serv->push($key, $message);
                }
            }
        } catch(\Exception $e) {
            // echo " -- " . $e->getMessage() . "\n";
        }
    }

    // 客户端断开连接时
    public function onClose($serv, $fd) {
        try {
            $redis = new Redis();
            $redis->connect('redis');
            $redis->hDel('connections', $fd);
        } catch(\Exception $e) {
            // echo " -- " . $e->getMessage() . "\n";
        }

        // if ($serv->connection_info($fd)['websocket_status'] != 0) {
            $message = $fd . ' quit.';
            $this->sendToAll($fd, $serv, $message);
        // }

        echo "connection close: {$fd}\n";
    }

    // 服务停止时
    public function onShutdown() {
        echo "Shutdown event triggered\n";
    }
}
