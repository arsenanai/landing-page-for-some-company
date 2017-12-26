<div class="panel-group" @isset($id)id='{{$id}}'@endisset role="tablist" aria-multiselectable="true">
	{{$slot}}
</div>