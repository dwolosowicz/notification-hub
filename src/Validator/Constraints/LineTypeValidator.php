<?php

namespace App\Validator\Constraints;

use App\Lines\Model\LineConfig;
use App\Lines\TypesProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LineTypeValidator extends ConstraintValidator
{
    /** @var TypesProvider */
    protected $lineTypesProvider;

    public function __construct(TypesProvider $lineTypesProvider)
    {
        $this->lineTypesProvider = $lineTypesProvider;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint LineType */

        $enabledLineTypes = array_filter($this->lineTypesProvider->getTypes(), function (LineConfig $lineConfig) {
            return $lineConfig->isEnabled();
        });

        if (count($enabledLineTypes) == 0) {
            $this->context->buildViolation($constraint->noLineTypesEnabledMessage)
                ->addViolation();

            return;
        }

        $enabledLineTypesCodes = array_map(function(LineConfig $lineConfig) {
            return $lineConfig->getCode();
        }, $enabledLineTypes);

        if (in_array($value, $enabledLineTypesCodes)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setParameter('{{ supportedValues }}', implode(', ', $enabledLineTypesCodes))
            ->addViolation();
    }
}
