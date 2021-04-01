<?php
namespace model\exception\http;

class UnauthorizedException extends HttpException {
    /**
     * UnauthorizedException constructor.
     * @param string $message
     */
    public function __construct(string $message = '') {
        parent::__construct($message, 401);
    }
}
