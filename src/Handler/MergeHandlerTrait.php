<?php
namespace Pianzhou\Monolog\Handler;

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