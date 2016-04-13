<?php

namespace TodolistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TodoItem entity.
 * 
 * @ORM\Table(name="todo_item")
 * @ORM\Entity(repositoryClass="TodolistBundle\Repository\TodoItemRepository")
 */
class TodoItem {

    /**
     * Item priority options.
     */
    const PRIORITY_HIGH = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_LOW = 3;

    /**
     * @ORM\Id 
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     * 
     * @var int
     */
    private $id;

    /**
     * Unix timestamp on creation of a database record.
     * 
     * @ORM\Column(name="created_at", nullable=true, type="integer")
     * @var int
     */
    private $createdAt;

    /**
     * Name of this todo item.
     * 
     * @ORM\Column(name="name", nullable=false)
     * @var string
     */
    private $name;

    /**
     * Description of this todo item.
     * 
     * @ORM\Column(name="description", nullable=true, type="string", length=250)
     * @var string
     */
    private $description;

    /**
     * Priority of this todo item. Must be one of class constants.
     * 
     * @ORM\Column(name="priority", nullable=true, type="integer")
     * @var int
     */
    private $priority;

    /**
     * If the item has been marked as completed.
     * 
     * @ORM\Column(name="completed", nullable=true, type="boolean", options={"default":false})
     * @var boolean
     */
    private $completed;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @throws \InvalidArgumentException If given param is not one of defined class constants.
     */
    public function setPriority($priority) {
        switch ($priority) {
            case self::PRIORITY_HIGH:
            case self::PRIORITY_MEDIUM:
            case self::PRIORITY_LOW:
                $this->priority = $priority;
                break;
            default:
                throw new \InvalidArgumentException("Priority must be one of defined class constants.");
        }
    }

    /**
     * @return boolean
     */
    public function isCompleted() {
        return $this->completed;
    }

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed) {
        $this->completed = $completed;
    }
}
