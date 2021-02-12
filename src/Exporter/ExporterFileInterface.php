<?php


namespace WebEtDesign\RgpdBundle\Exporter;


use ReflectionProperty;

interface ExporterFileInterface
{
    public function doExport(string $tmpDir, $object, ?ReflectionProperty $property = null);
}
