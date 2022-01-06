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

use Monolog\Handler\StreamHandler as HandlerStreamHandler;

/**
 * Stores to any stream resource
 * 使用该修改后的Handler，将一次性输出日志到文件
 *
 * Can be used to store into php://stderr, remote and local files, etc.
 *
 * @author Pian Zhou <pianzhou2021@163.com>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class StreamHandler extends HandlerStreamHandler
{
    use MergeHandlerTrait;
}
