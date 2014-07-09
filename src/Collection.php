<?php

/*
* This file is part of the Mecum package.
*
* (c) Rudy ONFROY <rudy@onfroy.net>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Mecum\Unify;

use Mecum\Expand\ManagerInterface;

/**
 * Collection
 *
 * Expandable collection that is useful to simplify and organise complex data treatments and manipulations.
 * It's can append data from every iterable object.
 *
 * @package  Mecum\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
class Collection extends \ArrayObject implements CollectionInterface
{
    use \Mecum\Expand\ExpandableTrait {
        \Mecum\Expand\ExpandableTrait::__construct as private expandableConstruct;
    }

    const MSG_NOT_ITERABLE = 'Input data must be iterable';

    /**
     *  Class constructor
     *
     *  @param \Mecum\Component\Expand\ManagerInterface $em
     */
    public function __construct(ManagerInterface $em)
    {
        $this->expandableConstruct($em);
    }

    /**
     *  Check if the data is iterable
     *
     *  @param mixed $data
     *
     *  @return bool
     */
    public static function isIterable($data)
    {
        return (is_array($data) || $data instanceof \Traversable);
    }
    
    /**
     *  Check if the data is a collection
     *
     *  @param mixed $data
     *
     *  @return bool
     */
    public static function isCollection($data)
    {
        return ($data instanceof Collection);
    }

    /**
     *  Add data in the collection
     *
     *  @param \Closure|\Traversable|array $input
     *
     *  @throws \InvalidArgumentException
     *
     *  @return $this
     */
    public function append($input)
    {
        // get data from a closure
        $data = ($input instanceof \Closure) ? call_user_func(\Closure::bind($input, $this, get_class())) : $input;
        
        if (!self::isIterable($data)) {
            throw new \InvalidArgumentException(self::MSG_NOT_ITERABLE, 110);
        }

        foreach ($data as $index => $sub) {
            $index = $this->offsetExists($index) ? $this->getUniqueIndex() : $index;
            $this->offsetSet($index, $sub);
        }

        return $this;
    }

    /**
     *  Replace data in the collection
     *
     *  @param \Closure|\Traversable|array $data
     *
     *  @return $this
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray([]);

        return $this->append($data);
    }
    
    /**
     *  Execute a closure on the collection
     *
     *  @param \Closure $closure
     *
     *  @return $this
     */
    public function operate(\Closure  $closure)
    {
        call_user_func(\Closure::bind($closure, $this, get_class()));
        
        return $this;
    }
    
    /**
     *  Execute a closure on all collection elements
     *
     *  @param \Closure $closure
     * 
     *  @return $this
     */
    public function loop(\Closure  $closure)
    {
        foreach ($this as $sub) {
            if (self::isIterable($sub)) {
                call_user_func(\Closure::bind($closure, $sub, get_class()));
            }
        }

        return $this;
    }
    
    /**
     *  Set a data
     *
     *  @param mixed $index
     *  @param mixed $data
     *
     *  @return $this
     */
    public function offsetSet($index, $input)
    {
        $data = (self::isIterable($input) and !self::isCollection($input))
                    ? self::create($this->extensions)->append((array) $input)
                    : $input;

        parent::offsetSet($index, $data);
        
        return $this;
    }
    
    /**
     *  Get a data
     *
     *  @param mixed $index
     *
     *  @return mixed
     */
    public function offsetGet($index)
    {
        if ($this->offsetExists($index)) {
            return parent::offsetGet($index);
        }
        
        $instance = new self($this->extensions);
        $this->offsetSet($index, $instance);
        
        return $instance;
    }

    /**
     *  Unset an index
     *
     *  @param mixed $index
     */
    public function offsetUnset($index)
    {
        if ($this->offsetExists($index)) {
            parent::offsetUnset($index);
        }
        
        return $this;
    }
         
    /**
     *  Get a copy array of data
     *
     *  @return array
     */
    public function getArrayCopy()
    {
        $data = parent::getArrayCopy();
        
        $arrayData = [];
        
        foreach ($data as $Index => $subData) {
            $arrayData[$Index] = self::isCollection($subData) ? $subData->getArrayCopy() : $subData;
        };
        
        return $arrayData;
    }
    
    /**
     *  Get an new unique index
     *
     *  @access protected
     *
     *  @return int
     */
    protected function getUniqueIndex()
    {
        $i = count($this);
        while ($this->offsetExists($i)) {
            ++$i;
        }

        return $i;
    }
    
    /**
     *  __clone
     */
    public function __clone()
    {
        $this->exchangeArray($this->getArrayCopy());
    }
    
    /**
     *  __toString
     */
    public function __toString()
    {
        return '[[Mecum :: Unify Object !!]]';
    }

    /**
     *   __set
     *  @see self::offsetSet()
     */
    public function __set($index, $data)
    {
        return $this->offsetSet($index, $data);
    }

    /**
     *   __get
     *  @see self::offsetGet()
     */
    public function __get($index)
    {
        return $this->offsetGet($index);
    }
    
    /**
     *   __isset
     *  @see self::offsetExists()
     */
    public function __isset ($name)
    {
        return $this->offsetExists($name);
    }
    
    /**
     *   __unset
     *  @see self::offsetUnset()
     */
    public function __unset ($name)
    {
        $this->offsetUnset($name);
    }
}
