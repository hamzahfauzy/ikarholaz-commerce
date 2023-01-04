@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ]
    ],
    'template_title' => __('Merchant')
])
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                    
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Advertisement') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('staff.merchants.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>  
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Kode Merchant</th>
										<th>Nama</th>
										<th>No. WA</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($model as $merchant)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $merchant->code }}</td>
											<td>{{ $merchant->name }}</td>
											<td>{{ $merchant->phone }}</td>

                                            <td>
                                                <form action="{{ route('staff.merchants.destroy',$merchant->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('staff.merchants.show',$merchant) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('staff.merchants.edit',$merchant) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{__('Delete')}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $model->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection
