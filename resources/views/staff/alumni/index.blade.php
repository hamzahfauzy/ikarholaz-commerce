@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [
[
'label' => 'Dashboard',
'route' => route('staff.index')
]
],
'template_title' => __('Alumni')
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
                            {{ __('Alumni') }}
                        </span>

                        <div class="float-right">
                            <a href="{{ route('staff.alumnis.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                {{ __('Create New') }}
                            </a>
                            <a href="{{ route('staff.alumnis.import') }}" class="btn btn-primary btn-sm float-right mr-2" data-placement="left">
                                {{ __('Import') }}
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
                                    <th>Name</th>
                                    <th>NRA</th>
                                    <th>Phone</th>
                                    <th>Graduation Year</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date of birth</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alumnis as $alumni)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $alumni->name ?? '-' }}</td>
                                    <td>{!! $alumni->NRA ?? '<a href="'.route('staff.alumnis.update-nra',$alumni->id).'">Update NRA</a>' !!}</td>
                                    <td>{{ $alumni->user && $alumni->user->email ? $alumni->user->email : '-' }}</td>
                                    <td>{{ $alumni->graduation_year ?? '-' }}</td>
                                    <td>{{ $alumni->email ?? '-' }}</td>
                                    <td>{{ $alumni->approval_status ?? '-' }}</td>
                                    <td>{{ $alumni->date_of_birth ?? '-' }}</td>

                                    <td>
                                        @if ($alumni->approval_status == '' && $alumni->NRA)
                                        <form action="{{ route('staff.alumnis.approve',$alumni->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to approve this item ?')}}')){ return true }else{ return false }">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-fw fa-check"></i> {{__('Approve')}}</button>
                                        </form>
                                        @endif
                                        <form action="{{ route('staff.alumnis.destroy',$alumni->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                            <a class="btn btn-sm btn-primary " href="{{ route('staff.alumnis.show',$alumni->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
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
            {!! $alumnis->links('vendor.pagination.bootstrap-4') !!}
        </div>
    </div>
</div>
@endsection