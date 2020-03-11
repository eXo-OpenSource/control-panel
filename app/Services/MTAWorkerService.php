<?php

namespace App\Services;

class MTAWorkerService
{
    private $secret;
    private $host;
    private $port;
    private $client;
    private $url;

    public function __construct($env = '')
    {
        if($env !== 'test' && $env !== 'prod') {
            return;
        }

        $prefix = '';

        if($env === 'test')
            $prefix = '_TEST';

        $this->host = env('WORKER' . $prefix . '_HOST');
        $this->port = env('WORKER' . $prefix . '_PORT');
        $this->secret = env('WORKER' . $prefix . '_SECRET');

        $this->host = 'http://' . $this->host . ':' . $this->port . '/';

        $this->client = new \GuzzleHttp\Client();
    }

    public function start()
    {
        return $this->request('start');
    }

    public function restart()
    {
        return $this->request('restart');
    }

    public function stop()
    {
        return $this->request('stop');
    }

    public function logs()
    {
        return $this->request('logs');
    }

    private function request($action)
    {
        try {
            $response = $this->client->request('GET', $this->host . $action, [
                'headers' => [
                    'API_SECRET' => $this->secret,
                ]
            ]);

            if($response->getStatusCode() !== 200)
                return null;

            return json_decode($response->getBody()->getContents(), false, 512,JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
