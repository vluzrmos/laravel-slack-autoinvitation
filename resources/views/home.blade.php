@extends('app')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>{{config('app.name')}}</h1>

            <p>Entre na comunidade {{config('services.slack.teamname')}} no Slack.</p>

            @unless(Session::has('invitationMessage'))

            <form action="{{url("/invite")}}" method="POST" class="form-inline" role="form">
                <input type="hidden" name='_token' value="{!! csrf_token() !!}"/>

                <div class="form-group {{$errors->has('name')?'has-error':''}}">
                    <input type="text" class="form-control" name="name" placeholder="Nome e sobrenome" required>
                </div>

                <div class="input-group {{$errors->has('email')?'has-error':''}}">
                    <input type="email" class="form-control" name="email" placeholder="seu.email@example.com" required>
                    <span class="input-group-btn">
                       <button type="submit" class="btn btn-primary">Solicitar convite</button>
                     </span>
                </div>

                @if($errors->has('name'))
                    <div class="help-block ">
                        <span class="text-danger">{{$errors->first('name')}}</span>
                    </div>
                @endif

                @if($errors->has('email'))
                    <div class="help-block ">
                        <span class="text-danger">{{$errors->first('email')}}</span>
                    </div>
                @endif
            </form>

            @else
                <p>{!! Session::get('invitationMessage') !!}</p>
            @endunless


        </div>
    </div>
@endsection
