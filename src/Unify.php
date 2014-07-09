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

use Mecum\Expand\Manager;
use Mecum\Expand\ManagerInterface;

use Mecum\Unify\Extension\XmlLibrary;
use Mecum\Unify\Extension\JsonLibrary;
use Mecum\Unify\Extension\YamlLibrary;
use Mecum\Unify\Extension\MathLibrary;
use Mecum\Unify\Extension\DebugLibrary;
use Mecum\Unify\Extension\OperationLibrary;

/**
 *  Unify
 *
 * @package  Mecum\Unify
 * @author   Rudy ONFROY <rudy@onfroy.net>
 */
class Unify
{
    const MSG_INVALID_EXTENSION_PARAM = 'The extensions parameter is invalide';
    
    /**
     *  Create a new collection
     *
     *  @param \Mecum\Component\Expand\ManagerInterface $em
     *
     *  @return \Mecum\Component\Unify\Collection  
     */
    public static function create($ex = null, $data = [])
    {
        if ($ex instanceof ManagerInterface) {
        
            // Create an unify collection with the specified extension manager
            $unify = Collection::create($ex);
            
        } elseif (is_array($ex)) {
        
            // Create an unify collection with specified default packages of extensions
            $unify = self::createWith($ex);
            
        } elseif (is_string($ex)) {
        
            // Create an unify collection with one default packages of extensions
            $unify = self::createWith([$ex]);
            
        } elseif (true === $ex) {
        
             // Create an unify collection with all default package of extensions
             $unify = self::createFull();
             
        } elseif (false === $ex or is_null($ex)) {
        
            // Create an unify collection without any extensions
            $unify = self::createLess();
            
        } else {
        
            throw new \InvalidArgumentException(self::MSG_INVALID_EXTENSION_PARAM, 120);
        }
        
        // add data
        if ([] !== $data) {
            $unify->append($data);
        }
        
        return $unify;
    }
    
    /**
     *  Create an unify collection with all default package of extensions
     *
     *  @return \Mecum\Component\Unify\Collection  
     */
    public static function createFull()
    {
        return Collection::create(
            Manager::create()
               ->add(JsonLibrary::getAll())
               ->add(XmlLibrary::getAll())
               ->add(YamlLibrary::getAll())
               ->add(MathLibrary::getAll())
               ->add(DebugLibrary::getAll())
               ->add(OperationLibrary::getAll())
        );
    }
    
    /**
     * Create an unify collection without any extensions
     *
     *  @return \Mecum\Component\Unify\Collection 
     */
    public static function createLess()
    {
        return Collection::create(Manager::create());
    }
    
    /**
     *  Create an unify collection with specified default packages of extensions
     *
     *  @return \Mecum\Component\Unify\Collection
     */
    public static function createWith(array $libraries)
    {
        $em = Manager::create();

        // add all library specified in the input array
        foreach ($libraries as $library) {
            switch (strtolower($library)) {
            
                case 'json':
                    $em->add(JsonLibrary::getAll());
                    break;
                
                case 'xml':
                    $em->add(XmlLibrary::getAll());
                    break;
                    
                case 'yaml':
                    $em->add(YamlLibrary::getAll());
                    break;
                    
                case 'operation':
                    $em->add(OperationLibrary::getAll());
                    break;
                    
                case 'math':
                    $em->add(MathLibrary::getAll());
                    break;
                
                case 'debug':
                    $em->add(DebugLibrary::getAll());
                    break;
            }
        }

        return Collection::create($em);
    }
}
