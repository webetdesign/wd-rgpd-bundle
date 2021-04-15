<?php

namespace WebEtDesign\RgpdBundle\Anonymizer;

use ReflectionProperty;
use Vich\UploaderBundle\Handler\UploadHandler;

class AnonymizerVich implements AnonymizerFileInterface
{
    /**
     * @var UploadHandler
     */
    private UploadHandler $uploadHandler;

    /**
     * AnonymizerVich constructor.
     * @param UploadHandler $uploadHandler
     */
    public function __construct(UploadHandler $uploadHandler)
    {
        $this->uploadHandler = $uploadHandler;
    }

    public function doAnonymize($object, ?ReflectionProperty $property = null)
    {
        $this->uploadHandler->remove($object, $property->getName());
        return null;
    }
}
