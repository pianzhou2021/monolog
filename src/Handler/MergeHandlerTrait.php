<?php
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
     */
    protected function format(array $record)
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
        $formatted  = '';
        $datetime   = '';

        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }

            $record = $this->format($record);

            $formatted    .= $record['formatted'];
            $datetime   = $record['datetime'];
        }

        if (!$formatted) {
            return;
        }

        $this->write(compact('formatted', 'datetime'));
    }
}