@extends('layouts.admin.app')

@section('title',__('Dashboard'))

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">@lang('Dashboard')</div>
        <div class="panel-body">
            You are logged in!
            @if(Entrust::hasRole('admin'))
                <p>This is visible to users with the admin role</p>
            @endif
            @if(Entrust::hasRole('editor'))
                <p>This is visible to users with the editor role</p>
            @endif
            {{--@role('admin')
                <p>Another admin's content</p>
            @endrole --}}   
        </div>
    </div>
@endsection