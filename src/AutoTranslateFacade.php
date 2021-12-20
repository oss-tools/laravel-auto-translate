<?php

namespace OSSTools\AutoTranslate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \OSSTools\AutoTranslate\Skeleton\SkeletonClass
 */
class AutoTranslateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auto-translate';
    }
}
