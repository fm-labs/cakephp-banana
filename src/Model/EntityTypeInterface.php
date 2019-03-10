<?php

namespace Banana\Model;

use Cake\Datasource\EntityInterface;

/**
 * Interface EntityTypeInterface
 *
 * The EntityTypeHandlerInterface expects an EntityTypeInterface instance when loading an EntityTypeHandler
 *
 * @package Banana\Model
 */
interface EntityTypeInterface
{
    /**
     * @param EntityInterface $entity
     * @return mixed
     */
    public function setEntity(EntityInterface $entity);
}
