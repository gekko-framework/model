<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model;

use Gekko\Collections\Collection;
use Gekko\Serialization\JsonDescriptor;
use Gekko\Serialization\JsonSerializable;
use Gekko\Serialization\IJsonSerializable;

class ModelRelationDescriptor implements IJsonSerializable
{
    use JsonSerializable;

    /**
     * @var int One-to-One (Owns A)
     */
    const HasOne = 1;

    /**
     * @var int One-to-One (Belongs To)
     */
    const BelongsTo = 2;

    /**
     * @var int One-to-Many
     */
    const HasMany = 3;

    /**
     * Kind of relation this object represents
     *
     * @var int
     */
    private $kind;

    /**
     * Model's FQN
     * 
     * @var string
     */
    private $model;
    
    /**
     * Foreign model's FQN
     *
     * @var string
     */
    private $foreignModel;
    
    /**
     * Model's properties
     *
     * @var \Gekko\Model\PropertyRelationDescriptor[]
     */
    private $properties;

    /**
     * Relation name
     * 
     * @var string
     */
    private $name;

    public function __construct(int $kind, string $model, string $foreignModel)
    {
        $this->kind = $kind;
        $this->model = $model;
        $this->foreignModel = $foreignModel;

        $this->properties = [];
        $this->foreignProperties = [];
    }

    public function __get(string $property)
    {
        if (\property_exists($this, $property))
            return $this->{$property};
        throw new \Exception("Unknown property {$property}");
    }

    public function properties() : Collection
    {
        return Collection::of($this->properties);
    }

    public function on(string $localProperty, string $foreignProperty) : self 
    {
        $this->properties[] = new PropertyRelationDescriptor($localProperty, $foreignProperty);
        return $this;
    }

    public function asProperty(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    public function getJsonDescriptor() : JsonDescriptor
    {
        $d = new JsonDescriptor();

        $d->property("kind")->int32();
        $d->property("name")->string();
        $d->property("model")->string();
        $d->property("foreignModel")->string();
        $d->property("properties")->array()->type(\Gekko\Model\PropertyRelationDescriptor::class);

        return $d;
    }
}
