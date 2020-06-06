<?php

namespace App\Services;

use GuzzleHttp\Client;

class ForumService
{
    private $client;
    private $endpoint;
    private $secret;

    public function __construct($endpoint, $secret)
    {
        $this->client = new Client();
        $this->endpoint = $endpoint;
        $this->secret = $secret;
    }

    public function sendNotification($userId, $title, $message, $url, $email = false)
    {
        try {
            $this->client->post($this->endpoint . '?user-api&method=notification', [
                'form_params' => [
                    'secret' => $this->secret,
                    'userID' => $userId,
                    'title' => $title,
                    'message' => $message,
                    'url' => $url,
                    'email' => $email,
                ]
            ]);

            return true;
        } catch (GuzzleException $exception) {
            return false;
        }
    }
}
