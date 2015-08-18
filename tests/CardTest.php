<?php

namespace Deck\Tests;

use PHPUnit_Framework_TestCase as TestCase;
use Deck\Card;

class CardTest extends TestCase
{
    public $testName = 'Test Card';

    public function testEmptyConstructor()
    {
        $card = new Card();

        $this->assertInstanceOf('Deck\Card', $card);

        return $card;
    }

    public function testConstructorWithName()
    {
        $card = new Card($this->testName);

        $this->assertSame(
            $this->testName,
            TestCase::readAttribute($card, 'name')
        );
    }

    public function testBuilder()
    {
        $card = Card::make();

        $this->assertInstanceOf('Deck\Card', $card);
    }

    /**
     * @depends testEmptyConstructor
     */
    public function testSetIsInitiallyEmpty($card)
    {
        $this->assertAttributeEmpty('set', $card);

        return $card;
    }

    /**
     * @depends testSetIsInitiallyEmpty
     */
    public function testSetSetterAndGetter($card)
    {
        $name = 'Test Set';
        $card->setSet($name);

        $this->assertSame($name, TestCase::readAttribute($card, 'set'));
        $this->assertSame($name, $card->getSet());
    }

    /**
     * @depends testSetIsInitiallyEmpty
     */
    public function testSetSetterIsChainable($card)
    {
        $sameCard = $card->setSet('');

        $this->assertSame($card, $sameCard);
    }

    /**
     * @depends testEmptyConstructor
     */
    public function testTypesAreInitiallyEmpty($card)
    {
        $this->assertAttributeEmpty('set', $card);

        return $card;
    }

    /**
     * @depends testTypesAreInitiallyEmpty
     */
    public function testTypesSetterAndGetter($card)
    {
        $types = ['type_1', 'type_2'];
        $card->setTypes($types);

        $this->assertSame($types, TestCase::readAttribute($card, 'types'));
        $this->assertSame($types, $card->getTypes());

        $moreTypes = ['type_3', 'type_1', 'type_4'];
        $card->setTypes($moreTypes);

        $this->assertSame(
            ['type_1', 'type_2', 'type_3', 'type_4'],
            $card->getTypes()
        );
    }

    /**
     * @depends testTypesAreInitiallyEmpty
     */
    public function testTypesSetterIsChainable($card)
    {
        $sameCard = $card->setTypes([]);

        $this->assertSame($card, $sameCard);
    }

    /**
     * @depends testEmptyConstructor
     */
    public function testAttributesAreInitiallyEmpty($card)
    {
        $this->assertAttributeEmpty('attributes', $card);

        return $card;
    }

    /**
     * @depends testAttributesAreInitiallyEmpty
     */
    public function testUnsetAttributeIsNull($card)
    {
        $key = 'Bar';

        $this->assertFalse(
            array_key_exists($key, TestCase::readAttribute($card, 'attributes'))
        );
        $this->assertNull($card->getAttribute($key));
    }

    /**
     * @depends testAttributesAreInitiallyEmpty
     */
    public function testAttributeSetterAndGetter($card)
    {
        $key = 'Foo';

        $value = 'foo';
        $card->setAttribute($key, $value);

        $attributes = TestCase::readAttribute($card, 'attributes');

        $this->assertSame($value, $attributes[$key]);
        $this->assertSame($value, $card->getAttribute($key));

        $newValue = 'bar';
        $card->setAttribute($key, $newValue);

        $attributes = TestCase::readAttribute($card, 'attributes');

        $this->assertSame($newValue, $card->getAttribute($key));
    }

    /**
     * @depends testAttributesAreInitiallyEmpty
     */
    public function testAttributeSetterIsChainable($card)
    {
        $sameCard = $card->setAttribute('Baz', '');

        $this->assertSame($card, $sameCard);
    }

    public function testCardHashGeneration()
    {
        $firstCard = Card::make('Foo');
        $secondCard = clone $firstCard;
        $secondCard->setAttribute('lorem', 'ipsum');
        $thirdCard = clone $secondCard;
        $thirdCard->setAttribute('dolor', 'sit amet');
        $fourthCard = Card::make('Bar');

        $this->assertNotSame($firstCard->getHash(),  $secondCard->getHash());
        $this->assertNotSame($firstCard->getHash(),  $thirdCard->getHash());
        $this->assertNotSame($firstCard->getHash(),  $fourthCard->getHash());
        $this->assertNotSame($secondCard->getHash(), $thirdCard->getHash());
        $this->assertNotSame($secondCard->getHash(), $fourthCard->getHash());
        $this->assertNotSame($thirdCard->getHash(),  $fourthCard->getHash());
    }
}
