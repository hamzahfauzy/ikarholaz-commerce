@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [
    [
    'label' => 'Dashboard',
    'route' => route('staff.index')
    ],
    [
    'label' => 'Alumni',
    'route' => route('staff.alumnis.index')
    ],
],
'template_title' => __('Show Alumni')
])
<section class="">
    <div class="row">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <span class="card-title">{{__('Show Alumni')}}</span>
                    </div>
                    <div class="float-right">
                        @if ($alumni->approval_status == '' && $alumni->NRA)
                        <form action="{{ route('staff.alumnis.approve',$alumni->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to approve this item ?')}}')){ return true }else{ return false }">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-check"></i> {{__('Approve')}}</button>
                            <a class="btn btn-primary" href="{{ route('staff.alumnis.index') }}"> Back</a>
                        </form>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('staff.alumnis.edit',$alumni->id) }}" class="btn btn-primary">Edit Profile</a>
                            <p></p>
                            <br>
                        </div>
                        <div class="col-12 col-md-3">
                            <img src="{{Storage::url($alumni->profile_pic)}}" alt="" width="100%">
                        </div>
                        <div class="col-12 col-md-9 m-auto">
                            <table class="table table-bordered">
                                <tr>
                                    <td>NRA</td>
                                    <td>:</td>
                                    <td>{{$alumni->NRA ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>{{ $alumni->name ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tahun Lulus</td>
                                    <td>:</td>
                                    <td>{{$alumni->graduation_year ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{$alumni->gender ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tempat / Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{($alumni->place_of_birth && $alumni->date_of_birth) ? $alumni->place_of_birth .' / '. $alumni->date_of_birth : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{$alumni->address . ', ' . $alumni->city . ', ' . $alumni->country}}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>{{ucwords($alumni->approval_status)}} Oleh {{$alumni->approval_by}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection