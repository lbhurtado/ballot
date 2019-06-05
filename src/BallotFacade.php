<?php

namespace LBHurtado\Ballot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LBHurtado\Ballot\Skeleton\SkeletonClass
 */
class BallotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ballot';
    }
}
