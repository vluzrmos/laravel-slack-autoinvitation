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

    $this->dispatch(new SlackInvitation($request->input('email')));

    $invitationMessage = "Você receberá um e-mail de convite para a <a href=\"https://larachatbr.slack.com.br\"\>LaraChat Brasil</a>.";

    return redirect("/")->with(compact("invitationMessage"));
  }
}
