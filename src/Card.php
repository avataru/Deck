<?php

namespace Deck;

class Card
{
    use Traits\Namable;

    /**
     * Card set, edition or expansion
     * The terminology depends on the game.
     *
     * @var string
     */
    protected $set;

    /**
     * Card types
     * These include but are not limited to rarity, aesthetics (eg.: golden or
     * foiled), category (eg.: resource, creature, spell, etc.).
     *
     * @var array
     */
    protected $types = [];

    /**
     * Card atributes
     * Attributes that define the mechanics of a card such as attack, health,
     * resource cost or special abilities.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Static builder
     * Allows chaining immediately following instantiation.
     *
     * @param  string|null $name Name of the card
     *
     * @return self
     */
    public static function make($name = null)
    {
        return new static($name);
    }

    /**
     * Constructor
     *
     * @param  string|null $name Name of the card
     */
    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * Set the card set
     *
     * @param string $name Set name
     *
     * @return self
     */
    public function setSet($name)
    {
        $this->set = $name;

        return $this;
    }

    /**
     * Get the card set
     *
     * @return string Set name
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Set the card types
     *
     * @param array $types Card types
     *
     * @return self
     */
    public function setTypes(array $types)
    {
        $types       = array_merge($this->types, $types);
        $this->types = array_values(array_unique($types));

        return $this;
    }

    /**
     * Get the card types
     *
     * @return array Card types
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set a card attribute
     * The name is used as key and must be unique.
     *
     * @param string $name  Attribute name
     * @param mixed  $value Attribute value
     *
     * @return self
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Get a card attribute
     *
     * @param string $name  Attribute name
     *
     * @return mixed|null   Attribute value or null if the attribute is not set
     */
    public function getAttribute($name)
    {
        return (array_key_exists($name, $this->attributes))
            ? $this->attributes[$name]
            : null;
    }

    /**
     * Get a hash based on the card
     * The hash is based on all the card information, not only the name.
     *
     * @return string Unique card hash
     */
    public function getHash()
    {
        return sha1(serialize($this));
    }
}
