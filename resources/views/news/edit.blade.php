@extends('layouts.admin.app')

@section('title',__('Редактировать новость'))

@section('content')
	<div id="news-form">
		@component('my.panel')
			@component('my.page-header',['title'=>__('Редактировать новость')]) @endcomponent
			@include('news.form',['link'=>route("update-news",['id'=>$new->id])])
		@endcomponent
	</div>
	@include('images.image')
@endsection

@section('script')
	<script type="text/javascript">
	  var form = 'edit';
	  var nid = {{$new->id}};
    var nw = {!! $new->content !!};
	</script>
	@include('news.script')
@endsection

@section('style')
	@include('images.image-style')
@endsection