<?php declare(strict_types=1);
/*
 * @Description: 将Post内容，格式化转换后添加到extra中
 * @Author: (c) Pian Zhou <pianzhou2021@163.com>
 * @Date: 2022-01-05 21:21:47
 * @LastEditors: Pian Zhou
 * @LastEditTime: 2022-01-08 21:41:29
 */

namespace Pianzhou\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

/**
 * 将Post内容，格式化转换后添加到extra中
 * 
 * maskFileds增加字段隐藏功能，支持.号，例如 data.username
 *
 * @author Pian Zhou <pianzhou2021@163.com>
 */
class PostProcessor implements ProcessorInterface
{
    protected $maskFileds   = [];
    protected $maskString   = '';
    
    public function __construct(array $maskFileds = [], string $maskString = '******')
    {
        $this->maskFileds   = $maskFileds;
        $this->maskString   = $maskString;
    }
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $record): array
    {
        $data   = $_POST;
        if (!empty($data)) {
            foreach ($this->maskFileds as $filed) {
                $this->mask($data, $filed);
            }
        }
        $record['extra']['post'] = $data;

        return $record;
    }

    /**
     * 转换值
     *
     * @param array $array 原始数据
     * @param string $key   需要取值的键，支持a.b.c的方式
     * @param string $default 默认值
     * @return void
     */
    protected function mask(array &$array, string $filed) : void
    {
        if (strpos($filed, '.') === false) {
            if (isset($array[$filed])) {
                $array[$filed] = $this->maskString;
            }

            return;
        }
        
        foreach (explode('.', $filed) as $segment) {
            if (!is_array($array) || !isset($array[$segment])) {
                return;
            } 
            $array = & $array[$segment];
        }

        $array  = $this->maskString;
    }
}