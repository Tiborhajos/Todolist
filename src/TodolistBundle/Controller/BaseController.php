<?php

namespace TodolistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends Controller {

    const ERROR_MESSAGE_HTTP_FORBIDDEN = "U heeft onvoldoende permissies om deze pagina te bekijken.";
    const ERROR_MESSAGE_HTTP_INTERNAL_SERVER_ERROR = "Er is iets mis gegaan. Neem contact op met uw beheerder als dit probleem zich vaker voordoet.";
    const ERROR_MESSAGE_HTTP_UNAUTHORIZED = "Onjuiste gebruikersnaam/wachtwoord opgegeven. Controleer de gegevens en probeer het opnieuw.";

    /**
     * Get the Logger from the service container.
     *
     * @return LoggerInterface
     */
    protected function getLogger() {
        return $this->get('logger');
    }

    /**
     * Return a valid, json-encoded response with proper headers and status code.
     *
     * @param mixed $responseContent Can be any DTO or other value.
     * @return Response
     */
    protected function createAjaxSuccessResponse($responseContent) {
        $content = json_encode($responseContent);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json'); //JQuery parses the content when it is set properly
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($content);

        $this->getLogger()->info("Returning successful JSON response: $content");
        return $response;
    }

    /**
     * Return an error message with proper headers and status code.
     *
     * @param mixed $errorMessage
     * @return Response
     */
    protected function createAjaxErrorResponse($errorMessage) {
        $content = json_encode(array('error' => $errorMessage));

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->setContent($content);

        $this->getLogger()->info("Returning JSON error response: $content");
        return $response;
    }
}