@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ]
    ],
    'template_title' => __('Category')
])

<div class="row">
    <div class="col-sm-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <span id="card_title">
                        {{ __('Category') }}
                    </span>

                        <div class="float-right">
                        <a href="{{ route('staff.categories.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                
                                <th>Name</th>
                                <th>Slug</th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>

                                    <td>
                                        <form action="{{ route('staff.categories.destroy',$category->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                            <!-- <a class="btn btn-sm btn-primary " href="{{ route('staff.categories.show',$category->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> -->
                                            <a class="btn btn-sm btn-success" href="{{ route('staff.categories.edit',$category->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
        {!! $categories->links('vendor.pagination.bootstrap-4') !!}
    </div>
</div>
@endsection
