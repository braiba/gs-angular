<?php

namespace Geekstitch\Entity;

class CategoryType extends AbstractEntity
{
    const ID_FANDOM = 1;
    const ID_GENRE = 2;
    const ID_PRODUCT = 3;
    const ID_LIMITED_EDITION = 4;

    protected $name;

    protected $urlChunk;

    public function getTableName()
    {
        return 'category_types';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->urlChunk;
    }
}
