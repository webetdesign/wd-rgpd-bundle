<?php

namespace WebEtDesign\RgpdBundle\Anonymizer;

use ReflectionProperty;

interface AnonymizerFileInterface
{
    public function doAnonymize($object, ?ReflectionProperty $property = null);
}
