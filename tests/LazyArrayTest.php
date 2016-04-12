<?php


namespace TheCodingMachine\LazyArray;


use TheCodingMachine\LazyArray\Fixtures\Test;

class LazyArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testLazyArray()
    {
        $lazyArray = new LazyArray([
            '\stdClass'
        ]);

        $this->assertEquals(new \stdClass(), $lazyArray[0]);
    }

    public function testLazyArrayInjectInstance()
    {
        $lazyArray = new LazyArray([
            new \stdClass()
        ]);

        $this->assertEquals(new \stdClass(), $lazyArray[0]);
        $this->assertSame($lazyArray[0], $lazyArray[0]);
    }

    public function testLazyArrayWithParams()
    {
        $lazyArray = new LazyArray([
            [Test::class, [42]]
        ]);

        $this->assertInstanceOf(Test::class, $lazyArray[0]);
        $this->assertEquals(42, $lazyArray[0]->foo);
    }

    public function testUnset()
    {
        $lazyArray = new LazyArray([
            '\stdClass'
        ]);

        $this->assertArrayHasKey(0, $lazyArray);
        unset($lazyArray[0]);
        $this->assertArrayNotHasKey(0, $lazyArray);
    }

    public function testPush()
    {
        $lazyArray = new LazyArray();

        $key = $lazyArray->push(Test::class, 42);
        $this->assertArrayHasKey($key, $lazyArray);
        $this->assertInstanceOf(Test::class, $lazyArray[$key]);
        $this->assertEquals(42, $lazyArray[$key]->foo);
    }

    public function testPushObject()
    {
        $lazyArray = new LazyArray();

        $key = $lazyArray->push(new \stdClass());
        $this->assertArrayHasKey($key, $lazyArray);
        $this->assertInstanceOf('stdClass', $lazyArray[$key]);
    }

    /**
     * @expectedException \LogicException
     */
    public function testSet()
    {
        $lazyArray = new LazyArray();

        $lazyArray[0] = 12;
    }
}
