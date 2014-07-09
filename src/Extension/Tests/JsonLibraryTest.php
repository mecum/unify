<?php
namespace Mecum\Unify\Extension\Tests;

use Mecum\Unify\Unify;

class JsonLibraryTest extends \PHPUnit_Framework_TestCase
{
    public function test_create_json()
    {
        $c = Unify::Create(['json']);
        
        $this->assertEquals(true, $c->extensions()->exists('toJson'));   
        $this->assertEquals(true, $c->extensions()->exists('addJson'));               
    }
    
    /**
     * @depends test_create_json
     *
     */
    public function test_addJson()
    {
        $JsonString = '
            [{
                "description" : "Description 1 Json",
                "title" : "Title 1 Json"
            },{
                "description" : "Description 2 Json",
                "title" : "Title 2 Json"
            },{
                "description" : "Description 3 Json",
                "title" : "Title 3 Json "
            }]
        ';

        $c = Unify::create(true)->addJson($JsonString);

        $this->assertEquals(true, $c[1]->offsetExists('title'));  
        $this->assertEquals('Description 3 Json', $c->offsetGet(2)->description);  
    }
    
    /**
     * @depends test_addJson
     *
     */
    public function test_toJson()
    {
        $JsonString = '
            [{
                "description" : "Description 1 Json",
                "title" : "Title 1 Json"
            },{
                "description" : "Description 2 Json",
                "title" : "Title 2 Json"
            },{
                "description" : "Description 3 Json",
                "title" : "Title 3 Json "
            }]
        ';

        $c1 = Unify::createFull()
            ->addJson($JsonString)
            ->loop(function (){    
                $this->newline1 = 'value 3';    
                $this->newline2 = 'value 2';
            });

        $c2 = Unify::createFull()->addJson($c1->toJson());

        $this->assertEquals(true, $c1[1]->offsetExists('title'));  
        $this->assertEquals(true, $c1[2]->offsetExists('newline1'));  
        $this->assertEquals('value 3', $c1[2]->newline1);  
        $this->assertEquals('Title 2 Json', $c1[1]->title);  

        $this->assertEquals(true, $c2[0]->offsetExists('title'));  
        $this->assertEquals(true, $c2[2]->offsetExists('newline2'));  
        $this->assertEquals('value 3', $c1[2]->newline1);  
        $this->assertEquals('Title 1 Json', $c1[0]->title);  
    }
    
    /**
     * @depends test_addJson
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 510
     */
    public function test_addJson_Exception()
    {
        Unify::createFull()->addJson([]);
    }
    
    /**
     * @depends test_toJson
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 520
     */
    public function test_toJson_Exception()
    {
        Unify::createFull()->toJson([]);
    }
}

