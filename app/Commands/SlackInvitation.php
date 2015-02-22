<?php namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SlackInvitation extends Command implements ShouldBeQueued, SelfHandling{
  use SerializesModels, InteractsWithQueue;

  protected $email = null;
  protected $username = null;
  protected $token = null;
  protected $cacheTime = 5; //in minutes
  protected $configSpace = 'services.slack';

  public function __construct($email, $username = 'User'){
    $this->email = $email;
    $this->username = $username;
    $this->token = $this->getConfig('token');
  }

  public function handle(){
    $team  = $this->getConfig('team');
    $teamUrlApi = "https://{$team}.slack.com/api/users.admin.invite";

    $options = [
      'query' => [
        't' => time()
      ],
      'body' => [
          'token' => $this->getToken(),
          'email' => $this->getEmail(),
          'first_name' => $this->getusername(),
          'set_active' => true,
          'channels' => $this->getConfigChannelsString(),
          '_attempts' => 1
      ]
    ];

    $this->postInvitation($teamUrlApi, $options);
  }

  /**
   * @return string
   */
  public function getToken(){
    return $this->token;
  }

  /**
   * @return string
   */
  public function getUsername(){
    return $this->username;
  }

  /**
   * @return string
   */
  public function getEmail(){
    return $this->email;
  }

  /**
   * Default Guzzle Client to connect with the Slack API
   * @return Client
   */
  public function getGuzzleClient(){
    $client = new Client();
    $client->setDefaultOption('verify', storage_path('/app/curl-ca-bundle.crt'));

    return $client;
  }

  /**
   * Send Post for invitation member
   * @param $url
   * @param $options
   * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
   */
  public function postInvitation($url, $options){
    return $this->getGuzzleClient()->post($url, $options);
  }

  /**
   * Get Configuration for a key with a default (if that doesn't exists)
   * @param $key
   * @param null $default
   * @return mixed
   */
  public function getConfig($key, $default=null){
    return Config::get($this->configSpace.".".$key, $default);
  }

  /**
   * Get array of channels
   * @return array
   */
  public function getApiChannels(){
    $token = $this->getToken();
    $cacheKey = "slack-api-channels-".$token;

    if(Cache::has($cacheKey)){
      return Cache::get($cacheKey);
    }

    $response = $this->getGuzzleClient()->get("https://slack.com/api/channels.list", ["query" => [
      "token" => $this->token
    ]]);

    $jsonObject = json_decode($response->getBody()->getContents());

    $channels = $jsonObject->channels;

    Cache::add($cacheKey, $channels, $this->cacheTime);

    return  $channels;
  }

  /**
   * Get channels by name or ID
   * @param array $ids
   * @return array
   */
  public function getApiChannelsById($ids=[]){
    if(is_string($ids)){
      $ids = preg_split('/, ?/', $ids);
    }

    $ids = (array) $ids;

    return array_filter($this->getApiChannels(), function($channel) use (&$ids){
      return (in_array($channel->id, $ids) or in_array($channel->name, $ids));
    });
  }

  /**
   * Get api configurated channels and parse to string to be used in a api request
   * @return string
   */
  public function getConfigChannelsString(){
    $channels = $this->getConfig('channels', '');

    if(in_array($channels, ['all', '*'])){
      $apiChannels = $this->getApiChannels();
    }
    else{
      $apiChannels = $this->getApiChannelsById($channels);
    }

    return $this->parseChannelsToString($apiChannels);
  }

  /**
   * Parse channels IDs to string to be used in a api request
   * @param array $channels
   * @return string
   */
  public function parseChannelsToString($channels = []){
    $channels = array_map(function($channel){
      return $channel->id;
    }, $channels);

    return implode(",", $channels);
  }

}