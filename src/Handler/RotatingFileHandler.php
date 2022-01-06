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

use Monolog\Handler\RotatingFileHandler as HandlerRotatingFileHandler;

/**
 * Stores logs to files that are rotated every day and a limited number of files are kept.
 * 使用该修改后的Handler，将一次性输出日志到文件
 *
 * This rotation is only intended to be used as a workaround. Using logrotate to
 * handle the rotation is strongly encouraged when you can use it.
 *
 * @author Pian Zhou <pianzhou2021@163.com>
 */
class RotatingFileHandler extends HandlerRotatingFileHandler
{
    use MergeHandlerTrait;
}
