<?php

namespace model\utils;

use Exception;
use Ramsey\Uuid\Uuid;

class UuidUtils {

    /**
     * @return string
     * @throws Exception
     */
    static function createUuid(): string {
        return Uuid::uuid4();
    }

    /**
     * @param string $uuid
     * @return bool
     */
    static function isValidUuid(string $uuid): bool {
        return !StringUtils::isEmpty($uuid) && Uuid::fromString($uuid)->getVersion() == Uuid::UUID_TYPE_RANDOM;
    }

}
