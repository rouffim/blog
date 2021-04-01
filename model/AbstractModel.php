<?php

namespace model;

use DateTime;
use model\utils\UuidUtils;
use model\utils\DateUtils;

abstract class AbstractModel {
    private ?int $id;
    private string $uuid;
    private DateTime $version;
    public ?string $MODEL_IMAGE_PATH = null;


    /**
     * AbstractModel constructor.
     * @throws \Exception
     */
    public function __construct() {
        $this->id = null;
        $this->uuid = UuidUtils::createUuid();
        $this->version = DateUtils::now();
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return DateTime
     */
    public function getVersion(): DateTime
    {
        return $this->version;
    }

    /**
     * @param DateTime $version
     */
    public function setVersion(DateTime $version): void
    {
        $this->version = $version;
    }

    /**
     * @param string $version
     * @throws \Exception
     */
    public function setVersionFromString(string $version): void
    {
        $this->version = new DateTime($version);
    }

    /**
     * @return string
     */
    public function getVersionIso8601(): string
    {
        return $this->version->format(DateTime::ATOM);
    }

    /**
     * @return string
     */
    public function getLastUpdate(): string
    {
        return 'Dernière mise à jour il y a ' . DateUtils::stringDiff($this->version);
    }

    /**
     * @param bool $json
     * @return array
     */
    public function toMap(bool $json = false): array {
        $map = array();

        $map["uuid"] = $this->getUuid();
        $map["version_iso"] = $this->getVersionIso8601();
        $map["last_update"] = $this->getLastUpdate();

        if(!$json) {
            $map["version"] = $this->getVersion();
        }

        return $map;
    }
}
