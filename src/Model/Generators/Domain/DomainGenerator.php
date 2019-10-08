<?php
/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 */

namespace Gekko\Model\Generators\Domain;

use \Gekko\Types\Type;
use \Gekko\Helpers\Utils;
use \Gekko\Model\ModelDescriptor;
use \Gekko\Collections\Collection;
use \Gekko\Model\PackageDescriptor;
use \Gekko\Model\PropertyDescriptor;
use \Gekko\Model\Generators\IGenerator;
use function \file_exists;
use function \mkdir;

class DomainGenerator implements IGenerator
{
    const GEN_CLASS = 1;
    const GEN_TRAIT = 2;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $config;


    public function __construct(int $config, string $path)
    {
        $this->path = $path;
        $this->config = $config;

        if (!file_exists($this->path))
            mkdir($this->path, 0777, true);

        if (!\is_writeable($this->path))
            throw new \Error("Cannot write to directory {$this->path}");
    }

    public function generate(PackageDescriptor $package) : void
    {
        $this->generateModels($package);
        $this->generateModelDescriptor($package);
    }

    private function generateModels(PackageDescriptor $package) : void
    {
        foreach ($package->models as $model)
        {
            $modelns = $model->namespace;

            if ($package->virtual)
                $modelns = \str_replace($package->namespace, "", $modelns);

            $ns = \explode("\\", $modelns);
            $path = Utils::path($this->path, ...$ns);

            if (!file_exists($path))
                mkdir($path, 0777, true);

            $filename = Utils::path($path, $model->className);

            if ($this->config & self::GEN_CLASS)
                \file_put_contents($filename . '.php', $this->process("Class", $package, $model));

            if ($this->config & self::GEN_TRAIT)
            {
                \file_put_contents($filename . 'Trait.php', $this->process("Trait", $package, $model));

                if (!file_exists($filename . '.php'))
                    \file_put_contents($filename . '.php', $this->process("TraitUsage", $package, $model));
            }
        }
    }

    private function process(string $template, PackageDescriptor $package, ModelDescriptor $model) : string
    {
        ob_start();
        include "Templates/{$template}.php";
        return \ob_get_clean();
    }

    private function generateModelDescriptor(PackageDescriptor $package) : void
    {
        foreach ($package->models as $model)
        {
            $modelns = $model->namespace;

            if ($package->virtual)
                $modelns = \str_replace($package->namespace, "", $modelns);

            $ns = \explode("\\", $modelns);
            array_push($ns, "Descriptors");

            $path = Utils::path($this->path, ...$ns);

            if (!file_exists($path))
                mkdir($path, 0777, true);

            $file = Utils::path($path, $model->className . 'Descriptor.php');

            \file_put_contents($file, $this->process("ModelDescriptor", $package, $model));
        }
    }

    private function getPropertyDescriptorDefinition(PropertyDescriptor $property, int $tabs = 4)
    {
        $separator = "\n" . \str_repeat("    ", $tabs);

        $definition = "\$this->property(\"{$property->propertyName}\")";

        if ($property->columnName !== $property->propertyName)
            $definition .= "{$separator}->column(\"{$property->columnName}\")";
        
        if ($property->type !== null)
            $definition .= "{$separator}->type(\\{$property->type}::class)";

        if ($property->autoincrement)
            $definition .= "{$separator}->autoincrement()";

        if ($property->nullable)
            $definition .= "{$separator}->nullable()";

        if ($property->primaryKey)
            $definition .= "{$separator}->key()";

        if ($property->unique)
            $definition .= "{$separator}->unique()";

        if ($property->length !== null)
            $definition .= "{$separator}->length({$property->length})";

        if ($property->is_array)
            $definition .= "{$separator}->array()";

        $definition .= ";";

        return $definition;
    }

    private function getRelationships(array $relationships, int $kind) : array
    {
        return Collection::of($relationships)
                ->where(function ($r) use ($kind) { return $r->kind == $kind; })
                ->toArray();
    }

    public function mapType(PropertyDescriptor $property) : string
    {
        return $property->type;
    }
}