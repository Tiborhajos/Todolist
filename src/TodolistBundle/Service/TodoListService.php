<?php

namespace TodolistBundle\Service;

use TodolistBundle\Entity\TodoItem;
use TodolistBundle\Repository\TodoItemRepository;
use Psr\Log\LoggerInterface as Logger;

class TodoListService {

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var TodoItemRepository
     */
    protected $todoItemRepository;

    /**
     * Create the service and set dependencies (with DI).
     *
     * @param TodoItemRepository $todoItemRepository used to fetch todo items from the database.
     * @param Logger $logger Used for logging errors/info.
     */
    public function __construct(TodoItemRepository $todoItemRepository, Logger $logger) {
        $this->setTodoItemRepository($todoItemRepository);
        $this->setLogger($logger);
    }

    /**
     * Creates a TodoItem from a name, description and priority. Adds a create time and sets the status to incompleted.
     * Sends the TodoItem to the repository to be saved.
     *
     * @param $name
     * @param $description
     * @param $priority
     * @return TodoItem
     */
    public function addTodoItem($name, $description, $priority) {
        $todoItem = new TodoItem();
        $todoItem->setName($name);
        $todoItem->setDescription($description);
        $todoItem->setPriority($priority);
        $todoItem->setCreatedAt(time());
        $todoItem->setCompleted(false);

        $addedTodoItem = $this->getTodoItemRepository()->saveTodoItem($todoItem);

        return $addedTodoItem;
    }

    /**
     * Takes a TodoItemId, calls the repository to see if it exists, and if it does, sets the status to completed.
     * Sends the completed TodoItem to the repository to be saved.
     *
     * @param $id
     * @return TodoItem
     * @throws \DomainException
     */
    public function completeTodoItemById($id) {
        $todoItem = $this->getTodoItemRepository()->findTodoItemById($id);

        if(empty($todoItem)) {
            throw new \DomainException("Cannot update TodoItem. Item with id $id not found");
        }
        $todoItem->setCompleted(true);
        $updatedTodoItem = $this->getTodoItemRepository()->updateTodoItem($todoItem);

        return $updatedTodoItem;
    }

    /**
     * Takes a todoItem id. Calls the repository for it to be removed from the database.
     *
     * @param $todoItemId
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     */
    public function deleteTodoItemById($todoItemId) {
        $deletedTodoItem = $this->getTodoItemRepository()->deleteTodoItemById($todoItemId);

        return $deletedTodoItem;
    }

    /**
     * @return array
     */
    public function getAllCompletedTodoItems() {
        $todoItems = $this->getTodoItemRepository()->findAllCompletedTodoItems();

        return $todoItems;
    }

    /**
     * @return array
     */
    public function getAllIncompletedTodoItems() {
        $todoItems = $this->getTodoItemRepository()->findAllIncompletedTodoItems();

        return $todoItems;
    }

    /**
     * @return TodoItemRepository
     */
    protected function getTodoItemRepository() {
        return $this->todoItemRepository;
    }

    /**
     * @param TodoItemRepository $todoItemRepository
     */
    protected function setTodoItemRepository(TodoItemRepository $todoItemRepository) {
        $this->todoItemRepository = $todoItemRepository;
    }

    /**
     * @return Logger
     */
    protected function getLogger() {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    protected function setLogger($logger) {
        $this->logger = $logger;
    }

}
