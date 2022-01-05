<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pianzhou\Monolog\Handler;

use Monolog\Handler\StreamHandler as HandlerStreamHandler;

/**
 * Stores to any stream resource
 *
 * Can be used to store into php://stderr, remote and local files, etc.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class StreamHandler extends HandlerStreamHandler
{
    use MergeHandlerTrait;
}
