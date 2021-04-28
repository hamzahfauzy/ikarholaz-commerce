@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Custom Field Value')
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
                                {{ __('Custom Field Value') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('custom-field-values.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Custom Field Id</th>
										<th>Pk Id</th>
										<th>Field Value</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customFieldValues as $customFieldValue)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $customFieldValue->custom_field_id }}</td>
											<td>{{ $customFieldValue->pk_id }}</td>
											<td>{{ $customFieldValue->field_value }}</td>

                                            <td>
                                                <form action="{{ route('custom-field-values.destroy',$customFieldValue->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('custom-field-values.show',$customFieldValue->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('custom-field-values.edit',$customFieldValue->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $customFieldValues->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection
