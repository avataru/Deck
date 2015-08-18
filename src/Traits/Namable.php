<?php

namespace Deck\Traits;

use Deck\Exceptions\NameException;

trait Namable
{
    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * Set the name
     *
     * @param string|null $name Name value to set
     *
     * @return self
     */
    public function setName($name = null)
    {
        if (!empty($this->name)) {
            throw new NameException('Name already set');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
