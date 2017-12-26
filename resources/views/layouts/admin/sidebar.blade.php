<ul class="list-group">
    @if(Entrust::can('manage_users'))
        <a class="list-group-item @if(Route::currentRouteName()==='editors') active @endif"
            href="{{ route('editors',['sz'=>20,'sr'=>'created_at','o'=>'desc','f'=>'%23%23','sc'=>'name']) }}">
                @lang('Модераторы')
        </a>
    @endif
    @if(Entrust::can('manage_content'))
        <a class="list-group-item @if(Route::currentRouteName()==='site-content') active @endif" href="{{route('site-content')}}">@lang('Сведение о сайте')</a>
        <a class="list-group-item @if(Route::currentRouteName()==='news-page') active @endif" href="{{ route('news-page',['category'=>'all','sz'=>20,'sr'=>'created_at','o'=>'desc','filter'=>'%23%23']) }}">
          @lang('Новости')
        </a>
        @foreach(App\Page::orderBy('order','asc')->get() as $page)
            <a class="list-group-item @if(Request::path()==='content/page-content/pid='.$page->id) active @endif"
            href="{{route('page-content',['id'=>$page->id])}}">
                @lang('Страница'): {{$page->printName()}}
            </a>
        @endforeach
    @endif
</ul>