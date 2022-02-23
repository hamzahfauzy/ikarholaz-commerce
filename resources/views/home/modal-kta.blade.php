<div id="modal-order-kta" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">{{__('Order KTA')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form-order" action="{{route('shop.checkout-kta')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="digit" value="">
                <input type="hidden" name="no_request" value="">
                <input type="hidden" name="product_id" value="">
                <input type="hidden" name="desain_id" value="">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">No. Kartu</label>
                            <input type="text" class="form-control" id="no_kartu_fix" name="no_kartu_fix" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Harga</label>
                            <input type="text" class="form-control" id="harga_fix" name="harga_fix" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="field-4" class="control-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" id="field-4" placeholder="Nama Lengkap">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="field-5" class="control-label">Nama tercetak di Kartu</label>
                            <input type="text" name="nama_tercetak_di_kartu" class="form-control" id="field-5" placeholder="Nama Tercetak di Kartu">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Kustom Desain</label>
                            <input type="file" name="kustom_desain" class="form-control">
                        </div>
                    </div>
                </div>
                @include('home.list-desain')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="document.getElementById('form-order').submit()">Check Out</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->