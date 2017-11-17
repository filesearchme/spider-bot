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

    protected $server_addr;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->server_addr = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:getHostByName(getHostName());
        $this->config = json_decode( file_get_contents(__DIR__.'/../config.json' ) );
        $this->client = new Client([
            'http_errors' => false,
            'headers' => [
                'User-Agent' => 'FileSearch.me Bot v'.$this->config->bot_version.' '.md5($this->server_addr)
            ]
        ]);
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
        global $argv;
        if( ( isset( $argv ) && $argv[1] == $this->config->unique_key ) || ( isset( $_GET['key'] ) && $_GET['key'] == $this->config->unique_key ) )
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
                    'identifier' => md5($this->server_addr),
                    'user_agent' => 'FileSearch.me Bot v'.$this->config->bot_version.' '.md5($this->server_addr)
                ],
                JSON_PRETTY_PRINT
            );
        }
        return false;
    }
}