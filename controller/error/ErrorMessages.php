<?php
namespace controller\error;

class ErrorMessages {
    public array $errors;

    /**
     * ErrorMessages constructor.
     */
    public function __construct() {
        $this->errors = array();
    }

    /**
     * @param ErrorMessage $error
     */
    public function addError(ErrorMessage $error) {
        array_push($this->errors, $error);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool {
        return count($this->errors) == 0;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        $ch = '[';

        if(!$this->isEmpty()) {
            foreach ($this->errors as $error) {
                $ch .= $error . ",";
            }

            $ch = substr($ch, 0, -1);
        }

        return $ch . ']';
    }


}
