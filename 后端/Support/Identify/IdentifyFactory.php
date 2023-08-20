<?php


namespace Support\Identify;


use Support\Identify\Instance\IdentifyDanDan;
use Support\Identify\Instance\IdentifyHn;
use Support\identify\Contracts\Identify;


class IdentifyFactory
{

    private static array $GAME_IDENTIFY = [
        IdentifyDanDan::class,
        IdentifyDanDan::class,
        IdentifyDanDan::class,
        IdentifyHn::class
    ];

    public static function makeIdentify($gameId):Identify
    {
        return new self::$GAME_IDENTIFY[$gameId]();
    }

}