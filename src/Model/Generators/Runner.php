<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model\Generators;

use \Gekko\Model\PackageDescriptor;

class Runner
{
    /**
     * @var \Gekko\Model\Generators\IGenerator[]
     */
    private $generators;

    public function run(PackageDescriptor $package) : void
    {
        foreach ($this->generators as $generator)
            $generator->generate($package);
    }

    public function register(IGenerator $generator)
    {
        $this->generators[] = $generator;
    }
}
