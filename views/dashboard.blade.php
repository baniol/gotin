@layout('gotin::layouts.main_layout')

@section('content')

{{$flash}}
	
	<div class="dashboard-content">
		@if(Auth::is('Admin'))
			{{HTML::link_to_action('gotin::users','Users',array(),array('class'=>'btn btn-large btn-block btn-info'))}}
			{{HTML::link_to_action('gotin::roles','Roles',array(),array('class'=>'btn btn-large btn-block btn-success'))}}
		@else
			{{HTML::link_to_action('gotin::dashboard','Some action...',array(),array('class'=>'btn btn-large btn-block btn-info'))}}
		@endif
	</div>

@endsection