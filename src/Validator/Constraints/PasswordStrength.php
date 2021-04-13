<?php


namespace WebEtDesign\RgpdBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    public $tooShortMessage = 'password_strength.length.error';
    public $message = 'password_strength.sensibility.error';
    public $minLength = 6;
    public $minStrength;
    public $unicodeEquality = false;

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'minStrength';
    }

    public function getRequiredOptions()
    {
        return ['minStrength'];
    }

    public function getTargets()
    {
        return array(self::CLASS_CONSTRAINT, self::PROPERTY_CONSTRAINT);
    }
}
