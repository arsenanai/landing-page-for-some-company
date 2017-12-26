@extends('layouts.admin.app')

@section('title',__('Правка модератора'))
	
@section('content')
	@component('my.panel')
		@component('my.page-header',['title'=>__('Правка модератора')]) @endcomponent
		<form class="form-horizontal" role="form" action="{{ route('update-editor',['id' => $toEdit->id]) }}" 
		method="post">
			{{ csrf_field() }}
			@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'name','title'=>__('Имя'),'errors'=>$errors])
				<input class="form-control" type="text" name="name" value="{{ $toEdit->name }}">
			@endcomponent
			@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'email','title'=>'Email','errors'=>$errors])
				<input class="form-control" type="email" name="name" value="{{ $toEdit->email }}">
			@endcomponent
			@component("my.hr-form-slot",['lw'=>4,'fw'=>6])
				<input class="btn btn-primary" type="submit" value="@lang('Сохранить')">
			@endcomponent
		</form>
		<hr>
		<form class="form-horizontal" action="{{ route('reset-password',['id' => $toEdit->id]) }}" method="post">
			{{ csrf_field() }}
			@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'password','title'=>__('Пароль'),'errors'=>$errors])
				<input class="form-control" type="text" name="password">
			@endcomponent
			@component("my.hr-form-slot",['lw'=>4,'fw'=>6])
				<input class="btn btn-primary" type="submit" name="" value="@lang('Сбросить')">
			@endcomponent
		</form>
	@endcomponent('my.panel')
@endsection