<?php

namespace service;

use model\AbstractModel;
use model\Pageable;
use model\utils\StringUtils;
use PDO;

abstract class ResourceService extends Service {

    /**
     * ResourceService constructor.
     */
    protected function __construct() {
        parent::__construct();
    }

    /**
     * @param string $uuid
     * @return mixed
     */
    abstract function find(string $uuid);

    /**
     * @param string|null $search
     * @param Pageable|null $pageable
     * @return array
     */
    abstract function findAll(string $search = null, Pageable $pageable = null): array;

    /**
     * @param AbstractModel $model
     * @param bool $update
     * @return mixed
     */
    abstract function save(AbstractModel $model, bool $update = false);

    /**
     * @param string $uuid
     * @return mixed
     */
    abstract function delete(string $uuid);


    /**
     * @param string $sql
     * @param string $searchFieldName
     * @param string|null $searchValue
     * @return string
     */
    protected function searchToSql(string $sql, string $searchFieldName, ?string $searchValue): string {
        if(!StringUtils::isEmpty($searchValue)) {
            $sql .= "WHERE $searchFieldName like :search ";
        }

        return $sql;
    }

    /**
     * @param $stmt
     * @param string|null $searchValue
     */
    protected function bindSearchParam($stmt, ?string $searchValue) {
        if(!StringUtils::isEmpty($searchValue)) {
            $search = "%$searchValue%";
            $stmt->bindParam(":search", $search, PDO::PARAM_STR);
        }
    }

    /**
     * @param string $sql
     * @param Pageable $pageable
     * @return string
     */
    protected function pageableToSql(string $sql, Pageable $pageable): string {
        if(!is_null($pageable)) {

            if(!StringUtils::isEmpty($pageable->getSortKey())) {
                $sql .= " ORDER BY " . $pageable->getSortKey() . " " . $pageable->getSortType();
            }

            if(!is_null($pageable->getIndex())) {
                $sql .= " LIMIT " . $pageable->getIndex();

                if(!is_null($pageable->getOffset())) {
                    $sql .= ", " . $pageable->getOffset();

                }
            }
        }

        return $sql;
    }

}
