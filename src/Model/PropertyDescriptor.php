<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model;

use \Gekko\Serialization\{ JsonDescriptor, JsonSerializable, IJsonSerializable };

class PropertyDescriptor implements IJsonSerializable
{
    use JsonSerializable;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $columnName;

    /**
     * @var \Gekko\Model\ModelDescriptor
     */
    private $model;

    /**
     * @var \Gekko\Types\Type
     */
    private $type;

    /**
     * @var bool
     */
    private $autoincrement;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $primaryKey;

    /**
     * @var bool
     */
    private $unique;

    /**
     * @var int
     */
    private $length;

    public function __construct(ModelDescriptor $model, string $name)
    {
        $this->model = $model;
        $this->propertyName = $name;
        $this->columnName = $name;
    }

    public function __get(string $property)
    {
        if (\property_exists($this, $property))
            return $this->{$property};
        throw new \Exception("Unknown property {$property}");
    }

    public function column(string $cname) : self
    {
        $this->columnName = $cname;
        return $this;
    }

    public function type(string $type) : self
    {
        $this->type = \Gekko\Types\Type::new($type);
        return $this;
    }

    public function byte() : self
    {
        $this->type = \Gekko\Types\Byte::instance();
        return $this;
    }

    public function int16() : self
    {
        $this->type = \Gekko\Types\Int16::instance();
        return $this;
    }

    public function int32() : self
    {
        $this->type = \Gekko\Types\Int32::instance();
        return $this;
    }

    public function int64() : self
    {
        $this->type = \Gekko\Types\Int64::instance();
        return $this;
    }

    public function float() : self
    {
        $this->type = \Gekko\Types\Float32::instance();
        return $this;
    }

    public function double() : self
    {
        $this->type = \Gekko\Types\Double64::instance();
        return $this;
    }

    public function decimal() : self
    {
        $this->type = \Gekko\Types\Decimal::instance();
        return $this;
    }

    public function boolean() : self
    {
        $this->type = \Gekko\Types\Boolean::instance();
        return $this;
    }

    public function string() : self
    {
        $this->type = \Gekko\Types\Varchar::instance();
        return $this;
    }

    public function text() : self
    {
        $this->type = \Gekko\Types\Text::instance();
        return $this;
    }

    public function char() : self
    {
        $this->type = \Gekko\Types\Char::instance();
        return $this;
    }

    public function varchar() : self
    {
        $this->type = \Gekko\Types\Varchar::instance();
        return $this;
    }

    public function binary() : self
    {
        $this->type = \Gekko\Types\Binary::instance();
        return $this;
    }

    public function blob() : self
    {
        $this->type = \Gekko\Types\Blob::instance();
        return $this;
    }

    public function dateTime() : self
    {
        $this->type = \Gekko\Types\DateTime::instance();
        return $this;
    }

    public function time() : self
    {
        $this->type = \Gekko\Types\Time::instance();
        return $this;
    }

    public function timestamp() : self
    {
        $this->type = \Gekko\Types\Timestamp::instance();
        return $this;
    }

    public function key() : self
    {
        $this->primaryKey = true;
        return $this;
    }

    public function unique() : self
    {
        $this->unique = true;
        return $this;
    }

    public function autoincrement() : self
    {
        $this->autoincrement = true;
        return $this;
    }

    public function nullable() : self
    {
        $this->nullable = true;
        return $this;
    }

    public function length(int $length) : self
    {
        $this->length = $length;
        return $this;
    }

    public function getJsonDescriptor() : JsonDescriptor
    {
        $d = new JsonDescriptor();

        $d->property("propertyName")->string();
        $d->property("columnName")->string();
        $d->property("type")->type(\Gekko\Types\Type::class);
        $d->property("autoincrement")->boolean();
        $d->property("nullable")->boolean();
        $d->property("primaryKey")->boolean();
        $d->property("unique")->boolean();
        $d->property("length")->int32();

        return $d;
    }
}
