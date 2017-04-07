@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    You are logged in!
                    @if(Entrust::hasRole('admin'))
                        <p>This is visible to users with the admin role</p>
                    @endif
                    @if(Entrust::hasRole('editor'))
                        <p>This is visible to users with the editor role</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
