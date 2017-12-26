@extends('layouts.admin.app')

@section('title',__('Новости'))

@section('content')
	@component('my.panel')
    	@component('my.page-header',['title'=>__('Новости').': ']) @endcomponent
		<div id="root">
		<div class="row">
			<div class="col-md-12">
				<span class="form-inline">
					<a class="btn btn-success" href="{{ route('add-news') }}">@lang("Добавить")</a>
					<div class="form-group">
						<select class="form-control" v-model="selectedCategory" @@change="request">
              <option value="all">@lang('Все')</option>
              <option value="main">@lang('Основные')</option>
              <option value="smi">@lang('СМИ о нас')</option>
						</select>
					</div>
				  <div class="form-group">
				    <input type="text" class="form-control" v-model="f">
				  </div>
				  <button @click="search" class="btn btn-default">@lang('Поиск')</button>
				  <span class="pull-right">
				  	<div class="form-group">
				  		<select class="form-control" title='@lang("Строки в странице")' v-model="sz" @change="request('size')">
						@for($i=10;$i<=100;$i=(($i<25)?($i+5):($i*2)))
							<option value="{{$i}}" @if($news->perPage()==$i) selected @endif>{{$i}}</option>
						@endfor
						</select>
				  	</div>
				  	<div class="form-group">
				  		<select class="form-control" title='@lang("Page elements")' onchange="javascript:location.href = this.value;">
							<option selected disabled>{{$news->firstItem()}} - {{$news->lastItem()}} @lang('of') {{$news->total()}}</option>
							<option value="{{$news->url(1)}}">@lang('Первая')</option>
							<option value="{{$news->url($news->lastPage())}}">@lang('Последняя')</option>
						</select>
				  	</div>
					<span class="btn-group">
						<a class="btn btn-default" href="{{$news->previousPageUrl()}}" 
						@empty($news->previousPageUrl()) disabled @endempty>
						<i class="fa fa-chevron-left"></i>
						</a>
						<a class="btn btn-default" href="{{$news->nextPageUrl()}}"
						@empty($news->nextPageUrl()) disabled @endempty>
						<i class="fa fa-chevron-right"></i>
						</a>
					</span>
				  </span>
				</span>
			</div>
		</div>	
			<br>
		@if($news->count()>0)
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th @click="changeSort('sort','title')">
								@if($sr==='title') <em> @endif
								@lang("Новость")
								@if($sr==='title') </em> @endif
								<i class="fa @if($sr==='title' AND $o==='asc') fa-chevron-up 
								@elseif($sr==='title' AND $o==='desc') fa-chevron-down @endif"></i>
							</th>
							<th @click="changeSort('sort','created_at')">
							@if($sr==='created_at') <em> @endif
							@lang("Добавлен")
							@if($sr==='created_at') </em> @endif
							<i class="fa @if($sr==='created_at' AND $o==='asc') fa-chevron-up 
								@elseif($sr==='created_at' AND $o==='desc') fa-chevron-down @endif"></i>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
							<tr v-for="nn in news">
								<td>
										@{{ textify(nn.content.title_ru) }}
								</td>
								<td>@{{ nn.createdAt }}</td>
								<td>
										<a class="btn btn-primary btn-xs" :href="nn.editLink">@lang("Правка")</a>
										<a class="btn btn-danger btn-xs" @@click='remove(nn.deleteLink)'>@lang("Удалить")</a>
								</td>
							</tr>
					</tbody>
				</table>
				</div>
			</div>
		@else
			<div class="alert alert-info">
				@lang('Пустой результат')
			</div>
		@endif
		</div>
    @endcomponent	
@endsection

@section('script')
<script type="text/javascript">
  Vue.config.devtools = true;
	new Vue({
		el: "#root",
		data:{
			i: {{$news->currentPage()}},
			sz:{{$sz}},
			sr:'{{$sr}}',
			o:'{{$o}}',
			f:'{{$f}}',
			selectedLanguage:2,
			selectedCategory:'{{$category}}',
			languages:[],
			news: [
				@foreach($news as $new)
        {
          content: {!! $new->content !!},
					editLink: "{{ route('edit-news',['id' => $new->id]) }}",
					deleteLink: "{{ route('delete-news',['id' => $new->id]) }}",
					createdAt: '{{ $new->created_at }}',
        },
				@endforeach
			],
			currentRoute: '/content/news',
			currentDomain: '{{Request::root()}}'
		},
		methods:{
		  textify(html){
        return html.replace(/<[^>]*>/g, "");
			},
			changeSort(changed,value){
				this.o = (this.sr===value)?(this.o==='asc')?'desc':'asc':'asc';
				this.sr = value;
				//this.i=1;
				this.request();
			},
			search(){
				this.i=1;
				this.request();
			},
			request(){
				this.f = this.f===''?'%23%23':this.f;
				window.location.href = this.currentDomain+this.currentRoute+"/category="+this.selectedCategory+"/sz="+this.sz+"/sr="+this.sr
				+"/o="+this.o+"/f="+this.f+"?page="+this.i;
			},
			remove(link){
                var r = confirm("@lang('Вы уверены?')");
                if (r == true) {
					window.location.href = link;
                }
			}
		},
		created(){
			this.f = this.f==='##'?'':this.f;
		},
	});
</script>
@endsection