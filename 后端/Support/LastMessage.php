<?php


namespace Support;


class LastMessage
{
    const LAST_MESSAGE = 'LAST_MESSAGE:';


    public function add($agentId, $roomId, array $message): int
    {
        $flag = $this->flag($agentId, $roomId);
        $lock = new TinLock($flag, 40);
        $lock->lock();
        $redis = redis();
        $len = $redis->zCount($flag, '0', (string)time());
        if ($len > 100) {
            $redis->zRemRangeByRank($flag, 0, $len - 100);
        }
        $lock->unLock();
        $res = $redis->zAdd($this->flag($agentId, $roomId), time(),json_encode($message));
        $redis->close();
        return $res;
    }

    public function gets($agentId, $roomId): array
    {
        $redis = redis();
        $sets = $redis->zRangeByScore($this->flag($agentId, $roomId), '0', (string)time());
        foreach ($sets as $key => $message) {
            $sets[$key] = json_decode($message,true);
        }
        $redis->close();
        return $sets;
    }

    public function len($agentId, $roomId): int
    {
        $redis = redis();
        $res = $redis->zCount($this->flag($agentId, $roomId),'0',(string)time());
        $redis->close();
        return  $res;
    }

    public function del($agentId, $roomId): int
    {
        $redis = redis();
        $res = $redis->del($this->flag($agentId, $roomId));
        $redis->close();
        return $res;
    }


    protected function flag($agentId, $roomId): string
    {
        return self::LAST_MESSAGE . $agentId . ':' . $roomId;
    }


}