<?php

function to_getter_setter_name(string $propertyName) : string
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
