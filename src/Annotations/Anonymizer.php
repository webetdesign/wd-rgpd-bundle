<?php


namespace WebEtDesign\RgpdBundle\Annotations;

/**
 * Class Anonymizer
 * @package WebEtDesign\RgpdBundle\Annotations
 *
 * @Annotation()
 * @Target({"PROPERTY"})
 */
class Anonymizer
{
    const TYPE_STRING     = 'TYPE_STRING';
    const TYPE_EMAIL      = 'TYPE_EMAIL';
    const TYPE_UNIQ       = 'TYPE_UNIQ';
    const TYPE_BOOL_TRUE  = 'TYPE_BOOL_TRUE';
    const TYPE_BOOL_FALSE = 'TYPE_BOOL_FALSE';

    const ACTION_SET_NULL = 'SET_NULL';
    const ACTION_CASCADE  = 'CASCADE';


    private string $type;
    private string $action;

    /**
     * @inheritDoc
     */
    public function __construct(array $values)
    {
        $this->type   = $values['type'] ?? self::TYPE_STRING;
        $this->action = $values['action'] ?? self::ACTION_SET_NULL;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Anonymizer
     */
    public function setType(string $type): Anonymizer
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Anonymizer
     */
    public function setAction(string $action): Anonymizer
    {
        $this->action = $action;
        return $this;
    }
}
