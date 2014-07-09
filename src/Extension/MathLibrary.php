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
 * MathLibrary
 *
 * Math extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class MathLibrary extends Library
{
    const MSG_NO_NUMERIC_VALUE = 'No numeric values were been found';
    
    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->sum()->avg()->min()->max();
    }

    /**
     *  Get sum of values with an index name
     *
     *  @throws \LogicException
     *
     *  @return int|float
     */
    private function sum()
    {
        $this->set('sum', function ($index) {
        
            $sum = $i = 0;
            foreach ($this as $data) {
                if (self::isCollection($data) && $data->offsetExists($index) && is_numeric($data[$index])) {
                    $sum += $data[$index];
                    ++$i;
                }
            }
            
            if (0 === $i) {
                throw new \LogicException(MathLibrary::MSG_NO_NUMERIC_VALUE, 310);
            }
            
            return $sum;
        });
        
        return $this;
    }
    
    /**
     *  Get average of values with an index name
     *
     *  @throws \LogicException
     *
     *  @return int|float
     */
    private function avg()
    {
        $this->set('avg', function ($index) {
        
            $sum = $i = 0;
            foreach ($this as $data) {
                if (self::isCollection($data) && $data->offsetExists($index) && is_numeric($data[$index])) {
                    $sum += $data[$index];
                    ++$i;
                }
            }

            if (0 === $i) {
                throw new \LogicException(MathLibrary::MSG_NO_NUMERIC_VALUE, 320);
            }
            
            return ($sum / $i);
        });
        
        return $this;
    }
    
    /**
     *  Get the minimum value with an index name
     *
     *  @throws \LogicException
     *
     *  @return int|float
     */
    private function min()
    {
        $this->set('min', function ($index) {
        
            foreach ($this as $data) {
                if (self::isCollection($data) && $data->offsetExists($index)  && is_numeric($data[$index])) {
                    $min = isset($min) ? min($min, $data[$index]) : $data[$index];
                }
            }
            
            if (!isset($min)) {
                throw new \LogicException(MathLibrary::MSG_NO_NUMERIC_VALUE, 330);
            }
            
            return $min;
        });
        
        return $this;
    }
    
    /**
     *  Get the maximum value with an index name
     *
     *  @throws \LogicException
     *
     *  @return int|float
     */
    private function max()
    {
        $this->set('max', function ($index) {
        
            foreach ($this as $data) {
                if (self::isCollection($data) && $data->offsetExists($index) && is_numeric($data[$index])) {
                    $max = isset($max) ? max($max, $data[$index]) : $data[$index];
                }
            }
            
            if (!isset($max)) {
                throw new \LogicException(MathLibrary::MSG_NO_NUMERIC_VALUE, 340);
            }
            
            return $max;
        });
        
        return $this;
    }
}
