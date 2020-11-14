<div class="container-fluid">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block bg-light sidebar">
			<div class="sidebar-sticky">
				<ul class="nav flex-column">
				@foreach($menu as $menuItem)
					@if( $menuItem->parent_id == 0 )
						@if( $menuItem->url == '' )
							<li class="nav-item"> <a class="nav-link" href="#" data-toggle="collapse" data-target="#{{ $menuItem->title }}" > <i class="{{ $menuItem->icon }}"></i> {{ $menuItem->title }} </a></li>
						@else
							<li class="nav-item"> <a class="nav-link" href="{{ $menuItem->url }}"> <i class="{{ $menuItem->icon }}"></i> {{ $menuItem->title }}</a> </li>
						@endif
					@endif

					@if( ! $menuItem->children->isEmpty() )
						<ul class="collapse nav flex-column" id="{{ $menuItem->title }}">
							@foreach($menuItem->children as $subMenuItem)
								<li class="nav-item"><a class="nav-link" href="{{ $subMenuItem->url }}"> <i class="{{ $subMenuItem->icon }}"></i> {{ $subMenuItem->title }}</a></li>
							@endforeach
						</ul>
					@endif
				@endforeach
				</ul>
			</div>
		</nav>
	</div>
</div>