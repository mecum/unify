<?php
namespace Mecum\Unify\Extension\Tests;

use Mecum\Unify\Unify;

class XmlLibraryTest extends \PHPUnit_Framework_TestCase
{
    public function test_create_xml()
    {
        $c = Unify::Create(['xml']);
        
        $this->assertEquals(true, $c->extensions()->exists('toXML'));   
        $this->assertEquals(true, $c->extensions()->exists('addXML'));       
        $this->assertEquals(true, $c->extensions()->exists('isXMLValid'));
    }

    /**
     * @depends test_create_xml
     */
    public function test_addXML_toXml_isXMLValid()
    {
        $xmlString = '
            <root>	
                <item>
                    <title>Title 1 XML </title>	
                    <description>Description 1 XML</description>	
                </item>	
                <item>
                    <title>Title 3 XML </title>	
                    <description>Description 3 XML</description>	
                </item>	
                <item>
                    <title>Title 2 XML </title>	
                    <description>Description 2 XML</description>	
                </item>	
            </root>
        ';

        $c1 = Unify::createFull()
            ->addXML($xmlString,'item')
            ->loop(function (){    
                $this->addline1 = 'value 3';    
                $this->addline2 = 'value 2';
            });
        
        $c2 = Unify::createFull()->addXML($c1->toXml('item'),'item');
            
        $c1->offsetSet('ligneset','value1'); // Invalide XML

        $this->assertEquals(true, $c1[1]->offsetExists('title'));  
        $this->assertEquals(true, $c1[2]->offsetExists('addline2'));  
        $this->assertEquals(true, $c1->offsetExists('ligneset'));  
        $this->assertEquals(false, $c1->isXMLValid());    
        
        $this->assertEquals(true, $c2[0]->offsetExists('title'));  
        $this->assertEquals(true, $c2[2]->offsetExists('addline1')); 
        $this->assertEquals(false, $c2->offsetExists('ligneset'));          
        $this->assertEquals(true, $c2->isXMLValid());  
        
    }
    
    /**
     * @depends test_addXML_toXml_isXMLValid
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 410
     */
    public function test_addXML_Exception()
    {
       Unify::createFull()->addXML([],'item'); 
    }
    
    
    /**
     * @depends test_addXML_toXml_isXMLValid
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 420
     */
    public function test_toXML_Exception()
    {
       Unify::createFull()->toXML('item',[]);
    }

}

