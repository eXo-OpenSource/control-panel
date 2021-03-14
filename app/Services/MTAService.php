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

    public function prisonPlayer($adminId, $targetId, $duration, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKPrisonPlayer', $adminId, $targetId, $duration, $reason);
    }

    public function unprisonPlayer($adminId, $targetId, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKUnprisonPlayer', $adminId,  $targetId, $reason);
    }

    public function addWarn($adminId, $targetId, $duration, $reason)
    {
        return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKAddWarn', $adminId,  $targetId, $duration, $reason);
    }

    public function removeWarn($adminId, $targetId, $warnId)
    {
        try {
            return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKRemoveWarn', $adminId,  $targetId, $warnId);
        } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
            return [];
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            return [];
        }
    }

    public function takeScreenShot($userId)
    {
        try {
            return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKTakeScreenShot', $userId);
        } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
            return [];
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            return [];
        }
    }

    public function getOnlinePlayers()
    {
        try {
            return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKSendOnlinePlayers');
        } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
            return [];
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            return [];
        }
    }

    public function sendChatBox($type, $target, $message, $r, $g, $b)
    {
        try {
            return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKSendChatBox', $type, $target, $message, $r, $g, $b);
        } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
            return [];
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            return [];
        }
    }

    public function sendMessage($targetType, $targetId, $message, $options = [])
    {
        try {
            return $this->mta->getResource(env('MTA_SERVER_RESOURCE'))->call('phpSDKSendMessage', $targetType, $targetId, $message, $options);
        } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
            return [];
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            return [];
        }
    }
}
