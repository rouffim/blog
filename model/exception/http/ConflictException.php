<?php
namespace model\exception\http;

class ConflictException extends HttpException {
    /**
     * ConflictException constructor.
     * @param string $message
     */
    public function __construct(string $message = '') {
        parent::__construct($message, 409);
    }
}
