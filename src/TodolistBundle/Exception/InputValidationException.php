<?php

namespace TodolistBundle\Exception;

/**
 * Thrown when service method parameters are missing or invalid.
 */
class InputValidationException extends \DomainException {

    const DEFAULT_ERROR_MESSAGE = "Missing or invalid input parameters.";

    public function __construct($message = null, $code = null, $previous = null) {
        if (empty($message)) {
            $message = self::DEFAULT_ERROR_MESSAGE;
        }
        parent::__construct($message, $code, $previous);
    }

}
