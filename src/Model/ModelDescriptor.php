<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model;

use \Gekko\Collections\Collection;
use \Gekko\Serialization\JsonDescriptor;
use \Gekko\Serialization\JsonSerializable;
use \Gekko\Serialization\IJsonSerializable;

class ModelDescriptor implements IJsonSerializable
{
    use JsonSerializable;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var \Gekko\Model\PropertyDescriptor[]
     */
    private $properties;

    /**
     * @var \Gekko\Model\ModelRelationDescriptor[]
     */
    private $relationships;


    public function __construct(string $fqn)
    {
        $fqnParts = explode("\\", $fqn);
        $this->className = \array_pop($fqnParts);
        $this->namespace = count($fqnParts) > 0 ? implode("\\", $fqnParts) : null;
        $this->tableName = $this->className;
        $this->properties = [];
        $this->relationships = [];
    }


    public function getProperty(string $propertyName) : PropertyDescriptor
    {
        return $this->properties()->first(function (PropertyDescriptor $p) use ($propertyName) { return $p->propertyName == $propertyName; });
    }

    public function properties() : Collection
    {
        return Collection::of($this->properties);
    }

    public function relationships() : Collection
    {
        return Collection::of($this->relationships);
    }

    public function namedRelationships() : Collection
    {
        return Collection::of($this->relationships)->where(function ($r) {
            return $r->name !== null;
        });
    }

    public function __get(string $property)
    {
        if (\property_exists($this, $property))
            return $this->{$property};
        throw new \Exception("Unknown property {$property}");
    }

    public function fullname() : string
    {
        if (empty($this->namespace))
            return $this->className;
        return "{$this->namespace}\\{$this->className}";
    }

    public function namespace(string $ns) : self
    {
        if ($this->namespace == null)
            $this->namespace = $ns;
        else
            $this->namespace .= "\\" . $ns;

        return $this;
    }

    public function tableName(string $tname) : self
    {
        $this->tableName = $tname;
        return $this;
    }

    public function property(string $name) : PropertyDescriptor
    {
        $property = new PropertyDescriptor($this, $name);
        
        $this->properties[] = $property;

        return $property;
    }

    public function hasOne(ModelDescriptor $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::HasOne, $this->fullname(), $foreignModel->fullname());
        $this->relationships[] = $relation;
        return $relation;
    }

    protected function hasOneOfClass(string $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::HasOne, $this->fullname(), $foreignModel);
        $this->relationships[] = $relation;
        return $relation;
    }

    public function belongsTo(ModelDescriptor $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::BelongsTo, $this->fullname(), $foreignModel->fullname());
        $this->relationships[] = $relation;
        return $relation;
    }

    protected function belongsToClass(string $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::BelongsTo, $this->fullname(), $foreignModel);
        $this->relationships[] = $relation;
        return $relation;
    }

    public function hasMany(ModelDescriptor $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::HasMany, $this->fullname(), $foreignModel->fullname());
        $this->relationships[] = $relation;
        return $relation;
    }

    protected function hasManyOfClass(string $foreignModel) : ModelRelationDescriptor
    {
        $relation = new ModelRelationDescriptor(ModelRelationDescriptor::HasMany, $this->fullname(), $foreignModel);
        $this->relationships[] = $relation;
        return $relation;
    }

    public function getJsonDescriptor() : JsonDescriptor
    {
        $d = new JsonDescriptor();

        $d->property("className")->string();
        $d->property("namespace")->string();
        $d->property("tableName")->string();
        $d->property("properties")->array()->type(PropertyDescriptor::class);
        $d->property("relationships")->array()->type(ModelRelationDescriptor::class);
        
        return $d;
    }
}
