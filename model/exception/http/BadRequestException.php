<?php
namespace model\exception\http;

class BadRequestException extends HttpException {
    /**
     * BadRequestException constructor.
     * @param string $message
     */
    public function __construct(string $message) {
        parent::__construct($message, 400);
    }
}
