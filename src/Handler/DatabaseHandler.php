<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Pian Zhou <pianzhou2021@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pianzhou\Monolog\Handler;

use Illuminate\Database\ConnectionInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use PDO;
use Pianzhou\Monolog\Formatter\TransformScalarFormatter;
use Pianzhou\Monolog\Handler\MergeHandlerTrait;

/**
 * Logs to a database
 *
 * usage example:
 *
 *   $log = new Logger('application');
 *   $database = new DatabaseHandler(new PDO('mysql:host=127.0.0.1; dbname=test', 'root', 'password'), "logs", Logger::DEBUG, true);
 *   $log->pushHandler($database);
 *
 * @author Pian Zhou <pianzhou2021@163.com>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class DatabaseHandler extends AbstractProcessingHandler
{
    use MergeHandlerTrait;
    /** @var PDO | ConnectionInterface */
    protected $connection;
    /** @var string */
    protected $table;

    /**
     * @param PDO $connection   The PDO instance
     * @param string                $key     The key name to push records to
     */
    public function __construct($connection, string $table = 'logs', $level = Logger::DEBUG, bool $bubble = true)
    {
        if (!(($connection instanceof PDO) || ($connection instanceof ConnectionInterface))) {
            throw new \InvalidArgumentException('PDO or Laravel ConnectionInterface instance required');
        }

        $this->connection = $connection;
        $this->table = $table;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $record): void
    {
        $this->insert($record['formatted']);
    }

    /**
     * 批量写入
     *
     * @param array $records
     * @return void
     */
    protected function batchWrite(array $records)
    {
        return $this->insert(array_column($records, 'formatted'));
    }


    /**
     * 插入数据
     * 
     * @return bool
     */
    protected function insert(array $records)
    {
        /**
         * 如果是Laravel connction，则直接调用insert方法
         * 
         */
        if ($this->connection instanceof ConnectionInterface) {
            return $this->connection->table($this->table)->insert($records);
        }

        if (! is_array(reset($records))) {
            $records = [$records];
        }

        $columns = implode(', ', array_keys(reset($records)));

        $bindValues     = [];
        $parameters   = [];
        foreach ($records as $record) {
            $parameters[] = '(?' . str_repeat(', ?', count($record) - 1) . ')';
            $bindValues = array_merge($bindValues, array_values($record));
        }
        $parametersString = implode(', ', $parameters);

        $sql    = "insert into {$this->table} ($columns) values {$parametersString}";

        $statement   = $this->connection->prepare($sql);
        
        //绑定参数
        foreach ($bindValues as $key => $bindValue) {
            $statement->bindValue($key+1, $bindValue);
        }
        
        return $statement->execute();
    }


    /**
     * Gets the default formatter.
     *
     * Overwrite this if the LineFormatter is not a good default for your handler.
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new TransformScalarFormatter();
    }
}
