@extends('layouts.admin.app')

@section('title',__('Изменить аккаунт'))

@section('content')
    <div>
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#account" aria-controls="account" role="tab" data-toggle="tab">@lang('Изменить аккаунт')</a></li>
        {{--<li role="presentation"><a href="#social" aria-controls="social" role="tab" data-toggle="tab">@lang('Social Networks')</a></li>--}}
        {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">@lang('Settings')</a></li>--}}
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="account">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('profile-save') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">@lang('Имя')</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('Email')</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('Сохранить')
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form class="form-horizontal" role=form method="POST" action="{{ route('password-change') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('old-password') ? ' has-error' : '' }}">
                            <label for="old-password" class="col-md-4 control-label">@lang('Текущий пароль')</label>
                            <div class="col-md-6">
                                <input id="old-password" type="password" class="form-control" name="old-password" value="" required>
                                @if ($errors->has('old-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('Новый пароль')</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">@lang('Подтверждение пароля')</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('Изменить')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--<div role="tabpanel" class="tab-pane" id="social">...</div>
        <div role="tabpanel" class="tab-pane" id="settings">...</div>--}}
      </div>

    </div>
@endsection