<?php


namespace Support\Identify\Contracts;



use Support\Identify\Structure\BetInfo;

interface Identify
{
    /**
     * 订单识别
     * @param string $message
     * @return \Support\Identify\Structure\BetInfo
     */
    public function identify(string $message):BetInfo;

    /**
     * 识别查分
     * @param string $message
     * @return bool
     */
    public function checkScore(string $message):bool;

    /**
     * 识别退单
     * @param string $message
     * @return array
     */
    public function checkReOrder(string $message):array;

    /**
     * 识别下分
     * @param string $message
     * @return array
     */
    public function checkReQuantity(string $message): array;
}