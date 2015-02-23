<?php namespace App\Http\Controllers;


use App\Commands\SlackInvitation;
use App\Http\Requests\SlackInvitationRequest;

class HomeController extends Controller {


	/**
	 *
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home');
	}


  public function invite(SlackInvitationRequest $request){

    $this->dispatch(new SlackInvitation(
      $request->input('email'),
      $request->input('name')
    ));

    $team =  config('services.slack.team');
    $title = config('services.slack.teamname');

    $invitationMessage = "Você receberá um e-mail de convite para o Slack <a href=\"https://{$team}.slack.com.br\"\>{$title}</a>.";

    return redirect("/")->with(compact("invitationMessage"));
  }
}
