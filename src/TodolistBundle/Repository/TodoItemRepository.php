<?php

namespace TodolistBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TodolistBundle\Entity\TodoItem;

/**
 * TodoItem repository. Works with DoctineORM.
 */
class TodoItemRepository extends EntityRepository {

    /**
     * Saves a new TodoItem.
     *
     * @param TodoItem $item
     * @return TodoItem
     */
    public function saveTodoItem(TodoItem $item) {
        $this->_em->persist($item);
        $this->_em->flush();
        return $item;
    }

    /**
     * Updates a TodoItem and returns the updated object.
     *
     * @param TodoItem $item
     * @return TodoItem
     */
    public function updateTodoItem(TodoItem $item) {
        $updatedItem = $this->_em->merge($item);
        $this->_em->flush();
        return $updatedItem;
    }

    /**
     * Finds a TodoItem by id and returns it.
     *
     * @param integer $id
     * @return null|TodoItem
     */
    public function findTodoItemById($id) {
        return $this->_em->getRepository('TodolistBundle\Entity\TodoItem')->findOneBy(array('id' => $id));
    }

    /**
     * Deletes a TodoItem and returns the deleted object.
     *
     * @param $itemId
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteTodoItemById($itemId) {
        $entity = $this->_em->getReference('TodolistBundle\Entity\TodoItem', $itemId);
        $this->_em->remove($entity);
        $this->_em->flush();
        return $entity;
    }

    /**
     * Finds all todoItems that are completed.
     *
     * @return array
     */
    public function findAllCompletedTodoItems() {
        return $this->_em->getRepository('TodolistBundle\Entity\TodoItem')->findBy(array('completed' => true));
    }

    /**
     * Finds all todoItems that are uncompleted.
     *
     * @return array
     */
    public function findAllIncompletedTodoItems() {
        return $this->_em->getRepository('TodolistBundle\Entity\TodoItem')->findBy(array('completed' => false));
    }
    
}
