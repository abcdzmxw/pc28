<?php


namespace Support\Identify;


trait IdentifyTrait
{
    public function checkScore(string $message):bool
    {

        $exp = '查|查分|查钱|查信用';
        if ($this->_codeExp($exp, $message) != false) {

            return true;
        }
        return false;
    }

    public function checkReOrder(string $message): array
    {

        $matches = [];
        $bool = preg_match("#^退(\d+)$#", $message, $matches);
        if (!$bool) {
            return [false,''];
        }
        return [true,$matches[1]];
    }

    public function checkReQuantity(string $message): array
    {

        $matches = [];
        $bool = preg_match("#^(回|下)(\d+)$#", $message, $matches);
        if (!$bool) {
            return [false,''];
        }
        return [true,$matches[2]];
    }

    private function _codeExp($code, $message): bool
    {
        $codeArr = explode("|", $code);
        foreach ($codeArr as $exp) {
            if ($exp == $message) {
                return true;
            }
        }
        return false;
    }
}