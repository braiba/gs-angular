<?php

namespace Geekstitch\Entity;

class Category extends AbstractEntity
{
    protected $categoryTypeId;

    protected $name;

    protected $urlChunk;

    protected $important;

    protected $samplePatternId;

    public function getTableName()
    {
        return 'categories';
    }

    /**
     * @return int
     */
    public function getCategoryTypeId()
    {
        return $this->categoryTypeId;
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

    /**
     * @return boolean
     */
    public function getImportant()
    {
        return (boolean) $this->important;
    }

    /**
     * @return int
     */
    public function getSamplePatternId()
    {
        return $this->samplePatternId;
    }
}