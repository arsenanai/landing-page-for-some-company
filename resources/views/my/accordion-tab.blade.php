<div class="panel panel-default" @isset($args) {{$args}} @endisset>
  <div class="panel-heading" role="tab" id="heading{{$id}}">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" data-parent="#{{$aid}}" href="#collapse{{$id}}"  aria-controls="collapse{{$id}}" 
      @if($open==true)
      aria-expanded="true"
      @else
      aria-expanded="true" class="collapsed"
      @endif
      >
        {{$title}}
      </a>
    </h4>
  </div>
  <div id="collapse{{$id}}" class="panel-collapse collapse @if($open==true) in @endif" role="tabpanel" aria-labelledby="heading{{$id}}">
    <div class="panel-body">
      {{$slot}}
    </div>
  </div>
</div>