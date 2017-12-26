<div class="form-group" @isset($vslot) {{ $vslot }} @endif >
    <div class="col-md-{{$fw=$fw===null?6:$fw}} col-md-offset-{{$lw=$lw===null?4:$lw}}">
        {{ $slot }}
    </div>
</div>