@extends('layouts.admin.app')

@section('title',__('Email example'))

@section('content')
    @component('my.panel')
        @component('my.page-header',['title'=>__('Email example')]) @endcomponent
        <form class="form-horizontal" role="form" action="{{route('send-mail')}}" 
        method="post" id="my-form">
            {{ csrf_field() }}
            @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'name','title'=>__('FIO'),'errors'=>$errors])
                <input class="form-control" type="text" name="name" value="">
            @endcomponent
            @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'phone','title'=>__('Phone'),'errors'=>$errors])
                <input class="form-control" type="tel" name="phone" value="">
            @endcomponent
            @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'email','title'=>'Email','errors'=>$errors])
                <input class="form-control" type="email" name="email" value="">
            @endcomponent
            @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'msg','title'=>__('Message'),'errors'=>$errors])
                <textarea class="form-control" name="msg" value=""></textarea>
            @endcomponent
            @component("my.hr-form-slot",['lw'=>4,'fw'=>6])
            <button
                class="btn btn-primary g-recaptcha"
                type="submit"
                data-sitekey="6LfQQScUAAAAAAz3qg3tAXT1zfJ5w4vH3eBoM67B"
                data-callback="submitFn">
            Submit
            </button>
            @endcomponent
        </form>
    @endcomponent('my.panel')
@endsection

@section('script')
    <script type="text/javascript">
        function submitFn(){
            document.getElementById("my-form").submit();
        }
    </script>
@endsection