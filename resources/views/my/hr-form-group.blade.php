<div class="form-group{{ $errors->has($id) ? ' has-error' : '' }}" @isset($args) {{$args}} @endisset>
    <label for="name" class="col-md-{{$lw=$lw===null?4:$lw}} control-label">
        {{ $title=$title===null?"":$title }}
    </label>
    <div class="col-md-{{$fw=$fw===null?6:$fw}}">
        {{ $slot }}
        @if ($errors->has($id))
            <span class="help-block">
                <strong>{{ $errors->first($id) }}</strong>
            </span>
        @endif
    </div>
</div>