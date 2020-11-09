<?php

declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;

use function in_array;

abstract class AbstractDto implements DtoInterface
{
    public const FALSE = 'false';
    public const TRUE = 'true';

    /** @var ?string */
    protected $wordSearch;

    /** @var ?String */
    protected $page;

    /** @var ?String */
    protected $name;

    /** @var ?String */
    protected $id;

    /** @var ?string */
    protected $visible;

    /** @var ?string */
    protected $hide;

    public function getWordSearch(): ?string
    {
        return $this->wordSearch;
    }

    public function setWordSearch($wordSearch)
    {
        $this->wordSearch = $wordSearch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible): AbstractDto
    {
        $this->checkBool($visible);
        $this->visible = $visible;

        return $this;
    }

    protected function checkBool($value): void
    {
        if (! in_array($value, [null, self::TRUE, self::FALSE])) {
            throw new InvalidArgumentException('valeur interdite');
        }
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @param mixed $hide
     */
    public function setHide($hide): AbstractDto
    {
        $this->checkBool($hide);
        $this->hide = $hide;

        return $this;
    }
}
