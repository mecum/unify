<?php
namespace Mecum\Unify\Tests;

use Mecum\Unify\Unify;

class UnifyExceptionTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 120
     */
    public function test_create_exception()
    {
        Unify::create(10);
    }

     /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 120
     */
    public function test_create2_exception()
    {
        Unify::create(new \stdClass());
    }
}