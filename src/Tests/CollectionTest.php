<?php
namespace Mecum\Unify\Tests;

use Mecum\Unify\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_set()
    {
        $c = Collection::create();
        
        $c->offsetSet('key1','value1');
        $c['key2'] = 'value2';
        $c->key3 = 'value3';
        
        $c->offsetSet('key1','value1-2');
        $c['key2'] = 'value2-2';
        $c->key3 = 'value3-2';
    } 

    public function test_offsetExists_isset()
    {
        $c = Collection::create();
        
        $c->offsetSet('key1','value1');
        $c['key2'] = 'value2';
        $c->key3 = 'value3';
        
        $this->assertEquals(true,  $c->offsetExists('key1')); 
        $this->assertEquals(true, isset($c->key1));  
        $this->assertEquals(true, isset($c['key1']));   
        
        $this->assertEquals(true,  $c->offsetExists('key2')); 
        $this->assertEquals(true, isset($c->key2));  
        $this->assertEquals(true, isset($c['key2']));   
        
        $this->assertEquals(true,  $c->offsetExists('key3')); 
        $this->assertEquals(true, isset($c->key3));  
        $this->assertEquals(true, isset($c['key3']));    
    }  

       
    public function test_offsetUnset()
    {
        $c = Collection::create();
        
        $c->offsetSet('key1','value1');
        $c['key2'] = 'value2';
        $c->key3 = 'value3';
        
        $c->offsetUnset('key1');
        unset($c['key2']);
        unset($c->key3);        
        
        $this->assertEquals(false,  $c->offsetExists('key1')); 
        $this->assertEquals(false,  $c->offsetExists('key2')); 
        $this->assertEquals(false,  $c->offsetExists('key3')); 
    }      

    public function test_offsetGet()
    {
        $c = Collection::create();
        
        $c->offsetSet('key1','value1');
        $c['key2'] = 'value2';
        $c->key3 = 'value3';
          
        $this->assertEquals('value1',  $c['key1']);  
        $this->assertEquals('value1',  $c->key1); 
        $this->assertEquals('value1',  $c->offsetGet('key1'));   
        
        $this->assertEquals('value2',  $c->offsetGet('key2')); 
        $this->assertEquals('value2', $c->key2);  
        $this->assertEquals('value2', $c['key2']);   
        
        $this->assertEquals('value3', $c->offsetGet('key3')); 
        $this->assertEquals('value3', $c->key3);  
        $this->assertEquals('value3', $c['key3']);  
    }       
       
    public function test_append()
    {
        $c = Collection::create()
            ->append(array(
                ['key1' =>'value1(1)','key2' => 'value2(1)'],
                ['key1' =>'value1(2)','key2' => 'value2(2)']
            ));
        
        $this->assertEquals(true, isset($c));
        $this->assertEquals(true, isset($c[0]));
        $this->assertEquals(true, isset($c[1]->key1));
        $this->assertEquals(true, isset($c->offsetGet(0)->key2));
        
        $this->assertEquals(false, isset($c[4]));
        $this->assertEquals(false, isset($c[1]->key5));
        $this->assertEquals(false, isset($c->offsetGet(1)->key5));
        
        $this->assertEquals(2, count($c)); 
        $this->assertEquals(2, count($c[0]));
        $this->assertEquals(2, $c->offsetExists(1));
        
        $this->assertEquals('value2(2)', $c->offsetGet(1)->key2);
        $this->assertEquals('value2(2)', $c[1]->key2);
        $this->assertEquals('value1(1)', $c[0]['key1']);    
    }
     
    public function test_exchangeArray()
    {
        $c = Collection::create()
            ->append(array(
                    ['key1' =>'value1(1)','key2' => 'value2(1)'],
                    ['key1' =>'value1(2)','key2' => 'value2(2)'],
                    ['key1' =>'value1(3)','key2' => 'value2(3)'],
             ))
             ->exchangeArray(array(
                    ['key1' =>'replace_value1(1)','key2' => 'replace_value2(1)'],
                    ['key1' =>'replace_value1(2)','key2' => 'replace_value2(2)'],
             ));
            
        $this->assertEquals(true, isset($c)); 
        $this->assertEquals(true, isset($c[1]->key1)); 
        
        $this->assertEquals(false, isset($c[2])); 
        
        $this->assertEquals(2, count($c));      
        $this->assertEquals(2, count($c[0]));   
        
        $this->assertEquals('replace_value2(2)', $c[1]->key2);  
        $this->assertEquals('replace_value1(1)', $c[0]['key1']);   
    }
     
    public function test_loop()
    {
        $c = Collection::create()
            ->append(array(
                    ['key1' =>'value1','key2' => 'value2'],
                    ['key1' =>'value1','key2' => 'value2'],
                    ['key1' =>'value1','key2' => 'value2'],
             ))
             ->loop(function()
             {
                $this->offsetSet('key3','value3');
                $this->key4 = 'value4';      
                $this['key5'] = 'value5';   
             });
              
        $this->assertEquals('value3', $c[0]->key3);  
        $this->assertEquals('value4', $c[2]['key4']);  
        $this->assertEquals('value5', $c[1]->offsetGet('key5'));   
    }
     
     public function test_operate()
    {
        $c = Collection::create()
            ->offsetSet('key1',[10,20,30])
            ->operate(function(){ $this->key2 = [12,22,32]; });
            
        $this->assertEquals(true, isset($c));
        $this->assertEquals(10, $c['key1'][0]);
        $this->assertEquals(32, $c->key2[2]);
    }
    
    public function test_isIterable()
    {
        $this->assertEquals(true, Collection::isIterable(array()));  
        $this->assertEquals(true, Collection::isIterable(Collection::create())); 
        $this->assertEquals(true, Collection::isIterable(simplexml_load_string('<root/>'))); 
        $this->assertEquals(false, Collection::isIterable(''));
        $this->assertEquals(false, Collection::isIterable(15)); 
    }
    
    public function test_isCollection()
    {
        $this->assertEquals(true, Collection::isCollection(Collection::create())); 
        $this->assertEquals(false, Collection::isCollection(array())); 
        $this->assertEquals(false, Collection::isCollection(simplexml_load_string('<root/>'))); 
    }
    
    public function test_clone()
    {
        $c1 = Collection::create()->offsetSet('key','value');
        $c2 = clone $c1;
        
        $this->assertEquals('value', $c1->key);
        $this->assertEquals('value', $c2->key);  
    }

}