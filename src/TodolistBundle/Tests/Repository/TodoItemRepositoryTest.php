<?php

namespace TodolistBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TodolistBundle\Entity\TodoItem;

class TodoItemRepositoryTest extends WebTestCase {

    private $repository;

    /**
     * @test
     */
    public function canSaveTodoItem() {
        $item = new TodoItem();
        $item->setName("test save");
        $item->setDescription("test description");

        $savedItem = $this->repository->saveTodoItem($item);
        $this->assertNotNull($savedItem);
        $this->assertInstanceOf('TodolistBundle\Entity\TodoItem', $savedItem);

        return $savedItem->getId();
    }

    /**
     * @test
     * @depends canSaveTodoItem
     */
    public function canFindTodoItemById($itemId) {
        $item = $this->repository->findTodoItemById($itemId);
        $this->assertInstanceOf('TodolistBundle\Entity\TodoItem', $item);
        $this->assertSame("test description", $item->getDescription());
        return $item;
    }

    /**
     * @test
     * @depends canFindTodoItemById 
     */
    public function canUpdateTodoItem($item) {
        $name = "test update";
        $item->setName($name);
        $updatedItem = $this->repository->updateTodoItem($item);
        $this->assertSame($name, $updatedItem->getName());
        $this->assertSame("test description", $updatedItem->getDescription());
        return $updatedItem->getId();
    }

    /**
     * @test
     * @depends canUpdateTodoItem
     */
    public function canDeleteTodoItemById($itemId) {
        $this->repository->deleteTodoItemById($itemId);
        return $itemId;
    }

    /**
     * @test
     * @depends canDeleteTodoItemById
     */
    public function cannotFindTodoItemById($itemId) {
        $item = $this->repository->findTodoItemById($itemId);
        $this->assertNull($item);
    }

    /**
     * Get repository from service container (with all dependencies already set).
     */
    protected function setUp() {
        $kernel = self::createKernel();
        $kernel->boot();
        $this->repository = $kernel->getContainer()->get('app.doctrine_todo_item_repository');
    }

    /**
     * Shut down test kernel.
     */
    protected function tearDown() {
        self::ensureKernelShutdown();
    }

}
