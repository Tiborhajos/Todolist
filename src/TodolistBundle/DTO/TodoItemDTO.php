<?php

namespace TodolistBundle\DTO;

use TodolistBundle\Entity\TodoItem;

/**
 * DTO used to send a to do item to the view.
 *
 * Class TodoItemDTO
 * @package TodolistBundle\DTO
 */
class TodoItemDTO {

    public $id;
    public $createdAt;
    public $name;
    public $description;
    public $priority;
    public $completed;

    public function __construct(TodoItem $todoItem) {
        $this->id = $todoItem->getId();
        $this->createdAt = $todoItem->getCreatedAt();
        $this->name = $todoItem->getName();
        $this->description = $todoItem->getDescription();
        $this->priority = $todoItem->getPriority();
        $this->completed = $todoItem->isCompleted();
    }

}
