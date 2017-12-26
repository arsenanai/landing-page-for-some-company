@extends('layouts.admin.app')

@section('title',__('Модераторы'))

@section('content')
	<div class="panel panel-default" id="root">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<span class="form-inline">
						<a class="btn btn-success" href="{{ route('add-editor') }}">@lang("Добавить")</a>
					  <div class="form-group">
					    <input type="text" class="form-control" v-model="f"	>
					  </div>
					  <div class="form-group">
					    <select v-model="sc" class="form-control">
					    	<option value="name">@lang('по имени')</option>
					    	<option value="email">@lang('по email')</option>
					    </select>
					  </div>
					  <button @click="search" class="btn btn-default">@lang('Поиск')</button>
					  <span class="pull-right">
					  	<div class="form-group">
					  		<select class="form-control" title='@lang("Строки в странице")' v-model="sz" @change="request('size')">
							@for($i=10;$i<=100;$i=(($i<25)?($i+5):($i*2)))
								<option value="{{$i}}" @if($editors->perPage()==$i) selected @endif>{{$i}}</option>
							@endfor
							</select>
					  	</div>
					  	<div class="form-group">
					  		<select class="form-control" title='@lang("Page elements")' onchange="javascript:location.href = this.value;">
								<option selected disabled>{{$editors->firstItem()}} - {{$editors->lastItem()}} @lang('of') {{$editors->total()}}</option>
								<option value="{{$editors->url(1)}}">@lang('Первая')</option>
								<option value="{{$editors->url($editors->lastPage())}}">@lang('Последняя')</option>
							</select>
					  	</div>
						<span class="btn-group">
							<a class="btn btn-default" href="{{$editors->previousPageUrl()}}" 
							@empty($editors->previousPageUrl()) disabled @endempty>
							<i class="fa fa-chevron-left"></i>
							</a>
							<a class="btn btn-default" href="{{$editors->nextPageUrl()}}"
							@empty($editors->nextPageUrl()) disabled @endempty>
							<i class="fa fa-chevron-right"></i>
							</a>
						</span>
					  </span>
					</span>
				</div>
			</div>
			<br>
			@if($editors->count()>0)
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th @click="changeSort('sort','name')">
								@if($sr==='name') <em> @endif
								@lang("Имя")
								@if($sr==='name') </em> @endif
								<i class="fa @if($sr==='name' AND $o==='asc') fa-chevron-up 
								@elseif($sr==='name' AND $o==='desc') fa-chevron-down @endif"></i>
							</th>
							<th @click="changeSort('sort','email')">
								@if($sr==='email') <em> @endif
								@lang("Email")
								@if($sr==='email') </em> @endif
								<i class="fa @if($sr==='email' AND $o==='asc') fa-chevron-up 
								@elseif($sr==='email' AND $o==='desc') fa-chevron-down @endif"></i>
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
						@foreach($editors as $user)
							<tr>
								<td>{{$user->name}}</td>
								<td>{{$user->email}}</td>
								<td>{{$user->created_at}}</td>
								<td>
										<a class="btn btn-primary btn-xs" href="{{ route('edit-editor',['id' => $user->id]) }}">@lang("Правка")</a>
										<a class="btn btn-danger btn-xs" @@click='remove("{{ route('delete-editor',['id' => $user->id]) }}")'>@lang("Удалить")</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-info">
				@lang('Пустой результат')
			</div>
			@endif
			{{--<ul>
				<li>count: {{$editors->count()}}</li>
				<li>current page: {{$editors->currentPage()}}</li>
				<li>first item: {{$editors->firstItem()}}</li>
				<li>has more pages: {{$editors->hasMorePages()}}</li>
				<li>last item: {{$editors->lastItem()}}</li>
				<li>*last page: {{$editors->lastPage()}}</li>
				<li>next page url: {{$editors->nextPageUrl()}}</li>
				<li>per page: {{$editors->perPage()}}</li>
				<li>previous page url: {{$editors->previousPageUrl()}}</li>
				<li>*total: {{$editors->total()}}</li>
				<li>url(1): {{$editors->url(1)}}</li>
			</ul>--}}
		</div>
	</div>
@endsection

@section('script')
<script type="text/javascript">
	new Vue({
		el: "#root",
		data:{
			//index, size, sort, order, filter, scope
			i: {{$editors->currentPage()}},
			sz: {{$sz}},
			sr: '{{$sr}}',
			o: '{{$o}}',
			f: '{{$f}}',
			sc: '{{$sc}}',
			currentRoute: '/management/editors/page',
			currentDomain: '{{Request::root()}}'
		},
		methods:{
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
				this.sc = this.sc===''?'%23%23':this.sc;
				//management/editors/page/sz=20/sr=id/o=desc/f=44/sc=44?page=2
				window.location.href = this.currentDomain+this.currentRoute+"/sz="+this.sz+"/sr="+this.sr
				+"/o="+this.o+"/f="+this.f+"/sc="+this.sc+"?page="+this.i;
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
			this.sc = this.sc==='##'?'':this.sc;
		}
	});
</script>
@endsection