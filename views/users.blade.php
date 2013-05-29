@layout('gotin::layouts.main_layout')

@section('content')

{{$flash}}
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>Email</th>
			<th>Created</th>
			<th class="actions">Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($users as $u)
	<tr>
		<td>{{ $u->id }}</td>
		<td>{{ $u->username }}</td>
		<td>{{ $u->email }}</td>
		<td>{{ $u->created_at }}</td>
		<td>
			{{GotinHelper::link_icon('gotin::users@edit','pencil','Edit',array($u->id),array('class'=>'btn'))}}
			@if($u->super != 1)
			{{GotinHelper::link_icon('gotin::users@delete','trash','Delete',array($u->id),array('class'=>'delete_toggler btn','onclick'=>'return confirm(\'Are you sure?\')'));}}
			@endif
		</td>
	</tr>
	@endforeach
	</tbody>
</table>

{{HTML::link_to_action('gotin::users@new','Add User',array(),array('class'=>'btn btn-primary right'))}}

@endsection
