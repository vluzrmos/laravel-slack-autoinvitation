<?php namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SlackInvitation extends Command implements ShouldBeQueued, SelfHandling{
  use SerializesModels, InteractsWithQueue;

  protected $email = null;
  protected $configSpace = 'services.slack';

  public function __construct($email){
    $this->email = $email;
  }

  public function handle(){
    $team  = $this->getConfig('team');
    $teamUrlApi = "https://{$team}.slack.com/api/users.admin.invite";

    $options = [
      'query' => [
        't' => time()
      ],
      'body' => [
          'token' => $this->getConfig('token', ''),
          'email' => $this->email,
          'first_name' => 'User',
          'set_active' => true,
          'channels' => $this->getConfig('channels'),
          '_attempts' => 1
      ]
    ];

    $this->postInvitation($teamUrlApi, $options);
  }

  public function postInvitation($url, $options){
    $client = new Client();
    $client->setDefaultOption('verify', storage_path('/app/curl-ca-bundle.crt'));

    return $client->post($url, $options);
  }

  public function getConfig($key, $default=null){
    return Config::get($this->configSpace.".".$key, $default);
  }

}