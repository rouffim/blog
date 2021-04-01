<?php


namespace model;


class TechnicalModel
{
    protected int $id;
    protected string $name;

    /**
     * TechnicalModel constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return "id : $this->id, name : $this->name";
    }


}
