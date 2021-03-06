<?= '<?php' ?>

/*
 * (c) Leonardo Brugnara
 *
 * Full copyright and license information in LICENSE file.
 *
 * -------------------------------------------------------
 *
 * WARNING: This is an autogenerated file, you should NOT edit this file as all
 *  the changes will be lost on file regeneration.
 *
 */

namespace <?= $model->namespace ?>\Descriptors;

class <?= $model->className ?>Descriptor extends \Gekko\Model\ModelDescriptor
{
    public function __construct()
    {
        parent::__construct("<?= $model->className ?>");

        <?= $model->namespace !== null ? "\$this->namespace(\"{$model->namespace}\");" : ""; ?>


        <?= $model->className !== $model->tableName ? "\$this->tableName(\"{$model->tableName}\");" : ""; ?>


        // PROPERTY DESCRIPTORS
<?php foreach ($model->properties as $property): ?>
        <?= $this->getPropertyDescriptorDefinition($property, 4)?>


<?php endforeach; ?>

        // RELATIONSHIPS
<?php foreach ($model->relationships as $relation): ?>
<?php if ($relation->kind == \Gekko\Model\ModelRelationDescriptor::BelongsTo): ?>
        <?= "\$this->belongsToClass(\"{$relation->foreignModel}\")" ?>

        <?php foreach ($relation->properties as $property): ?>
        <?= "->on(\"$property->local\", \"{$property->foreign}\")" ?>
        <?php endforeach; ?>

                <?= ($relation->name !== null ? "->asProperty(\"{$relation->name}\")" : "") . ";" ?>

<?php elseif ($relation->kind == \Gekko\Model\ModelRelationDescriptor::HasOne): ?>
        <?= "\$this->hasOneOfClass(\"{$relation->foreignModel}\")" ?>

        <?php foreach ($relation->properties as $property): ?>
        <?= "->on(\"$property->local\", \"{$property->foreign}\")" ?>
        <?php endforeach; ?>

                <?= ($relation->name !== null ? "->asProperty(\"{$relation->name}\")" : "") . ";" ?>

<?php elseif ($relation->kind == \Gekko\Model\ModelRelationDescriptor::HasMany): ?>
        <?= "\$this->hasManyOfClass(\"{$relation->foreignModel}\")" ?>

        <?php foreach ($relation->properties as $property): ?>
        <?= "->on(\"$property->local\", \"{$property->foreign}\")" ?>
        <?php endforeach; ?>

                <?= ($relation->name !== null ? "->asProperty(\"{$relation->name}\")" : "") . ";" ?>

<?php endif; ?>

<?php endforeach; ?>

    }
}