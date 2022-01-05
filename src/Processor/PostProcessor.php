<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pianzhou\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

/**
 * Adds a unique identifier into records
 *
 * @author Simon Mönch <sm@webfactory.de>
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
        foreach ($this->maskFileds as $filed) {
            if (isset($data[$filed])) {
                $data[$filed]   = $this->maskString;
            }
        }
        $record['extra']['post'] = $data;

        return $record;
    }
}
