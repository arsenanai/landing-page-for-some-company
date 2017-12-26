@extends('layouts.admin.app')

@section('title',__('Добавить новость'))

@section('content')
	<div id="news-form">
		@component('my.panel')
			@component('my.page-header',['title'=>__('Добавить новость')]) @endcomponent
			@include('news.form',['link'=>route("save-news")])
		@endcomponent
	</div>
	@include('images.image')
@endsection

@section('script')
	<script type="text/javascript">
    var form = "add";
    var nid = -1;
		var nw = {
      category:"main",
      title_ru:"",
      body_ru:"",
      fields:[
        {
          label:"@lang('Фото')",
          id:"field#file",
          translatable:false,
          type:"image",
          order:1,
          args:"",
          value:"",
        },
        {
          label:"@lang('Категория')",
          id:"field#category",
          translatable:false,
          type:"select",
          options:[
            {key:'main',value:'@lang("Основные")'},
            {key:'smi',value:'@lang("СМИ о нас")'},
          ],
          order:2,
          args:"",
          value:"main",
        },
        {
          label:"@lang('Заголовок')",
          id:"field#title",
          translatable:true,
          type:"textarea",
          order:3,
          args:"required",
          values:[
							@foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: '',
            },
						@endforeach
          ],
        },
        {
          label:"@lang('Краткое содержание')",
          id:"field#short",
          translatable:true,
          type:"textarea",
          order:4,
          args:"required",
          values:[
							@foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: '',
            },
						@endforeach
          ],
        },
        {
          label:"@lang('Лид')",
          id:"field#lid",
          translatable:true,
          type:"textarea",
          order:5,
          args:"required",
          values:[
							@foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: '',
            },
						@endforeach
          ],
        },
        {
          label:"@lang('Содержание')",
          id:"field#body",
          translatable:true,
          type:"textarea",
          order:6,
          args:"required",
          values:[
							@foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: '',
            },
						@endforeach
          ],
        },
        {
          label:"@lang('Ссылка')",
          id:"field#slug",
          translatable:false,
          type:"test",
          order:7,
          args:"required",
          value:"",
        },
      ],
    };
	</script>
	@include('news.script')
@endsection

@section('style')
	@include('images.image-style')
@endsection