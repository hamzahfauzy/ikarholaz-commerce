@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ]
    ],
    'template_title' => __('Kegiatan')
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
                                {{ __('Kegiatan') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('staff.events.create') }}" class="btn btn-primary btn-sm mr-3"  data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                                <button class="btn btn-success btn-sm float-right btn-export">
                                  {{ __('Export') }}
                                </button>
                            </div>  
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover tbl-events">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nama</th>
										<th>Kategori</th>
										<th>Waktu Mulai</th>
										<th>Waktu Selesai</th>
										<th>Tempat</th>

                                        <th class="noExl"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $event->name }}</td>
											<td>{{ $event->category }}</td>
											<td>{{ $event->start_time }}</td>
											<td>{{ $event->end_time }}</td>
											<td>{{ $event->place }}</td>

                                            <td class="noExl">
                                                <form action="{{ route('staff.events.destroy',$event->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('staff.events.show',$event) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('staff.events.edit',$event) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $events->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="{{asset('js/jquery.table2excel.js')}}"></script>
<script>
$(".btn-export").click(function(){
  $(".tbl-events").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Laporan Kegiatan",
    filename: "LaporanKegiatan", //do not include extension
    fileext: ".xls" // file extension
  }); 
});
</script>
@endsection