@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Custom Field')
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
                                {{ __('Custom Field') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('custom-fields.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Field Key</th>
										<th>Field Type</th>
										<th>Class Target</th>
										<th>Query Condition</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customFields as $customField)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $customField->field_key }}</td>
											<td>{{ $customField->field_type }}</td>
											<td>{{ $customField->class_target }}</td>
											<td>{{ $customField->query_condition }}</td>

                                            <td>
                                                <form action="{{ route('custom-fields.destroy',$customField->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('custom-fields.show',$customField->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('custom-fields.edit',$customField->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $customFields->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection
