@layout('gotin::layouts.main_layout')

@section('content')

{{$flash}}
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Description</th>
			<th class="actions">Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($roles as $r)
	<tr>
		<td>{{ $r->id }}</td>
		<td>{{ $r->name }}</td>
		<td>{{ $r->description }}</td>
		<td>
			{{GotinHelper::link_icon('gotin::roles@edit','pencil','Edit',array($r->id),array('class'=>'btn'))}}
			{{GotinHelper::link_icon('gotin::roles@delete','trash','Delete',array($r->id),array('class'=>'delete_toggler btn','onclick'=>'return confirm(\'Are you sure?\')'))}}
		</td>
	</tr>
	@endforeach
	</tbody>
</table>

{{HTML::link_to_action('gotin::roles@new','Add Role',array(),array('class'=>'btn btn-primary right'))}}

@endsection