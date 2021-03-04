<?php

namespace Rir\PaymentProviders\Uniteller\Facades;

use Illuminate\Support\Facades\Facade;

class UnitellerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'uniteller';
    }
}
