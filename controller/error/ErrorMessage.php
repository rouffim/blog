<?php
namespace controller\error;


class ErrorMessage {
    public string $key;
    public string $message;

    /**
     * ErrorMessage constructor.
     * @param string $key
     * @param string $message
     */
    public function __construct(string $key, string $message) {
        $this->key = $key;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return "{\"key\":\"$this->key\", \"message\": \"$this->message\"}";
    }


}
