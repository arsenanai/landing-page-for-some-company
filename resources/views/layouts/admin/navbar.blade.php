<nav class="navbar navbar-default navbar-static-top">
<div class="container">
    <div class="navbar-header">
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">@lang('Навигация')</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Branding Image -->
        <a class="navbar-brand" href="{{route('page',['id'=>'main','lang'=>App::getLocale()])}}">
            {{ App\Company::firstOrFail()->getShortName() }}
        </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="nav navbar-nav">
            {{--@foreach(App\Page::where('order','!=',1)->orderBy('order')->get() as $page)
                <li class="{{(strpos(Request::url(),'/'.explode('#',json_decode($page->content)->presentations[0]->id)[1]) !== false)?'active':''}}">
                    <a href="{{route('page',[
                    'id'=>explode('#',json_decode($page->content)->presentations[0]->id)[1],
                    'lang'=>App::getLocale()])}}">
                        {{$page->printName()}}
                    </a>
                </li>
            @endforeach
            <li class="{!!(strpos(Request::url(),'/pages/about-us') !== false)?'active':''!!}">
                <a href="{{route('about',['lang'=>App::getLocale()])}}">
                    @lang('About Us')
                </a>
            </li>
            <li class="{!!(strpos(Request::url(),'/pages/services') !== false)?'active':''!!}">
                <a href="{{route('services',['lang'=>App::getLocale()])}}">
                    @lang('Services')
                </a>
            </li>
            <li class="{!!(strpos(Request::url(),'/pages/news') !== false)?'active':''!!}">
                <a href="{{route('news',['lang'=>App::getLocale()])}}">
                    @lang('News')
                </a>
            </li>
            <li class="{!!(strpos(Request::url(),'/pages/contacts') !== false)?'active':''!!}">
                <a href="{{route('contacts',['lang'=>App::getLocale()])}}">
                    @lang('Contacts')
                </a>
            </li>--}}
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li><a href="{{ route('login') }}">@lang('Страница входа')</a></li>
            @else
                <li class="dropdown {!! (Request::url() == route('settings')) ? 'active' : '' !!}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li class="{!! (Request::url() == route('settings')) ? 'active' : '' !!}">
                            <a href="{{ route('settings') }}">
                                @lang('Настройки')
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                @lang('Выйти')
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
</nav>