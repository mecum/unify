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

use Mecum\Expand\ExpandableInterface;

/**
 * CollectionInterface
 *
 * @package  Mecum\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
interface CollectionInterface extends ExpandableInterface, \ArrayAccess
{
    public function operate(\Closure $callable);
    public function loop(\Closure $callable);
    public static function isCollection($data);
    public static function isIterable($data);
    public function append($data);
    public function exchangeArray($data);
}
