<?php

function sanitize_name(string $propertyName) : string
{
    $length = strlen($propertyName);
    $pos = -1;
    while (($pos = strpos($propertyName, "_", $pos+1)) !== false)
    {
        if ($pos == $length - 1)
            break;

        $propertyName[$pos+1] = strtoupper($propertyName[$pos+1]);
    }

    $propertyName = str_replace("_", "", $propertyName);

    $propertyName[0] = strtoupper($propertyName[0]);

    return $propertyName;
}

function to_setter_name(\Gekko\Model\PropertyDescriptor $property) : string
{
    $name = sanitize_name($property->propertyName);

    return "set{$name}";
}

function to_getter_name(\Gekko\Model\PropertyDescriptor $property) : string
{
    $name = sanitize_name($property->propertyName);

    if ($property->type === \Gekko\Types\Boolean::instance())
        return "is{$name}";

    return "get{$name}";
}
