<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model;

use Gekko\Serialization\JsonSerializer;

class ModelDescriptorProvider
{
    /**
     * Already instantiated model descriptors
     * 
     * @var \Gekko\Model\ModelDescriptor[]
     */
    private static $descriptors = [];


    public function resolve(string $model) : ModelDescriptor
    {
        if (isset(self::$descriptors[$model]))
            return self::$descriptors[$model];

        $ref = (new \ReflectionClass($model));
        
        $descriptorClass =  $ref->getNamespaceName() . "\\Descriptors\\"  . $ref->getShortName() . "Descriptor";

        return self::$descriptors[$model] = new $descriptorClass;
    }
}
