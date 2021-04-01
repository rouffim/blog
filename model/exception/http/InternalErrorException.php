<?php
namespace model\exception\http;

class InternalErrorException extends HttpException {
    /**
     * InternalErrorException constructor.
     * @param string $message
     */
    public function __construct(string $message = '') {
        parent::__construct($message, 500);
    }
}
