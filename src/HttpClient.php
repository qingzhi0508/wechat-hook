<?php

namespace Shawn\WeChatRobot;

use GuzzleHttp\Client;

class HttpClient
{
    protected $client;
    protected $config;
    /**
     * @var string
     */
    protected $hookUrl = "https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=";

    /**
     * @var string
     */
    protected $accessToken = "";

    public function __construct($config)
    {
        $this->config = $config;
        $this->setAccessToken();
        $this->client = $this->createClient();
    }

    /**
     *
     */
    public function setAccessToken()
    {
        $this->accessToken = $this->config['token'];
    }

    /**
     * create a guzzle client
     * @return Client
     */
    protected function createClient()
    {
        $client = new Client([
            'timeout' => $this->config['timeout'],
        ]);
        return $client;
    }

    /**
     * @return string
     */
    public function getRobotUrl()
    {
        return $this->hookUrl . $this->accessToken;
    }

    /**
     * send message
     * @param $params
     * @return array
     */
    public function send($params)
    {
        $request = $this->client->post($this->getRobotUrl(), [
            'body'    => json_encode($params,JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'verify'  => $this->config['verify']
        ]);

        $result = $request->getBody()->getContents();
        return json_decode($result, true);
    }
}
