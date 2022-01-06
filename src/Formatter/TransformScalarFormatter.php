<?php 

/*
 * This file is part of the Monolog package.
 *
 * (c) Pian Zhou <pianzhou2021@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pianzhou\Monolog\Formatter;

use Monolog\Formatter\ScalarFormatter;

/**
 * Formats data into an associative array of scalar values.
 * Objects and arrays will be JSON encoded.
 *
 * @author Pian Zhou <pianzhou2021@163.com>
 */
class TransformScalarFormatter extends ScalarFormatter
{
    protected $transforms   = [];

    public function __construct(?string $dateFormat = null, $transfroms=[])
    {
        $this->transforms  = $transfroms;
        return parent::__construct($dateFormat);
    }
    
    /**
     * {@inheritDoc}
     *
     * @phpstan-return array<string, scalar|null> $record
     */
    public function format(array $record): array
    {
        $result = [];
        foreach ($this->transforms as $key => $value) {
            $result[$key] = $this->value($record, $value);
        }

        return parent::format($result);
    }

    /**
     * 转换值
     * 
     */
    protected function value($array, $key, $default = '')
    {
        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}
