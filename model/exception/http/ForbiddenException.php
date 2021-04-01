<?php
namespace model\exception\http;

class ForbiddenException extends HttpException {
    /**
     * ForbiddenException constructor.
     * @param string $message
     */
    public function __construct(string $message = '') {
        parent::__construct($message, 403);
    }
}
