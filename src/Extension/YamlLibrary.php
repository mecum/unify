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
use Symfony\Component\Yaml\Yaml;

/**
 * YamlLibrary
 *
 * Yaml extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class YamlLibrary extends Library
{
    const MSG_INVALID_INPUT_FILE = 'The input file must be a string';
    const MSG_INVALID_FILE_NAME = 'The file name must be a string';
    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->addYaml()->toYaml();
    }
    
    /**
     *  Add Yaml data in the dataset
     *
     *  @param string $input The yaml string or the yaml file name
     *  @param bool $isFileName Define to true if $input is a file name
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function addYaml()
    {
        $this->set('addYaml', function ($input, $isFileName = false) {
        
            if (!is_string($input)) {
                throw new \InvalidArgumentException(YamlLibrary::MSG_INVALID_INPUT_FILE, 610);
            }
            
            $this->append($isFileName ?  Yaml::parse(file_get_contents($input)) :  Yaml::parse($input));
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Export dataset to Yaml format
     *
     *  @param string $fileName
     *
     *  @throws \InvalidArgumentException
     *
     *  @return String|$this Return $this if $fileName is defined
     */
    private function toYaml()
    {
        $this->set('toYaml', function ($fileName = '') {
        
            if (!is_string($fileName)) {
                throw new \InvalidArgumentException(XmlLibrary::MSG_INVALID_FILE_NAME, 620);
            }
            
            $yamlString = Yaml::dump($this->getArrayCopy());
            
            if ('' == $fileName) {
                return $yamlString;
            }
            
            file_put_contents($fileName, $yamlString);
            
            return $this;
        });
        
        return $this;
    }
}
