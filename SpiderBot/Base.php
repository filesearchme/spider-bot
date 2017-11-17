<?php
namespace SpiderBot;
use GuzzleHttp\Client;
use \SpiderBot\Api\Work;
/**
 *
 */
class Base
{
    protected $api_url = "http://spider.filesearch.me/api/jobs/";

    protected $config;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'http_errors' => false,
            'headers' => [
                'User-Agent' => 'FileSearch.me Bot v'.$this->config->bot_version.' '.md5($_SERVER['SERVER_ADDR'])
            ]
        ]);
        $this->config = json_decode( file_get_contents(__DIR__.'/../config.json' ) );
    }

    /**
     * @param $url
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function fetch($url)
    {
        $res = $this->client->request('GET', $url);
        return $res->getStatusCode() == 200 ? $res->getBody() : '';
    }

    public function run()
    {
        if( isset( $_GET['key'] ) && $_GET['key'] == $this->config->unique_key )
        {
            $work = new Work;
            $work->fire();
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(
                [
                    'bot' => 'FileSearch.me',
                    'version' => $this->config->bot_version,
                    'identifier' => md5($_SERVER['SERVER_ADDR']),
                    'user_agent' => 'FileSearch.me Bot v'.$this->config->bot_version.' '.md5($_SERVER['SERVER_ADDR'])
                ],
                JSON_PRETTY_PRINT
            );
        }
        return false;
    }
}