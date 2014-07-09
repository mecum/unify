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
 * OperationLibrary
 *
 * Operation extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class OperationLibrary extends Library
{
    const MSG_NO_ELEMENT = 'There is no element in this collection';
    const MSG_ROW_INTEGER = 'The number of rows must be integer';
    const MSG_START_ROW_INTEGER = 'The start row must be integer';
    const MSG_INVALID_INDEX= 'The index must be a string or an integer';

    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->renameAll()->removeAll()->orderBy()->orderRandom()->limit()->getFirst();
    }
    
    /**
     *  Sort the dataset by the value of an index
     *
     *  @param string $index Sort the collection by this index 
     *  @param bool $desc Set to true for sort ascending
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function orderBy()
    {
        $this->set('orderBy', function ($index, $desc = false) {
        
            if (!is_integer($index) && !is_string($index)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_INVALID_INDEX, 210);
            }
        
            $this->uasort(function ($a, $b) use ($index, $desc) {
            
                if ($a[$index] == $b[$index]) {
                    return 0;
                }
                
                if ($desc) {
                    return ($a[$index]  > $b[$index]) ? -1 : 1;
                }
                
                return ($a[$index]  < $b[$index]) ? -1 : 1;
            });
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Sort the dataset randomly
     *
     *  @return $this
     */
    private function orderRandom()
    {
        $this->set('orderRandom', function () {
        
            $data = $this->getArrayCopy();
            shuffle($data);
            
            return $this->exchangeArray($data);
            
        });
        
        return $this;
    }
    
    /**
     *  Rename an index in the dataset
     *
     *  @param string $oldindex The index to replace
     *  @param string $newIndex The new index
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function renameAll()
    {
        $this->set('renameAll', function ($oldindex, $newIndex) {
        
            if (!is_integer($oldindex) && !is_string($oldindex)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_INVALID_INDEX, 230);
            }
            
            if (!is_integer($newIndex) && !is_string($newIndex)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_INVALID_INDEX, 231);
            }
        
            foreach ($this as $sub) {
                if (self::isCollection($sub) && $sub->offsetExists($oldindex)) {
                    $sub->offsetSet($newIndex, $sub->offsetGet($oldindex));
                    $sub->offsetUnset($oldindex);
                };
            };
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Remove an index in the dataset
     *
     *  @param string $index The index to remove
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function removeAll()
    {
        $this->set('removeAll', function ($index) {
        
            if (!is_integer($index) && !is_string($index)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_INVALID_INDEX, 240);
            }
            
            foreach ($this as $sub) {
                if (self::isCollection($sub)) {
                    $sub->offsetUnset($index);
                };
            };
            
            return $this;
        });
        
        return $this;
    }
    
    /**
     *  Limite the the dataset
     *
     *  @param int $row The limit number of rows
     *  @param int $start The start row
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    private function limit()
    {
        $this->set('limit', function ($row, $start = 0) {
        
            if (!is_integer($row)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_ROW_INTEGER, 250);
            }
            
            if (!is_integer($start)) {
                throw new \InvalidArgumentException(OperationLibrary::MSG_START_ROW_INTEGER, 251);
            }
            
            $instance = self::create($this->extensions());
            
            $i = 0;
            
            foreach ($this as $key => $sub) {
            
                if ($i++>=$start) {
                    $instance->append([$key => $sub]);
                }
                
                if ($i>=($start+$row)) {
                    break;
                }
            }
            
            return $this->exchangeArray($instance->getArrayCopy());
            
        });
        
        return $this;
    }
    
    /**
     *  Get the first element of the collection
     *
     *  @param mixed $default Return $default when there is no element in this collection
     *
     *  @throws \LogicException
     *
     *  @return self|mixed
     */
    private function getFirst()
    {
        $this->set('getFirst', function ($default = null) {
        
            $this->getIterator()->rewind();
            
            if (!$this->getIterator()->valid()) {
            
                if (!is_null($default)) {
                    return $default;
                }
                
                throw new \LogicException(OperationLibrary::MSG_NO_ELEMENT, 220);
            }
            
            return $this->getIterator()->current();
        });
        
        return $this;
    }
}
