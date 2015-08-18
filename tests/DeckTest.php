<?php

namespace Deck\Tests;

use PHPUnit_Framework_TestCase as TestCase;
use Deck\Deck;
use Deck\Card;

class DeckTest extends TestCase
{
    public $testName = 'Test Deck';

    public function testEmptyConstructor()
    {
        $deck = new Deck();

        $this->assertInstanceOf('Deck\Deck', $deck);

        return $deck;
    }

    public function testConstructorWithName()
    {
        $deck = new Deck($this->testName);

        $this->assertSame(
            $this->testName,
            TestCase::readAttribute($deck, 'name')
        );
    }

    public function testBuilder()
    {
        $deck = Deck::make();

        $this->assertInstanceOf('Deck\Deck', $deck);
    }

    /**
     * @depends testEmptyConstructor
     */
    public function testThereAreNoCardsInitially($deck)
    {
        $this->assertAttributeEmpty('cards', $deck);

        return $deck;
    }

    /**
     * @depends testThereAreNoCardsInitially
     */
    public function testAddingACardAndCardsGetter($deck)
    {
        $card = Card::make('Foo');
        $deck->addCard($card);
        $cards = TestCase::readAttribute($deck, 'cards');

        $this->assertSame($cards, $deck->getCards());
        $this->assertArrayHasKey($card->getHash(), $deck->getCards());
        $this->assertSame($cards[$card->getHash()]['count'], 1);
        $this->assertSame($cards[$card->getHash()]['details'], $card);
        $this->assertNotSame(
            serialize($cards[$card->getHash()]['details']),
            serialize(Card::make('Bar'))
        );

        return $deck;
    }

    /**
     * @depends testAddingACardAndCardsGetter
     */
    public function testIncreaseCardCountWhenAddingAnExistingCard($deck)
    {
        $initialCard = Card::make('Foo');
        $deck->addCard($initialCard);
        $otherCard = Card::make('Bar');
        $deck->addCard($otherCard);
        $cards = $deck->getCards();

        $this->assertSame(2, $cards[$initialCard->getHash()]['count']);
        $this->assertSame(1, $cards[$otherCard->getHash()]['count']);
    }

    public function testCardsAreSortedAlphabeticallyAfterAddingACard()
    {
        $deck = Deck::make('Test deck');
        foreach ([
            Card::make('Aaa'),
            Card::make('Aac'),
            Card::make('aaa'),
            Card::make('Aab')
        ] as $card) {
            $deck->addCard($card);
        }

        $cards = $deck->getCards();
        $cardNames = [];
        foreach ($cards as $card) {
            array_push($cardNames, $card['details']->getName());
        }

        $this->assertSame(['Aaa', 'Aab', 'Aac', 'aaa'], $cardNames);
    }

    public function testCardsCounting()
    {
        $deck = Deck::make('Test deck');
        foreach ([
            Card::make('Foo'),
            Card::make('Bar'),
            Card::make('Foo')
        ] as $card) {
            $deck->addCard($card);
        }

        $this->assertSame(2, count($deck->getCards()));
        $this->assertSame(3, $deck->getCardsCount());

        return $deck;
    }

    /**
     * @depends testCardsCounting
     */
    public function testCardListGeneration($deck)
    {
        $this->assertSame("1x Bar\n2x Foo\n", $deck->getCardsList());
        $this->assertSame('Bar|Foo|', $deck->getCardsList('%2$s|'));
    }
}
