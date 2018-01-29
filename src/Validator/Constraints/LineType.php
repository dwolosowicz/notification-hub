<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LineType extends Constraint
{
    public $message = 'The line type of "{{ value }}" doesn\'t match any of the supported types ({{ supportedValues }}).';
    public $noLineTypesEnabledMessage = 'No line types are enabled. Contact the administrator.';
}
