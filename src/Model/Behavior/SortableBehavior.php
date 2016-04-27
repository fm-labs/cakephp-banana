<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Inflector;

/**
 * Class SortableBehavior
 *
 * @package Banana\Model\Behavior
 * @see http://book.cakephp.org/3.0/en/orm/behaviors.html
 */
class SortableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'field' => 'pos', // the sort position field
        'scope' => [], // sorting scope
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
    }

    /**
     *
     * @param Event $event The event
     * @param Entity $entity The entity
     * @param \ArrayObject $options
     * @param $operation
     * @return void
     */
    public function beforeRules(Event $event, Entity $entity, ArrayObject $options, $operation)
    {
    }


    /**
     * Automatically slug when saving.
     *
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
    }

    public function findSorted(Query $query, array $options = [])
    {
        //$options += ['scope' => []];
        //$scope = ($options['scope']) ? $options['scope'] : $this->config('scope');
        $scope = (array) $this->config('scope');
        array_unshift($scope, $this->config('field'));

        $query->order($scope);
        return $query;
    }

    public function moveUp(EntityInterface $node, $number = 1)
    {
        $delta = max(0, $number);
        return $this->_table->connection()->transactional(function () use ($node, $delta) {
            //$this->_ensureFields($node);
            return $this->_moveByDelta($node, $delta);
        });
    }

    public function moveDown(EntityInterface $node, $number = 1)
    {
        $delta = max(0, $number) * -1;
        return $this->_table->connection()->transactional(function () use ($node, $delta) {
            //$this->_ensureFields($node);
            return $this->_moveByDelta($node, $delta);
        });
    }

    public function moveTop(EntityInterface $node)
    {
        return $this->_table->connection()->transactional(function () use ($node) {
            //$this->_ensureFields($node);
            return $this->_moveToPosition($node, 1);
        });
    }

    public function moveBottom(EntityInterface $node)
    {
        return $this->_table->connection()->transactional(function () use ($node) {
            //$this->_ensureFields($node);
            return $this->_moveToPosition($node, $this->_getMaxPos());
        });
    }

    public function moveAfter(EntityInterface $node, $targetId)
    {
        return $this->_table->connection()->transactional(function () use ($node, $targetId) {
            //$this->_ensureFields($node);
            $targetNode = $this->_table->get($targetId);
            $targetPos = $targetNode->pos;
            //debug("Move after $targetId which is will be $targetPos");
            return $this->_moveToPosition($node, $targetPos);
        });
    }

    public function moveBefore(EntityInterface $node, $targetId)
    {
        return $this->_table->connection()->transactional(function () use ($node, $targetId) {
            //$this->_ensureFields($node);
            $targetNode = $this->_table->get($targetId);
            $maxPos = $this->_getMaxPos() - 1;
            $targetPos = max(1, min($targetNode->pos, $maxPos));
            //debug("Move before $targetId which will be Pos $targetPos | Max $maxPos");
            return $this->_moveToPosition($node, $targetPos);
        });
        return $node;
    }

    protected function _moveToPosition(EntityInterface $node, $newPos)
    {
        $sortField = $this->_config['field'];
        $pos = $node->get($sortField);
        $delta = $pos - $newPos;
        return $this->_moveByDelta($node, $delta);
    }

    protected function _moveByDelta(EntityInterface $node, $delta)
    {
        $sortField = $this->_config['field'];
        $pos = $node->get($sortField);
        $newPos = $pos - $delta;

        //debug("Move Pos $pos by delta $delta -> New position will be: $newPos");

        if ($delta == 0) {
            return $node;
        }
        
        $query = $this->_scoped($this->_table->query());
        $exp = $query->newExpr();
        $shift = 1;

        if ($delta < 0) {
            // move down
            $max = $this->_getMaxPos();
            $newPos = min($newPos, $max);

            $movement = clone $exp;
            $movement->add($sortField)->add("{$shift}")->type("-");

            $cond1 = clone $exp;
            $cond1->add($sortField)->add("{$pos}")->type(">");

            $cond2 = clone $exp;
            $cond2->add($sortField)->add("{$newPos}")->type("<=");

        } elseif ($delta > 0) {
            // move up
            $newPos = max(0, $newPos);

            $movement = clone $exp;
            $movement->add($sortField)->add("{$shift}")->type("+");

            $cond1 = clone $exp;
            $cond1->add($sortField)->add("{$pos}")->type("<");

            $cond2 = clone $exp;
            $cond2->add($sortField)->add("{$newPos}")->type(">=");
        }

        $where = clone $exp;
        $where->add($cond1)->add($cond2)->type("AND");

        $query->update()
            ->set($exp->eq($sortField, $movement))
            ->where($where);

        $query->execute()->closeCursor();

        $node->set($sortField, $newPos);
        return $this->_table->save($node);
    }

    protected function _getMaxPos()
    {
        $sortField = $this->_config['field'];
        $res = $this->_table->find()->select([$sortField])->hydrate(false)->orderDesc($sortField)->first();
        return $res[$sortField];
    }

    protected function _scoped(Query $query) {

        $scope = $this->_config['scope'];

        //@TODO Implement me

        return $query;
    }
}
