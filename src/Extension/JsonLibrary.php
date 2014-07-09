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
 * JsonLibrary
 *
 * Json extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class JsonLibrary extends Library
{
    const MSG_INVALID_INPUT_FILE = 'The input file must be a string';
    const MSG_INVALID_FILE_NAME = 'The file name must be a string';

    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->addJson()->toJson();
    }
    
    /**
     *  Add Json data in the dataset
     *
     *  @param string $input The json string or the json file name
     *  @param bool $isFileName Define to true if $input is a file name
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function addJson()
    {
        $this->set('addJson', function ($input, $isFileName = false) {
        
            if (!is_string($input)) {
                throw new \InvalidArgumentException(JsonLibrary::MSG_INVALID_INPUT_FILE, 510);
            }
        
            $json = $isFileName ? file_get_contents($input) : $input;
            
            $this->append(json_decode($json, true));
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Export dataset to Json format
     *
     *  @param string $fileName
     *
     *  @throws \InvalidArgumentException
     *
     *  @return String|$this Return $this if $fileName is defined
     */
    private function toJson()
    {
        $this->set('toJson', function ($fileName = '') {
        
            if (!is_string($fileName)) {
                throw new \InvalidArgumentException(JsonLibrary::MSG_INVALID_FILE_NAME, 520);
            }
            
            $jsonString = json_encode($this->getArrayCopy());
            
            if ($fileName === '') {
                return $jsonString;
            }
            
            file_put_contents($fileName, $jsonString);
            
            return $this;
        });
        
        return $this;
    }
}
