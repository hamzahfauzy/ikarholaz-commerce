<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $product->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        @if(!$product->id || !$product->parent)
        <div class="form-group">
            {{ Form::label('slug') }}
            {{ Form::text('slug', $product->slug, ['class' => 'form-control' . ($errors->has('slug') ? ' is-invalid' : ''), 'placeholder' => 'Slug']) }}
            {!! $errors->first('slug', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::textArea('description', $product->description, ['class' => 'form-control summernote' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        @endif
        <div class="form-group">
            {{ Form::label('base_price') }}
            {{ Form::text('base_price', $product->base_price??0, ['class' => 'form-control' . ($errors->has('base_price') ? ' is-invalid' : ''), 'placeholder' => 'Base Price']) }}
            {!! $errors->first('base_price', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        @if(!$product->id || !$product->parent)
        <div class="form-group">
            {{ Form::label('discount_price') }}
            {{ Form::text('discount_price', $product->discount_price??0, ['class' => 'form-control' . ($errors->has('discount_price') ? ' is-invalid' : ''), 'placeholder' => 'Discount Price']) }}
            {!! $errors->first('discount_price', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('stock_status') }}
            {{ Form::select('stock_status', ['0'=>'Sesuai Stok','Tersedia'=>'Tersedia','Tidak Tersedia'=>'Tidak Tersedia'], $product->stock_status, ['class' => 'form-control' . ($errors->has('stock_status') ? ' is-invalid' : ''), 'placeholder' => '- Stock Status -']) }}
            {!! $errors->first('stock_status', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        @endif
        <div class="form-group">
            {{ Form::label('stock') }}
            {{ Form::number('stock', $product->stock??0, ['class' => 'form-control' . ($errors->has('stock') ? ' is-invalid' : ''), 'placeholder' => 'Stock']) }}
            {!! $errors->first('stock', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('weight') }}
            {{ Form::number('product_weight', $product->product_weight??1, ['step'=>1,'class' => 'form-control' . ($errors->has('product_weight') ? ' is-invalid' : ''), 'placeholder' => 'product_weight']) }}
            {!! $errors->first('product_weight', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        
    </div>
</div>

@section('css')
<!-- Summernote css -->
<link href="{{asset('plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
.group-span-filestyle {
    width:100%;
}
.group-span-filestyle > label {
    display:block;
}
</style>
@endsection

@section('script')
<!--Summernote js-->
<script src="{{asset('plugins/summernote/summernote-bs4.js')}}"></script>
<script>
$('.summernote').summernote({
    height: 350,                 // set editor height
    minHeight: null,             // set minimum height of editor
    maxHeight: null,             // set maximum height of editor
    focus: false                 // set focus to editable area after initializing summernote
});
</script>
<script src="{{asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
<script>
$('.select2').select2()
function readURL(input,el) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $(el).attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
    $('.btn-delete').removeClass('d-none')
    $('[name=hidden_image]').val("");
    $('#myModal').modal('hide')
  }
}

$(".image").change(function() {
  readURL(this,'#preview');
  $(".btn-delete-existing").addClass('d-none')
});

$(".image-variant").change(function() {
  readURL(this,'#preview-variant');
});

function deleteThumbnail() {
    $(".image").val(null)
    $('#preview').attr('src', null);
    $('.group-span-filestyle span.badge').html('')
    $(".btn-delete").addClass('d-none')
}

function deleteExistingThumbnail() {
    $(".image").val(null)
    $('#preview').attr('src', null);
    $('.group-span-filestyle span.badge').html('')
    $(".btn-delete-existing").addClass('d-none')

    fetch("{{route('staff.product-images.delete',$product->thumb?$product->thumb->id:0)}}")
}

function loadAllProductImages()
{
    fetch("{{route('product-images')}}")
    .then(res => res.json())
    .then(res => {
        var html = "<div class='row'>"
        res.forEach(image => {
            html += `<div class="col-12 col-sm-3"><img src="${image.full_image_url}" onclick='selectImage(${JSON.stringify(image)})' style="cursor:pointer"></div>`
        })

        html += "</div>"

        $("#profile").html(html)
    })
}

function selectImage(image)
{
    $('[name=hidden_image]').val(image.file_url);
    $('#preview').attr('src',image.full_image_url)
    $('.btn-delete').removeClass('d-none')
    $('#myModal').modal('hide')
}
</script>
@endsection