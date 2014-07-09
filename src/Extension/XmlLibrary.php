<?php

/*
* This file is part of the Mecum package.
*
* (c) Rudy ONFROY <rudy@onfroy.net>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
 
namespace Mecum\Unify\Extension;

use Mecum\Expand\Library;

/**
 * XmlLibrary
 *
 * XML extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class XmlLibrary extends Library
{
    const MSG_INVALID_INPUT_FILE = 'The input file must be a string';
    const MSG_INVALID_FILE_NAME = 'The file name must be a string';

    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->addXML()->toXML()->isXMLValid();
    }
    
    /**
     *  Add XML data in the dataset
     *
     *  @param string $input The xml string or the xml file name
     *  @param string $xpath
     *  @param bool $isFileName Define to true if $input is a file name
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function addXML()
    {
        $this->set('addXML', function ($input, $xpath, $isFileName = false) {
        
            if (!is_string($input)) {
                throw new \InvalidArgumentException(XmlLibrary::MSG_INVALID_INPUT_FILE, 410);
            }
            
            $xml = $isFileName ? simplexml_load_file($input) : simplexml_load_string($input);
            
            $this->append($xml->xpath($xpath));
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Export dataset to XML format
     *
     *  @param string $nodeName
     *  @param string $fileName
     *
     *  @throws \InvalidArgumentException
     *
     *  @return String|$this Return $this if $fileName is defined
     */
    private function toXML()
    {
        $this->set('toXML', function ($nodeName, $fileName = '') {
        
            if (!is_string($fileName)) {
                throw new \InvalidArgumentException(XmlLibrary::MSG_INVALID_FILE_NAME, 420);
            }
            
            //Add xml child recurcively
            $toXML = function (array $arr, $xml, $nodeName) use (&$toXML) {
            
                foreach ($arr as $k => $v) {
                    is_array($v) ? $toXML($v, $xml->addChild($nodeName),$k) : $xml->addChild($k, $v);
                }
                    
                return $xml;
            };
            
            $xmlString = $toXML($this->getArrayCopy(), new \SimpleXMLElement('<root/>'), $nodeName)->asXML();
            
            if ('' == $fileName) {
                return $xmlString;
            }
            
            file_put_contents($fileName, $xmlString);
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Check if the data set is valid to an XML conversion
     *
     *  @return bool
     */
    private function isXMLValid()
    {
        $this->set('isXMLValid', function () {
        
            // Data must be values or collections, not both
            $isXMLValid = function ($data) use (&$isXMLValid) {
            
                $isValid = true;
                $isCollection = $isValues = false;
                
                foreach ($data as $sub) {
                
                    if (self::isCollection($sub)) {
                        $isValid = $isXMLValid($sub); // Recursive validation
                        $isCollection = true;
                    } else {
                        $isValues = true;
                    }
                    
                    if (!$isValid) {
                        break;
                    }
                }
                
                return (($isCollection xor $isValues) and $isValid);
            };
            
            return $isXMLValid($this);
        });
        
        return $this;
    }
}
