<?php

namespace TodolistBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class DefaultControllerTest extends WebTestCase {

    /**
     * @test
     */
    public function canAddTodoItem() {
        $requestParams = array(
            'name' => 'Integration test add todo item',
            'description' => 'this item was generated as part of an automated integration test',
            'priority' => 1
        );

        $client = $this->sendRequest('POST', 'app.route.create_todo_item', $requestParams);

        $dto = $this->assertAjaxRequestWasSuccessfull($client);
        $this->assertAttributeNotEmpty('id', $dto);
        return $dto->id;
    }

    /**
     * @test
     * @depends canAddTodoItem
     */
    public function canCompleteTodoItem($itemId) {
        $requestParams = array('todoItemId' => $itemId);
        $client = $this->sendRequest('POST', 'app.route.complete_todo_item', $requestParams);

        $this->assertAjaxRequestWasSuccessfull($client);
        return $itemId;
    }

    /**
     * @test
     * @depends canCompleteTodoItem
     */
    public function canDeleteTodoItem($itemId) {
        $requestParams = array('todoItemId' => $itemId);

        $client = $this->sendRequest('POST', 'app.route.delete_todo_item', $requestParams);

        $this->assertAjaxRequestWasSuccessfull($client);
    }

    /**
     * @test
     */
    public function canGetAllIncompletedTodoItems() {
        
    }

    /**
     * @test
     */
    public function canGetAllCompletedTodoItems() {
        
    }

    /**
     * @param string $httpMethod Usually POST or GET
     * @param string $routeConfigName
     * @param array $params
     * @return Symfony\Bundle\FrameworkBundle\Client
     * 
     */
    protected function sendRequest($httpMethod, $routeConfigName, array $params = array()) {
        $client = static::createClient();
        $uri = $client->getContainer()->get('router')->generate($routeConfigName, array(), false);
        $client->request($httpMethod, $uri, $params);

        return $client;
    }

    /**
     * Evaluate the success of a response by checking it for correct headers and presence of result content.
     *  
     * @param Symfony\Bundle\FrameworkBundle\Client $client
     * @return string Page content.
     */
    protected function assertPageRequestWasSuccessfull(Client $client) {
        $isHtml = $client->getResponse()->headers->contains("content-type", "text/html; charset=UTF-8");
        $statusOk = $client->getResponse()->isSuccessful();

        $this->assertTrue($statusOk);
        $this->assertTrue($isHtml);

        $pageContent = $client->getResponse()->getContent();
        $this->assertNotEmpty($pageContent);
        return $pageContent;
    }

    /**
     * Evaluate the success of a response by checking it for correct headers and presence of result content.
     *  
     * @param Symfony\Bundle\FrameworkBundle\Client $client
     */
    protected function assertAjaxRequestWasSuccessfull(Client $client) {
        $isJson = $client->getResponse()->headers->contains("content-type", "application/json");
        $statusOk = $client->getResponse()->isSuccessful();
        $content = $client->getResponse()->getContent();

        $this->assertTrue($statusOk);
        $this->assertTrue($isJson);

        $this->assertNotEmpty($content);
        $decoded = json_decode($content);
        return $decoded;
    }

    /**
     * Evaluate the failure of a response by checking it for correct headers and presence of error message content.
     *
     * @param Symfony\Bundle\FrameworkBundle\Client $client
     */
    protected function assertAjaxRequestWasNotSuccessfull(Client $client) {
        $isJson = $client->getResponse()->headers->contains("content-type", "application/json");
        $statusNotOk = $client->getResponse()->isServerError();
        $content = $client->getResponse()->getContent();

        $this->assertTrue($statusNotOk);
        $this->assertTrue($isJson);

        $this->assertNotEmpty($content);
        $decoded = json_decode($content);
        return $decoded;
    }

}
