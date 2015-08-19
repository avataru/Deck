<?php

namespace Deck;

class Deck
{
    use Traits\Namable;

    /**
     * Cards in the deck
     *
     * @var array
     */
    private $cards = [];

    /**
     * Static builder
     * Allows chaining immediately following instantiation.
     *
     * @param  string|null $name Name of the deck
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
     * @param  string|null $name Name of the deck
     */
    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * Add a card to the deck
     * If the card already exists, increase the count
     *
     * @param Card $card
     *
     * @return self
     */
    public function addCard(Card $card)
    {
        $hash = $card->getHash();

        if (array_key_exists($hash, $this->cards)) {
            $this->cards[$hash]['count']++;
        } else {
            $this->cards[$hash] = [
                'count'   => 1,
                'details' => $card
            ];
        }

        $this->sortCards();

        return $this;
    }

    /**
     * Sort the cards
     * The cards will be sorted alphabetically by name
     *
     * @return void
     */
    protected function sortCards()
    {
        uasort($this->cards, function($cardA, $cardB) {
            return strcmp(
                $cardA['details']->getName(),
                $cardB['details']->getName()
            );
        });
    }

    /**
     * Get the cards in the deck
     *
     * @return array Cards
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Get the total number of cards in the deck
     *
     * @return integer Number of cards
     */
    public function getCardsCount()
    {
        $counts = array_column($this->cards, 'count');

        return array_sum($counts);
    }

    /**
     * Build a list of cards
     *
     * @param  string $template Template to use for building the cards list
     *
     * @return string
     */
    public function getCardsList($template = "%1\$dx %2\$s\n")
    {
        return array_reduce($this->getCards(), function($list, $card) use ($template) {
            $list .= sprintf($template, $card['count'], $card['details']->getName());
            return $list;
        }, '');
    }
}
