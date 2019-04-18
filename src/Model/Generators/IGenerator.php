<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model\Generators;

use \Gekko\Model\PackageDescriptor;
use \Gekko\Model\PropertyDescriptor;
use \Gekko\Types\Type;

interface IGenerator
{
    public function generate(PackageDescriptor $package) : void;
    function mapType(PropertyDescriptor $property) : string;
}