<?php
namespace Mecum\Unify\Tests;

use Mecum\Unify\Collection;

class CollectionExceptionTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 110
     */
    public function test_add_exception()
    {
        Collection::create()->append('');
    }

     /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 110
     */
    public function test_exchangeArray_exception()
    {
        Collection::create()->exchangeArray('');
    }
}

