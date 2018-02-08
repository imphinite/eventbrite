<?php

namespace Eventbrite\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Html\HtmlBuilder
 */
class Eventbrite extends Facade 
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    {
         return 'Eventbrite';
    }
}