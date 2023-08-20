<?php


namespace Support\Identify\Instance;



use Support\Identify\Structure\BetInfo;
use Support\Identify\Contracts\Identify;
use Support\Identify\IdentifyTrait;

class IdentifyHn implements Identify
{

    use IdentifyTrait;

    public function identify(string $message): BetInfo
    {
        // TODO: Implement identify() method.
    }


}