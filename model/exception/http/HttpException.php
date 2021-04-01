<?php
namespace model\exception\http;

use Exception;

abstract class HttpException extends Exception {
    /**
     * HttpException constructor.
     * @param string $message
     * @param int $code
     */
    protected function __construct(string $message = '', int $code = 500) {
        parent::__construct($message, $code);
    }
}
