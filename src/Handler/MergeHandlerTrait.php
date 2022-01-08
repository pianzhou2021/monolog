<?php
/*
 * @Description: 合并输出Handler
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-01-05 21:21:47
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-01-08 15:47:42
 */
/*
 * This file is part of the Monolog package.
 *
 * (c) Pian Zhou <pianzhou2021@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Pianzhou\Monolog\Handler;

/**
 * 当使用合并输出的时候，将日志合并后一次性输出
 * 
 * @author Pian Zhou <pianzhou2021@163.com>
 */
trait MergeHandlerTrait
{
    /**
     * {@inheritDoc}
     */
    public function handle(array $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        $record = $this->format($record);

        $this->write($record);

        return false === $this->bubble;
    }

    /**
     * 格式化内容
     *
     * @param array $record
     * @return array
     */
    protected function format(array $record) : array
    {
        if ($this->processors) {
            /** @var Record $record */
            $record = $this->processRecord($record);
        }

        $record['formatted'] = $this->getFormatter()->format($record);

        return $record;
    }

    /**
     * 批量处理日志
     * 
     * {@inheritDoc}
     */
    public function handleBatch(array $records): void
    {
        foreach ($records as $key => $record) {
            if (!$this->isHandling($record)) {
                unset($records[$key]);
                continue;
            }

            $record[$key] = $this->format($record);
        }

        if (empty($records)) {
            return;
        }

        $this->batchWrite($records);
    }

    /**
     * 批量写入
     *
     * @param array $records 有效记录
     * @return void
     */
    protected function batchWrite(array $records)
    {
        $formatted  = implode('', array_column($records, 'formatted'));
        $datetime   = reset($records)['datetime'];

        return $this->write(compact('formatted', 'datetime'));
    }
}