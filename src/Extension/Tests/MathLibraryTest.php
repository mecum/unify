<?php
namespace Mecum\Unify\Extension\Tests;

use Mecum\Unify\Unify;

class MathLibraryTest extends \PHPUnit_Framework_TestCase
{
    public function test_create_math()
    {
        $c = Unify::Create(['math']);
        
        $this->assertEquals(true, $c->extensions()->exists('sum'));   
        $this->assertEquals(true, $c->extensions()->exists('avg'));        
        $this->assertEquals(true, $c->extensions()->exists('min'));        
        $this->assertEquals(true, $c->extensions()->exists('max'));        
    }
    
    /**
     * @depends test_create_math
     */
    public function test_Sum()
    {
        $c = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item3','value' => 179]
            ));
            
        $this->assertEquals(328, $c->sum('value'));           
    }
    
    /**
     * @depends test_create_math
     */
    public function test_Avg()
    {
        $c = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item3','value' => 179]
            ));
            
        $this->assertEquals(82, $c->avg('value'));                  
    }
    
    /**
     * @depends test_create_math
     */
    public function test_Min()
    {
        $c = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item3','value' => 179]
            ));
                   
        $this->assertEquals(12, $c->min('value'));           
    }
    
    /**
     * @depends test_create_math
     */
    public function test_Max()
    {
        $c = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item3','value' => 179]
            ));
             
        $this->assertEquals(179, $c->max('value'));           
    } 
    
    /**
     * @depends test_Sum
     *
     * @expectedException LogicException
     * @expectedExceptionCode 310
     */
    public function test_Sum_Exception()
    {
       Unify::createFull()
        ->append(array(['name' => 'item1','value' => 'test1'],))
        ->sum('value');           
    } 
    
    /**
     * @depends test_Avg
     *
     * @expectedException LogicException
     * @expectedExceptionCode 320
     */
    public function test_Avg_Exception()
    {
       Unify::createFull()
        ->append(array(['name' => 'item1','value' => 'test1'],))
        ->avg('value');           
    } 
    
    /**
     * @depends test_Min
     *
     * @expectedException LogicException
     * @expectedExceptionCode 330
     */
    public function test_Min_Exception()
    {
        Unify::createFull()
            ->append(array(['name' => 'item1','value' => 'test1'],))
            ->min('value');           
    }
    
    /**
     * @depends test_Max
     *
     * @expectedException LogicException
     * @expectedExceptionCode 340
     */
    public function test_Max_Exception()
    {
        Unify::createFull()
            ->append(array(['name' => 'item1','value' => 'test1'],))
            ->max('value');           
    } 
}

