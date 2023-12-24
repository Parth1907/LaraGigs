<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ListingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\ListingService';
    }
}