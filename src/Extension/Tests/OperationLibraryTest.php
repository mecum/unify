<?php
namespace Mecum\Unify\Extension\Tests;

use Mecum\Unify\Unify;

class OperationLibraryTest extends \PHPUnit_Framework_TestCase
{
    public function test_create_operation()
    {
        $c = Unify::Create(['operation']);
        
        $this->assertEquals(true, $c->extensions()->exists('removeALL'));   
        $this->assertEquals(true, $c->extensions()->exists('renameAll'));  
        $this->assertEquals(true, $c->extensions()->exists('getFirst'));   
        $this->assertEquals(true, $c->extensions()->exists('orderBy'));
        $this->assertEquals(true, $c->extensions()->exists('orderRandom'));   
        $this->assertEquals(true, $c->extensions()->exists('limit'));
        
    }

    /**
     * @depends test_create_operation
     */
    public function test_RemoveALL()
    {  
        $c = Unify::createFull()
            ->append([['name' => 'item1','value' => 83]])
            ->removeALL('value');
        
        $this->assertEquals(true, $c[0]->offsetExists('name')); 
        $this->assertEquals(false, $c[0]->offsetExists('value'));   
    }

    /**
     * @depends test_create_operation
     */
    public function test_RenameAll()
    {  
        $c = Unify::createFull()
            ->append([['name' => 'item1','value' => 83]])
            ->RenameAll('name','renamed');
            
        $this->assertEquals(true, $c[0]->offsetExists('renamed')); 
        $this->assertEquals(false, $c[0]->offsetExists('name'));    
    }
    
    /**
     * @depends test_create_operation
     */
    public function test_GetFirst()
    {  
        $c1 = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
            ));
            
        $c2 = Unify::createFull();
            
        $this->assertEquals(83, $c1->getFirst()['value']);
        $this->assertEquals('test', $c2->getFirst('test'));               
    }
    
    /**
     * @depends test_GetFirst
     */
    public function test_OrderBy()
    {  
        $c = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item4','value' => 179]
            ));
        
        // Default order
        $this->assertEquals('item1', $c->getFirst()['name']);
        
        // Ascending order
        $c->OrderBy('value');
        $this->assertEquals('item2', $c->getFirst()['name']);
        
        // Descending order
         $c->OrderBy('value',true);
        $this->assertEquals('item4', $c->getFirst()['name']);
        
         $c2 = Unify::createFull()->append(
            ['name' => 'item1','value' => 83],
            ['name' => 'item2','value' => 12]
         );       
    }
    
    /**
     * @depends test_GetFirst
     */
    public function test_orderRandom()
    {  
        $c1 = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
            ));
       
       //$this->assertEquals('item1', $c1->orderRandom()->getFirst()['name']);  
    }
    
    /**
     * @depends test_GetFirst
     */
    public function test_Limit()
    {  
        $c1 = Unify::createFull()
            ->append(array(
                ['name' => 'item1','value' => 83],
                ['name' => 'item2','value' => 12],
                ['name' => 'item3','value' => 54],
                ['name' => 'item4','value' => 179]
            ));
             
        $c2 = clone $c1;
        $c3 = clone $c1;
        
        $c2->limit(2,0);
        $c3->limit(2,1);
        
        $this->assertEquals(4, $c1->count());
        $this->assertEquals('item1', $c1->getFirst()['name']);
        $this->assertEquals(2, $c2->count());        
        $this->assertEquals('item1', $c2->getFirst()['name']);  
        $this->assertEquals(2, $c3->count());        
        $this->assertEquals('item2', $c3->getFirst()['name']);   

        $c1->limit(2);
        
        $this->assertEquals(2, $c1->count());
        $this->assertEquals('item1', $c1->getFirst()['name']);   
    }
    
    
    /**
     * @depends test_OrderBy
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 210
     */
    public function test_OrderBy_Exception1()
    {  
        Unify::createFull()->OrderBy([]);    
    }
    
    /**
     * @depends test_GetFirst
     *
     * @expectedException LogicException
     * @expectedExceptionCode 220
     */
    public function test_GetFirst_Exception()
    {  
        Unify::createFull()->getFirst();             
    }
    
    /**
     * @depends test_RenameAll
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 230
     */
    public function test_RenameAll_Exception1()
    {  
        Unify::createFull()->RenameAll([],10); 
    }
    
    /**
     * @depends test_RenameAll
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 231
     */
    public function test_RenameAll_Exception2()
    {  
        Unify::createFull()->RenameAll(10,[]); 
    }
    
    /**
     * @depends test_RemoveALL
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 240
     */
    public function test_RemoveALL_Exception()
    {  
        Unify::createFull()->removeALL([]); 
    }
    
    /**
     * @depends test_Limit
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 250
     */
    public function test_Limit_Exception()
    {  
        Unify::createFull()->limit('');  
    }
    
    /**
     * @depends test_Limit_Exception
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 251
     */
    public function test_Limit_Exception2()
    {  
        Unify::createFull()->limit(5,'');  
    }
    
}

