<?php

namespace App\Filter;

use App\Annotation\UserAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
    /** @var Reader */
    protected $reader;

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        $userAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            UserAware::class
        );

        if (!$userAware) {
            return '';
        }

        $fieldName = $userAware->userFieldName;

        try {
            $userId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            return '';
        }

        if (empty($fieldName) || empty($userId)) {
            return '';
        }

        return sprintf("%s.%s = %s", $targetTableAlias, $fieldName, $userId);
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}