<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model;

use \Gekko\Serialization\JsonDescriptor;
use \Gekko\Serialization\JsonSerializable;
use \Gekko\Serialization\IJsonSerializable;

class PropertyRelationDescriptor implements IJsonSerializable
{
    use JsonSerializable;

    /**
     * @var string
     */
    private $local;

    /**
     * @var string
     */
    private $foreign;

    public function __construct(string $local, string $foreign)
    {
        $this->local = $local;
        $this->foreign = $foreign;
    }

    public function __get(string $property)
    {
        if (\property_exists($this, $property))
            return $this->{$property};
        throw new \Exception("Unknown property {$property}");
    }

    public function getJsonDescriptor() : JsonDescriptor
    {
        $d = new JsonDescriptor();

        $d->property("local")->string();
        $d->property("foreign")->string();

        return $d;
    }
}
