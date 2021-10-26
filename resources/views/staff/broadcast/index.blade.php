@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
    ],
    'template_title' => __('Broadcast')
])
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Broadcast') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('staff.broadcasts.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Title</th>
										<th>Message</th>
										<th>Url</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($broadcasts as $broadcast)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $broadcast->title }}</td>
											<td>{{ $broadcast->message }}</td>
											<td>{{ $broadcast->url }}</td>

                                            <td>
                                                <form action="{{ route('staff.broadcasts.destroy',$broadcast->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('staff.broadcasts.show',$broadcast->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('staff.broadcasts.edit',$broadcast->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                       <td colspan="5">{{__('Empty data')}}</td> 
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $broadcasts->links() !!}
            </div>
        </div>
    </div>
@endsection
