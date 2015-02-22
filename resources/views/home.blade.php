@extends('app')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>{{config('app.name')}}</h1>

            <p>Entre na comunidade {{config('services.slack.teamname')}} no Slack.</p>

            <p>
                @unless(Session::has('invitationMessage'))

            <form action="{{url("/invite")}}" method="POST" class="form-inline" role="form">
                <input type="hidden" name='_token' value="{!! csrf_token() !!}"/>

                <div class="input-group {{$errors->any()?'has-error':''}}">
                    <input type="text" class="form-control" name="email" id="" placeholder="Digite seu email">
                    <span class="input-group-btn">
                       <button type="submit" class="btn btn-success">Enviar convite</button>
                     </span>
                </div>
                <!-- /input-group -->
                @if($errors->any())
                    <div class="help-block ">
                        <span class="text-danger">{{$errors->first('email')}}</span>
                    </div>
                @endif
            </form>
            @else
                {!! Session::get('invitationMessage') !!}
                @endunless

                </p>
        </div>
    </div>
@endsection
