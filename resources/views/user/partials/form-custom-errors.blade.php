@if (Session::has('errors'))
<div class="alert alert-danger validation">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	<ul class="text-left">
	@foreach(Session::get('errors') as $error)
		<li>{{$error}}</li>
	@endforeach
	</ul>
</div>
@endif
