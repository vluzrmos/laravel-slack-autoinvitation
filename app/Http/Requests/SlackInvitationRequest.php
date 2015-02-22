<?php namespace App\Http\Requests;

class SlackInvitationRequest extends Request{

  public function rules(){
    return [
      'email' => 'required|email'
    ];
  }

  public function messages(){
    return [
      'email.required' => 'Você deve inserir um endereço de email.',
      'email.email' => 'O endereço de email digitado é inválido.'
    ];
  }

  public function authorize(){
    return true;
  }
}