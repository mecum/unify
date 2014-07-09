<?php
namespace Mecum\Unify\Tests;

use Mecum\Unify\Unify;
use Mecum\Expand\Manager;
use Mecum\Unify\Collection;

class UnifyTest extends \PHPUnit_Framework_TestCase
{
    
    public function test_create_manager()
    {
        $m = Manager::create()
                ->set('extension',function(){ return true; });
        
        $c = Unify::create($m);

        $this->assertEquals(true,  $c instanceof Collection);  
        $this->assertEquals(true,  $c->extensions()->exists('extension')); 
        $this->assertEquals(false, $c->extensions()->exists('extension2'));           
    }
    
    public function test_createFull()
    {
        $c = Unify::createFull();
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(true,  $c->extensions()->exists('avg'));  
        $this->assertEquals(true,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(true,  $c->extensions()->exists('toHTML')); 
    }
   
    /**
     * @depends test_createFull
     */
    public function test_create_true()
    {
        $c = Unify::create(true);
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(true,  $c->extensions()->exists('avg'));  
        $this->assertEquals(true,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(true,  $c->extensions()->exists('toHTML')); 
    }
    
    public function test_createLess()
    {
        $c = Unify::createLess();
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(false,  $c->extensions()->exists('avg'));  
        $this->assertEquals(false,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(false,  $c->extensions()->exists('toHTML')); 
    }
    
    /**
     * @depends test_createLess
     */
    public function test_create()
    {
        $c = Unify::create();
        
        $this->assertEquals(true,  $c instanceof Collection);  
    }     
    
    /**
     * @depends test_createLess
     */
    public function test_create_false()
    {
        $c = Unify::create(false);
        $this->assertEquals(true,  $c instanceof Collection);
        
        $this->assertEquals(false,  $c->extensions()->exists('avg'));  
        $this->assertEquals(false,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(false,  $c->extensions()->exists('toHTML')); 
    }
    
    /**
     * @depends test_createLess
     */
    public function test_create_false_data()
    {
        $c = Unify::create(false,['test' => 'test']);
        
        $this->assertEquals(true,  $c instanceof Collection);     
        $this->assertEquals('test',  $c->test);
    }
    
    public function test_createWith()
    {
        $c = Unify::createWith(['Math','Operation']);
        
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(true,  $c->extensions()->exists('avg'));  
        $this->assertEquals(true,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(false,  $c->extensions()->exists('toHTML')); 
    }  
    
    /**
     * @depends test_createWith
     */
    public function test_create_array()
    {
        $c = Unify::create(['operation','math']);
        
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(true,  $c->extensions()->exists('avg'));  
        $this->assertEquals(true,  $c->extensions()->exists('renameAll'));    
        $this->assertEquals(false, $c->extensions()->exists('toHTML'));  
    }  
    
    /**
     * @depends test_createWith
     */
    public function test_create_string()
    {
        $c = Unify::create('operation');
        
        $this->assertEquals(true,  $c instanceof Collection);   
        $this->assertEquals(true,  $c->extensions()->exists('renameAll')); 
        $this->assertEquals(false,  $c->extensions()->exists('avg'));          
        $this->assertEquals(false, $c->extensions()->exists('toHTML'));  
    }  
}