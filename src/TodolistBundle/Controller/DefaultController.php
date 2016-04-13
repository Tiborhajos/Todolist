<?php

namespace TodolistBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TodolistBundle\Controller\BaseController;
use TodolistBundle\Exception\InputValidationException;
use TodolistBundle\DTO\TodoItemDTO;
use \Exception;

/**
 * Class DefaultController, controls CRUD of the to do bundle page and renders templates.
 * @package TodolistBundle\Controller
 */
class DefaultController extends BaseController {

    /**
     * Renders the add todo item page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderAddTodoItemPageAction() {
        return $this->render('TodolistBundle::add-todo-item.html.twig');
    }

    /**
     * Create a new todo item.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTodoItemAction(Request $request) {
        $requestParams = $request->request->all();

        //do some basic validation
        if (!empty($requestParams['name']) && !empty($requestParams['description']) && !empty($requestParams['priority'])) {
            $name = $requestParams['name'];
            $description = $requestParams['description'];
            $priority = $requestParams['priority'];
        } else {
            throw new InputValidationException();
        }

        try {
            $addedTodoItem = $this->getTodoItemService()->addTodoItem($name, $description, $priority);
            $todoItemDTO = $this->createTodoItemDTO($addedTodoItem);

            $response = $this->createAjaxSuccessResponse($todoItemDTO);
        } catch (Exception $ex) {
            $response = $this->createAjaxErrorResponse('Failed create the item. Please refresh the page and try again');
        }

        return $response;
    }

    /**
     * Delete a todo item based on id.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTodoItemAction(Request $request) {
        $requestParams = $request->request->all();

        //do some basic validation
        if (!empty($requestParams['todoItemId'])) {
            $todoItemId = $requestParams['todoItemId'];
        } else {
            throw new InputValidationException();
        }

        try {
            $deletedTodoItem = $this->getTodoItemService()->deleteTodoItemById($todoItemId);
            $response = $this->createAjaxSuccessResponse($deletedTodoItem);
        } catch (Exception $ex) {
            $response = $this->createAjaxErrorResponse('Failed to delete the item.');
        }

        return $response;
    }

    /**
     * Marks a todo item as completed.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completeTodoItemAction(Request $request) {
        $requestParams = $request->request->all();

        //do some basic validation
        if (!empty($requestParams['todoItemId'])) {
            $todoItemId = $requestParams['todoItemId'];
        } else {
            throw new InputValidationException();
        }

        try {
            $updatedToDoItem = $this->getTodoItemService()->completeTodoItemById($todoItemId);
            $response = $this->createAjaxSuccessResponse($updatedToDoItem);
        } catch (Exception $ex) {
            $response = $this->createAjaxErrorResponse('Failed marking the item as complete.');
        }

        return $response;
    }

    /**
     * Gets all todo items that are not yet completed (i.e. the todo list).
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllIncompletedTodoItemsAction() {
        $todoItems = $this->getTodoItemService()->getAllIncompletedTodoItems();

        $todoItemDTOs = $this->createTodoItemDTOs($todoItems);

        return $this->render('TodolistBundle::todo-list.html.twig', array('todoItemDTOs' => $todoItemDTOs));
    }

    /**
     * Gets all completed todo items.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllCompletedTodoItemsAction() {
        $todoItems = $this->getTodoItemService()->getAllCompletedTodoItems();

        $todoItemDTOs = $this->createTodoItemDTOs($todoItems);

        return $this->render('TodolistBundle::completed-todo-items.html.twig', array('todoItemDTOs' => $todoItemDTOs));
    }

    private function createTodoItemDTOs(array $todoItems) {
        $todoItemDTOs = [];
        foreach ($todoItems as $todoItem) {
            $todoItemDTOs[] = new TodoItemDTO($todoItem);
        }

        return $todoItemDTOs;
    }

    private function createTodoItemDTO($todoItem) {
        return new TodoItemDTO($todoItem);
    }

    private function getTodoItemService() {
        return $this->get('app.todo_item_service');
    }
}
