<?php
namespace Mecum\Unify\Extension\Tests;

use Mecum\Unify\Unify;

class YamlLibraryTest extends \PHPUnit_Framework_TestCase
{
    public function test_create_yaml()
    {
        $c = Unify::Create(['yaml']);
        
        $this->assertEquals(true, $c->extensions()->exists('toYaml'));   
        $this->assertEquals(true, $c->extensions()->exists('addYaml'));               
    }
    
    public function test_create_toYaml_addYaml()
    {
        $c1 = Unify::Create(['yaml'])
                ->offsetSet('name','value');
        
        $xamlString = $c1->toYaml();
        
        $c2 = Unify::Create(['yaml']);
        $c2->addYaml($xamlString);
        
        $this->assertEquals(true, is_string($xamlString));       
        $this->assertEquals(true, $c2->offsetExists('name'));  
        $this->assertEquals('value', $c2->name);    
    }
}