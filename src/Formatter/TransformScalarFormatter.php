<?php 
/*
 * @Description: 将数据转换成字符类型，并且支持转换
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-01-05 21:21:47
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-01-08 13:55:55
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

    /**
     * 构造函数
     *
     * @param string|null $dateFormat 日期格式
     * @param array $transfroms 转换字段，可以自定义字段
     */
    public function __construct(?string $dateFormat = 'Y-m-d H:i:s', $transfroms=[
        'message'       => 'message',
        'channel'       => 'channel',
        'level_name'    => 'level_name',
        'level'         => 'level',
        'context'       => 'context',
        'extra'         => 'extra',
        'datetime'      => 'datetime',
    ])
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
     * @param array $array 原始数据
     * @param string $key   需要取值的键，支持a.b.c的方式
     * @param string $default 默认值
     * @return mixed
     */
    protected function value(array $array, string $key, string $default = '') : mixed
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
