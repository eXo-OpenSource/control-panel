<?php

namespace App\Services;

use MultiTheftAuto\Sdk\Mta;
use MultiTheftAuto\Sdk\Model\Server;
use MultiTheftAuto\Sdk\Model\Authentication;

class MTAService
{
    private $mta;

    public function __construct()
    {
        $server = new Server(env('MTA_SERVER_IP'), env('MTA_SERVER_PORT'));
        $auth = new Authentication(env('MTA_SERVER_USERNAME'), env('MTA_SERVER_PASSWORD'));
        $this->mta = new Mta($server, $auth);
    }

    public function kickPlayer($adminId, $targetId, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKKickPlayer', $adminId, $targetId, $reason);
    }

    public function banPlayer($adminId, $targetId, $duration, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKBanPlayer',$adminId, $targetId, $duration, $reason);
    }

    public function unbanPlayer($adminId, $targetId, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKUnbanPlayer', $adminId,  $targetId, $reason);
    }

    public function addWarn($adminId, $targetId, $duration, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKAddWarn', $adminId,  $targetId, $duration, $reason);
    }

    public function removeWarn($adminId, $targetId, $warnId)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKRemoveWarn', $adminId,  $targetId, $warnId);
    }

    public function takeScreenShot($userId)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKTakeScreenShot', $userId);
    }

    public function getOnlinePlayers()
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKSendOnlinePlayers');
    }
}
