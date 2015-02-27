<?php namespace App\Commands;


use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Vluzrmos\SlackApi\SlackApiFacade as SlackApi;

class SlackInvitation extends Command implements ShouldBeQueued, SelfHandling{
  use SerializesModels, InteractsWithQueue;

  protected $email = null;
  protected $username = null;
  protected $cacheTime = 5; //in minutes
  protected $configSpace = 'services.slack';

  public function __construct($email, $username = ''){
    $this->email = $email;
    $this->username = $username;
  }

  public function handle(){
    SlackApi::post('users.admin.invite', [
        'body' => [
            'email' => $this->getEmail(),
            'first_name' => $this->getUsername(),
            'set_active' => true,
            'channels' => $this->getConfigChannelsString(),
            '_attempts' => 1
        ]
    ]);
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
    $jsonObject = SlackApi::get('channels.list');

    $channels = array_filter($jsonObject['channels'], function($channel){
      return !$channel['is_archived'];
    });

    return $channels;
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
      return (in_array($channel['id'], $ids) or in_array($channel['name'], $ids));
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
      return $channel['id'];
    }, $channels);

    return implode(",", $channels);
  }

}