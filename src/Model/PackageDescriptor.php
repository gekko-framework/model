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

class PackageDescriptor implements IJsonSerializable
{
    use JsonSerializable;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $schema;

    /**
     * @var \Gekko\Model\ModelDescriptor[]
     */
    private $models;

    /**
     * If true, the namespace does not map physically
     * with a directory structure
     *
     * @var bool
     */
    private $virtual;


    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $this->schema = $namespace;
        $this->models = [];
    }


    public function __get(string $property)
    {
        if (\property_exists($this, $property))
            return $this->{$property};
        throw new \Exception("Unknown property {$property}");
    }

    public function schema($schemaName) : self
    {
        $this->schema = $schemaName;
        return $this;
    }

    public function model(string $name) : ModelDescriptor
    {
        $model = new ModelDescriptor("{$this->namespace}\\{$name}");
        $this->models[] = $model;
        return $model;
    }

    public function virtual() : self
    {
        $this->virtual = true;
        return $this;
    }

    public function addModel(ModelDescriptor $model) : self
    {
        $model->namespace("{$this->namespace}\\{$model->namespace}");
        $this->models[] = $model;
        return $this;
    }

    public function getJsonDescriptor() : JsonDescriptor
    {
        $d = new JsonDescriptor();

        $d->property("namespace")->string();
        $d->property("schema")->string();
        $d->property("virtual")->boolean();
        $d->property("models")->array()->type(\Gekko\Model\ModelDescriptor::class);

        return $d;
    }
}
