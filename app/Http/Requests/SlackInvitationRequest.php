<?php namespace App\Http\Requests;

class SlackInvitationRequest extends Request{

  public function rules(){
    return [
        'email' => 'required|email',
        'name'  => 'required|min_words:2'
    ];
  }

  public function messages(){
    return [
        'email.required'  => 'Você deve inserir um endereço de email.',
        'email.email'     => 'O endereço de email digitado é inválido.',
        'name.required'   => 'Digite seu nome para que os membros da comunidade possam reconhecer você.',
        'name.min'        => 'O nome deve conter ao menos :min caracteres.',
        'name.min_words'  => 'Por favor digite seu nome e sobrenome.'
    ];
  }

  public function authorize(){
    return true;
  }
}