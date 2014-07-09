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
 * DebugLibrary
 *
 * Debug extension library package
 *
 * @package  Mecum\Component\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
final class DebugLibrary extends Library
{
    /**
     *  Constructor
     *  Load all extensions methods
     */
    public function __construct()
    {
        $this->toHTML();
    }
    
    /**
     *  Export dataset to HTML format
     *
     *  @return string
     */
    private function toHTML()
    {
        $this->set('toHTML', function () {
        
            $toHTML = function ($collection) use (&$toHTML) {
            
                $items = '';
                foreach ($collection as $index => $sub) {
                    $sub = !self::isCollection($sub) ? $sub : $toHTML($sub);
                    $items .= sprintf('<li>[%s] %s</li>', $index, $sub);
                }
                
                return sprintf('<ul>%s</ul>', $items);
            };

            return $toHTML($this);
        });

        return $this;
    }
}
