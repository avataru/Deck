<?php

namespace Deck\Tests\Traits;

use PHPUnit_Framework_TestCase as TestCase;

class NamableTest extends TestCase
{
    public $testName = 'Test name';

    public function setUp()
    {

    }

    public function testNameIsInitiallyEmpty()
    {
        $namable = $this->getObjectForTrait('Deck\Traits\Namable');

        $this->assertAttributeEmpty('name', $namable);

        return $namable;
    }

    /**
     * @depends testNameIsInitiallyEmpty
     */
    public function testNameSetterAndGetter($namable)
    {
        $namable->setName($this->testName);

        $this->assertSame($this->testName, TestCase::readAttribute($namable, 'name'));
        $this->assertSame($this->testName, $namable->getName());

        return $namable;
    }

    /**
     * @depends testNameSetterAndGetter
     */
    public function testSetNameCannotBeChanged($namable)
    {
        $this->setExpectedException('Deck\Exceptions\NameException');

        $namable->setName($this->testName);
    }
}
