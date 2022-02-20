<div id="modal-nra-cantik" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="form-nra-cantik" onsubmit="return checkKartu()">
            <div class="modal-header">
                <h4 class="modal-title mt-0">{{__('Konversi ke NRA Cantik')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="btn waves-effect waves-light btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tahun Lulus</button>
                            <ul class="dropdown-menu select-tahun" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                @for($i=1974;$i<=2018;$i++)
                                <li><a href="javascript:void(0)" class="dropdown-item" onclick="no_seri.value=({{$i}}).toString().slice(-2)">{{$i}}</a></li>
                                @endfor
                            </ul>
                        </div>
                        <input type="text" id="no_seri" name="no_seri" class="form-control" placeholder="No. Seri" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" id="no_kartu" name="no_kartu" maxlength="8" class="form-control" required placeholder="Nomor Kartu. Ex : 01234567 (8 Digit)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn waves-effect waves-light btn-primary">Cek</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->