<?php


namespace Support\Identify\Instance;



use Support\Identify\Structure\BetInfo;

use Support\Identify\Contracts\Identify;
use Support\Identify\IdentifyTrait;

class IdentifyDanDan implements Identify
{
    use IdentifyTrait;

    private string $message = '';
    private BetInfo $betInfo;
    public const PAY_CLASS = [
        1 => '高',
        2 => '低',
        3 => '多',
        4 => '空',
        //11 => '多单',
        //12 => '多双',
        //13 => '空单',
        //14 => '空双',
        //21 => '极阴',
        //22 => '极阳',
        31 => '豹',
        32 => '顺',
        33 => '对',

        //'小 0-13 多 27'
    ];
    public const PAY_CLASS_SINGLE = [
        [
            41 => '0',
            42 => '1',
            43 => '2',
            44 => '3',
            45 => '4',
            46 => '5',
            47 => '6',
            48 => '7',
            49 => '8',
            410 => '9',
            411 => '10',
            412 => '11',
            413 => '12',
            414 => '13',
        ],
        41 => '27',
        42 => '26',
        43 => '25',
        44 => '24',
        45 => '23',
        46 => '22',
        47 => '21',
        48 => '20',
        49 => '19',
        410 => '18',
        411 => '17',
        412 => '16',
        413 => '15',
        414 => '14',
    ];

    public const PAY_CLASS_SINGLE1 = [

            41 => '0',
            42 => '1',
            43 => '2',
            44 => '3',
            45 => '4',
            46 => '5',
            47 => '6',
            48 => '7',
            49 => '8',
            410 => '9',
            411 => '10',
            412 => '11',
            413 => '12',
            414 => '14',
            415 => '15',
            416 => '16',
            417 => '17',
            418 => '18',
            419 => '19',
            420 => '20',
            421 => '21',
            422 => '22',
            423 => '23',
            424 => '24',
            425 => '25',
            426 => '26',
            427 => '27',
    ];
    const NUM_CODE = [
        '多' => '15 17 19 21 23 25 27',
        '空' => '0 1 3 5 6 7 9 11 13',
        '低' => '2 4 6 8 10 12',
        '高' => '14 16 18 20 22 24 26'
        //'空单' => '0 1 3 5 6 7 9 11 13',
        //'空双' => '2 4 6 8 10 12',
        //'多单' => '15 17 19 21 23 25 27',
        //'多双' => '14 16 18 20 22 24 26',
        //'极阴' => '0 1 2 3 4 5',
        //'极阳' => '22 23 24 25 26 27'
    ];

    public function identify(string $message): BetInfo
    {
        // TODO: Implement identify() method.
        $this->message = $message;
        $this->betInfo = new BetInfo();
        if ($this->checkBasics())
            return $this->betInfo;
        elseif ($this->checkCombination())
            return $this->betInfo;
        elseif ($this->checkExtreme())
            return $this->betInfo;
        elseif ($this->checkSpecial())
            return $this->betInfo;
        elseif ($this->checkSingle())
            return $this->betInfo;
        return $this->betInfo;
    }

    /**
     * 基础的 大空单双
     */
    private function checkBasics(): bool
    {
        $matches = [];
        $bool = preg_match("#^(单|双|大|小)(\d+)$#", $this->message, $matches);
        if (!$bool) {
            if(!preg_match("#^(\d+)(单|双|大|小)$#", $this->message, $matches)) return false;
            [$matches[1],$matches[2]] = [$matches[2],$matches[1]];
        }
        $this->betInfo->isTrue = true;
        $this->betInfo->PlayCode = self::NUM_CODE[$matches[1]] ?? '';
        $this->betInfo->PlayClass = array_flip(self::PAY_CLASS)[$matches[1]];
        $this->betInfo->PlayMethod = $matches[1];
        $this->betInfo->PlayCodeCount = count(explode(' ', self::NUM_CODE[$matches[1]] ?? ''));
        $this->betInfo->Quantity = $matches[2];
        return true;
    }

    /**
     * 组合
     */
    private function checkCombination(): bool
    {
        $matches = [];
        $bool = preg_match("#^(大单|大双|小单|小双)(\d+)$#", $this->message, $matches);
        if (!$bool) {
            if(!preg_match("#^(\d+)(大单|大双|小单|小双)$#", $this->message, $matches)) return false;
            [$matches[1],$matches[2]] = [$matches[2],$matches[1]];
        }
        $this->betInfo->isTrue = true;
        $this->betInfo->PlayCode = self::NUM_CODE[$matches[1]] ?? '';
        $this->betInfo->PlayClass = array_flip(self::PAY_CLASS)[$matches[1]];
        $this->betInfo->PlayMethod = $matches[1];
        $this->betInfo->PlayCodeCount = count(explode(' ', self::NUM_CODE[$matches[1]] ?? ''));
        $this->betInfo->Quantity = $matches[2];
        return true;
    }

    /**
     * 极值
     */
    private function checkExtreme(): bool
    {
        $matches = [];
        $bool = preg_match("#^(极大|极小)(\d+)$#", $this->message, $matches);
        if (!$bool) {
            if(!preg_match("#^(\d+)(极大|极小)$#", $this->message, $matches)) return false;
            [$matches[1],$matches[2]] = [$matches[2],$matches[1]];
        }
        $this->betInfo->isTrue = true;
        $this->betInfo->PlayCode = self::NUM_CODE[$matches[1]] ?? '';
        $this->betInfo->PlayClass = array_flip(self::PAY_CLASS)[$matches[1]];
        $this->betInfo->PlayMethod = $matches[1];
        $this->betInfo->PlayCodeCount = count(explode(' ', self::NUM_CODE[$matches[1]] ?? ''));
        $this->betInfo->Quantity = $matches[2];
        return true;
    }

    /**
     * 豹顺对
     */
    private function checkSpecial(): bool
    {
        $matches = [];
        $bool = preg_match("#^(豹|顺|对)(子)?(\d+)$#", $this->message, $matches);
        if (!$bool) {
            if(!preg_match("#^(\d+)(豹|顺|对)(子)?$#", $this->message, $matches)) return false;
            [$matches[1],$matches[3]] = [$matches[3],$matches[1]];
        }
        $this->betInfo->isTrue = true;
        $this->betInfo->PlayCode = '';
        $this->betInfo->PlayClass = array_flip(self::PAY_CLASS)[$matches[1]];
        $this->betInfo->PlayMethod = $matches[1];
        $this->betInfo->PlayCodeCount = count(explode(' ', self::NUM_CODE[$matches[1]] ?? ''));
        $this->betInfo->Quantity = $matches[3];
        return true;
    }

    /**
     * 特码
     */
    private function checkSingle(): bool
    {
        $matches = [];
        $bool = preg_match("#^(\d+)(T|点|操|艹|草|\.)(\d+)$#", $this->message, $matches);
        if (!$bool) {
            return false;
        }
        if ($matches[1] > 27 or $matches[1] < 0) {
            return false;
        }
        $this->betInfo->isTrue = true;
        $this->betInfo->PlayClass = array_flip(self::PAY_CLASS_SINGLE[0])[$matches[1]] ?? array_flip(self::PAY_CLASS_SINGLE[1])[$matches[1]];
        $this->betInfo->PlayMethod = '特码'.$matches[1];
        $this->betInfo->PlayCodeCount = 1;
        $this->betInfo->Quantity = $matches[3];
        $this->betInfo->PlayCode = $matches[1];
        return true;
    }

}