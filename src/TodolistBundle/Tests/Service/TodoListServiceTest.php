<?php

namespace TodolistBundle\Tests\Service;

use TodolistBundle\Service\TodoListService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TodolistBundle\Entity\TodoItem;

class TodoListServiceTest extends WebTestCase {

    private $todoItemNamespace = 'TodolistBundle\Entity\TodoItem';

    /**
     * @dataProvider serviceDependencyProvider
     * @test
     */
    public function canFindAllCompletedTodoItems($mockRepo, $mockLogger) {
        $todoItem1 = $this->getTodoItemFixture();
        $todoItem2 = $this->getTodoItemFixture();
        $todoItem1->setCompleted(true);
        $todoItem2->setCompleted(true);
        
        $mockRepo->method('findAllCompletedTodoItems')
                ->willReturn(array($todoItem1, $todoItem2));

        $service = new TodoListService($mockRepo, $mockLogger);
        $result = $service->getAllCompletedTodoItems();

        $this->assertInstanceOf($this->todoItemNamespace, $result[0]);
        $this->assertGreaterThanOrEqual(2, count($result));
        //$this->assertFalse($result[0]->isCompleted());
    }

    /**
     * @dataProvider serviceDependencyProvider
     * @test
     */
    public function canFindAllIncompletedTodoItems($mockRepo, $mockLogger) {
        $todoItem1 = $this->getTodoItemFixture();
        $todoItem2 = $this->getTodoItemFixture();
    
        $mockRepo->method('findAllIncompletedTodoItems')
                ->willReturn(array($todoItem1, $todoItem2));

        $service = new TodoListService($mockRepo, $mockLogger);
        $result = $service->getAllIncompletedTodoItems();

        $this->assertInstanceOf($this->todoItemNamespace, $result[0]);
        $this->assertGreaterThanOrEqual(2, count($result));
        $this->assertFalse($result[0]->isCompleted());
    }

    /**
     * @dataProvider serviceDependencyProvider
     * @test
     */
    public function canDeleteTodoItemById($mockRepo, $mockLogger) {
     
        $this->markTestIncomplete();
        $mockRepo->method('deleteTodoItemById')
                ->willReturn(null);

        $service = new TodoListService($mockRepo, $mockLogger);
        $result = $service->deleteTodoItemById(1100);
    }

    /**
     * @dataProvider serviceDependencyProvider
     * @test
     */
    public function canAddTodoItem($mockRepo, $mockLogger) {
        $todoItem = $this->getTodoItemFixture();

        $mockRepo->method('saveTodoItem')
                ->willReturn($todoItem);

        $name = 'Test todo item';
        $description = 'this item was generated as part of an automated test';
        $priority = 1;

        $service = new TodoListService($mockRepo, $mockLogger);
        $item = $service->addTodoItem($name, $description, $priority);

        $this->assertInstanceOf($this->todoItemNamespace, $item);
        $this->assertFalse($item->isCompleted());
    }

    /**
     * @dataProvider serviceDependencyProvider
     * @test
     */
    public function canCompleteTodoItemById($mockRepo, $mockLogger) {
        $this->markTestIncomplete();
        
        $todoItem = $this->getTodoItemFixture();

        $mockRepo->method('findTodoItemById')
                ->willReturn($todoItem);
        $mockRepo->method('updateTodoItem')
                ->willReturn($todoItem);

        $service = new TodoListService($mockRepo, $mockLogger);
        $item = $service->completeTodoItemById(1100);

        $this->assertInstanceOf($this->todoItemNamespace, $item);
        $this->assertTrue($item->isCompleted());
    }

    /**
     * Returns mock objects for the required service dependencies.
     *
     * @return array
     */
    public function serviceDependencyProvider() {
        $mockRepo = $this->getMockBuilder('TodolistBundle\Repository\TodoItemRepository')
                ->disableOriginalConstructor()
                ->getMock();
        $mockLogger = $this->getMockBuilder('Psr\Log\LoggerInterface')
                ->disableOriginalConstructor()
                ->getMock();

        return array(array($mockRepo, $mockLogger));
    }
    
    /**
     * Create a new TodoItem fixture.
     *
     * @return TodoItem
     */
    protected function getTodoItemFixture() {
        $todoItem = new TodoItem();
        $todoItem->setId(1100);
        $todoItem->setName('Test todo item');
        $todoItem->setDescription('this item was generated as part of an automated test');
        $todoItem->setPriority(1);
        $todoItem->setCreatedAt(time());
        $todoItem->setCompleted(false);

        return $todoItem;
    }

}
