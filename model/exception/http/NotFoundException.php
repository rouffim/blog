<?php
namespace model\exception\http;

class NotFoundException extends HttpException {
    /**
     * NotFoundException constructor.
     * @param string $message
     */
    public function __construct(string $message = '') {
        parent::__construct($message, 404);
    }
}
