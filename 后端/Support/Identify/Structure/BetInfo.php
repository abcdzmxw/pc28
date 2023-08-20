<?php


namespace Support\Identify\Structure;


/**
 * Class BetInfo
 * @package App\Support\Identify\Structure
 */
class BetInfo
{
    public bool $isTrue = false;

    public string $issue;

    public int $game_id;

    public int $room_id;

    public int $game_odds_source;

    public string $PlayCode;

    public string $PlayMethod;

    public string $PlayCodeCount;

    public string $PlaySite;

    public float $Quantity;

    public string $PlayClass;

    public string $odds;
}