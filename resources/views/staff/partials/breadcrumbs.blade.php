<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{$template_title}}</h4>
            <ol class="breadcrumb p-0 m-0">
                @foreach($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item">
                    <a href="{{$breadcrumb['route']}}">{{$breadcrumb['label']}}</a>
                </li>
                @endforeach
                <li class="breadcrumb-item active">
                    {{$template_title}}
                </li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->