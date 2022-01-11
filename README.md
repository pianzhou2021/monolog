# pianzhou/monolog

## 安装

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

```
composer require pianzhou/monolog
```


# 一、Database Handler介绍

### 首先需要创建一下数据表，以MySQL为例:

```
CREATE TABLE IF NOT EXISTS `t_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `channel` varchar(20) NOT NULL,
  `level_name` varchar(20) NOT NULL,
  `level` smallint(6) NOT NULL,
  `context` text NOT NULL,
  `extra` text NOT NULL,
  `datetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;
```
### 目前Database Handler支持PDO实例和laravel ConnectionInterface实例

#### 1、PDO实例使用方法：
```
$dbHandler     = new \Pianzhou\Monolog\Handler\DatabaseHandler(
    new PDO('mysql:host=localhost;dbname=test', 'root', ''),
    't_logs',
    \Monolog\Logger::DEBUG,
    true
 );

 $dbHandler->setFormatter(new \Pianzhou\Monolog\Formatter\TransformScalarFormatter());

 $logger    = new \Monolog\Logger('测试', [
     $dbHandler,
 ]);

$logger->info('测试一下写入数据库');
```
#### 2、laravel ConnectionInterface实例使用方法(需要安装依赖：illuminate/database)
```
$dbHandler     = new \Pianzhou\Monolog\Handler\DatabaseHandler(
    new Illuminate\Database\MySqlConnection(
      new PDO('mysql:host=localhost;dbname=test', 'root', '')
    ),
    't_logs',
    \Monolog\Logger::DEBUG,
    true
 );

 $dbHandler->setFormatter(new \Pianzhou\Monolog\Formatter\TransformScalarFormatter());

 $logger    = new \Monolog\Logger('测试', [
     $dbHandler,
 ]);

$logger->info('测试一下写入数据库');
```
通过上面的demo，我们就可以很轻松的将日志文件记录到数据库中了。另外，如果使用BufferHandler批量化写入日志的话，DatabaseHandler会一次性批量化的将日志记录写入数据库，而不是一条条记录。

相关链接：

[基于PHP Monolog，打造数据库日志记录器(DatabaseHandler)](https://blog.csdn.net/pianzhou2021/article/details/122431923)

[基于PHP Monolog，打造一个API请求日志记录器](https://blog.csdn.net/pianzhou2021/article/details/122433988)