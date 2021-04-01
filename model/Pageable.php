<?php
namespace model;

use model\utils\StringUtils;

class Pageable {
    private ?int $index = null;
    private ?int $offset = null;
    private ?string $sort_key = null;
    private ?string $sort_type = null;

    /**
     * Pageable constructor.
     */
    public function __construct() {

    }

    /**
     * @return ?int
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * @param ?int $index
     */
    public function setIndex(?int $index): void
    {
        if($index >= 0) {
            $this->index = $index;
        }
    }

    /**
     * @param string|null $index
     */
    public function setIndexFromString(?string $index): void
    {
        if(!is_null($index) && is_numeric($index)) {
            $this->setIndex(intval($index));
        }
    }

    /**
     * @return ?int
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param ?int $offset
     */
    public function setOffset(?int $offset): void
    {
        if($offset > 0) {
            $this->offset = $offset;
        }
    }

    /**
     * @param string|null $offset
     */
    public function setOffsetFromString(?string $offset): void
    {
        if(!is_null($offset) && is_numeric($offset)) {
            $this->setOffset(intval($offset));
        }
    }

    /**
     * @return string|null
     */
    public function getSortKey(): ?string
    {
        return $this->sort_key;
    }

    /**
     * @param string|null $sort_key
     */
    public function setSortKey(?string $sort_key): void
    {
        $this->sort_key = $sort_key;
    }

    /**
     * @return string|null
     */
    public function getSortType(): ?string
    {
        if(StringUtils::isEmpty($this->sort_type)) {
            $this->sort_type = 'ASC';
        }
        return $this->sort_type;
    }

    /**
     * @param string|null $sort_type
     */
    public function setSortType(?string $sort_type): void
    {
        if(!is_null($sort_type)) {
            $sort_type = strtoupper($sort_type);

            if ($sort_type == 'ASC' || $sort_type == 'DESC') {
                $this->sort_type = $sort_type;
            } else {
                $this->sort_type = 'ASC';
            }
        } else {
            $this->sort_type = 'ASC';
        }
    }


}
