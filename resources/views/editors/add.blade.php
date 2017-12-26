@extends('layouts.admin.app')

@section('title',__('Добавить модератора'))
	
@section('content')
	<div class="panel panel-default">
		<div class="panel-body">
			@component('my.page-header',['title'=>__('Добавить модератора')]) @endcomponent
			<form class="form-horizontal" role="form" action="{{ route('save-editor') }}" method="post">
				{{ csrf_field() }}
				@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'name','title'=>__('Имя'),'errors'=>$errors])
					<input class="form-control" type="text" name="name">
				@endcomponent
				@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'email','title'=>'Email','errors'=>$errors])
					<input class="form-control" type="email" name="email">
				@endcomponent
				@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'password','title'=>__('Пароль'),'errors'=>$errors])
					<input class="form-control" type="text" name="password">
				@endcomponent
				@component("my.hr-form-slot",['lw'=>4,'fw'=>6])
					<input class="btn btn-primary" type="submit" value="@lang('Сохранить')">
				@endcomponent
			</form>
		</div>
	</div>
@endsection