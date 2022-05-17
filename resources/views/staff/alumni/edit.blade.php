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
'template_title' => __('Update Alumni')
])
<section class="">
    <div class="">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">{{__('Update Alumni')}}</span>
                </div>
                <div class="card-body">

                    
                    <form method="POST" action="{{ route('staff.alumnis.update', $alumni->id) }}" role="form" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" {{$alumni->private_email ? 'checked' : ''}} name="private_email" id="private_email">
                            <label class="form-check-label" for="private_email">Private Email</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" {{$alumni->private_phone ? 'checked' : ''}} name="private_phone" id="private_phone">
                            <label class="form-check-label" for="private_phone">Private Phone</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" {{$alumni->private_domisili ? 'checked' : ''}} name="private_domisili" id="private_domisili">
                            <label class="form-check-label" for="private_domisili">Private Domisili</label>
                        </div>
                        <img src="{{Storage::url('public/'.$alumni->profile_pic)}}" width="200" class="my-2">
                        <div class="form-group">
                            <label for="">Photo Profile</label>
                            <input type="file" name="profile" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">NRA</label>
                            <input type="text" name="NRA" value="{{old('NRA') ?? $alumni->NRA}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="name" value="{{old('name') ?? $alumni->user->name}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Kelas</label>
                            <input type="text" name="class_name" value="{{old('class_name') ?? $alumni->class_name}}" class="form-control" data-role="tagsinput">
                        </div>
                       <div class="form-group">
                            <label for="">Tahun Masuk</label>
                            <select name="year_in" class="form-control @error('year_in') is-invalid @enderror" id="">
                                <option value="">- Pilih Tahun -</option>
                                @for($y=date('Y')-2;$y>=1971;$y--)
                                <option {{old('year_in') == $y || $alumni->year_in == $y ? 'selected' : ''}}>{{$y}}</option>
                                @endfor
                            </select>
                            @error('year_in')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Tahun Lulus</label>
                            <select name="graduation_year" class="form-control @error('graduation_year') is-invalid @enderror" id="">
                                <option value="">- Pilih Tahun -</option>
                                @for($y=date('Y');$y>=1974;$y--)
                                @if($y==1978)
                                @continue
                                @endif
                                <option {{old('graduation_year') == $y || $alumni->graduation_year == $y ? 'selected' : ''}}>{{$y}}</option>
                                @endfor
                            </select>
                            
                            @error('graduation_year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">No HP</label>
                            <input type="text" name="phone" value="{{old('phone') ?? $alumni->user->email}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" value="{{old('email') ?? $alumni->email}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Kelamin</label>
                            <select name="gender" id="" class="form-control">
                                <option value="" readonly selected>- Pilih Jenis Kelamin -</option>
                                <option value="Laki-Laki" {{$alumni->gender == 'Laki-Laki' ? 'selected=""' : ''}}>Laki-Laki</option>
                                <option value="Perempuan" {{$alumni->gender == 'Perempuan' ? 'selected=""' : ''}}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tempat Lahir</label>
                            <input type="text" name="place_of_birth" value="{{old('place_of_birth') ?? $alumni->place_of_birth}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" value="{{old('date_of_birth') ?? $alumni->date_of_birth}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Negara</label>
                            <input type="text" name="country" value="{{old('country') ?? $alumni->country}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Provinsi</label>
                            <select name="province" id="" class="form-control" onchange="getDistrict(this.value,'#city_id')">
                                <option value="" readonly selected>- Pilih Provinsi -</option>
                                @foreach($provincies as $province)
                                    <option {{ $alumni->province == $province->province_id ? 'selected' : '' }} value="{{$province->province_id}}">{{$province->province}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Kabupaten / Kota</label>
                            <select name="city" id="city_id" class="form-control">
                                @if($alumni->city)
                                <option value="{{$alumni->city}}">{{$alumni->city}}</option>
                                @else
                                <option value="">- Pilih Provinsi terlebih dahulu -</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="address" id="" cols="30" rows="10" class="form-control">{{old('address') ?? $alumni->address}}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Keahlian</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addSkill()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="skills">
                                @foreach($alumni->skills as $i => $skill)
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="skills[{{$i}}][id]" value="{{$skill->id}}">
                                        <input class="form-control" type="text" placeholder="Keahlian ke {{$i+1}}" name="skills[{{$i}}][name]" value="{{$skill->name}}" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSkill({{$i}},{{$skill->id}})"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Usaha / Bisnis</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addBusiness()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="businesses">
                                @foreach($alumni->businesses as $i => $business)
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="businesses[{{$i}}][id]" value="{{$business->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Usaha / Bisnis ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#business-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeBusiness({{$i}},{{$business->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="business-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input class="form-control" type="text" placeholder="Nama" name="businesses[{{$i}}][name]" value="{{$business->name}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sektor</label>
                                                <select name="businesses[{{$i}}][sektor]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Sektor -</option>
                                                    @foreach($sektors as $sektor)
                                                        <option {{$sektor == $business->sektor ? "selected" : ""}} value="{{$sektor}}">{{$sektor}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Badan Hukum</label>
                                                <select name="businesses[{{$i}}][badan_hukum]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Badan Hukum -</option>
                                                    @foreach($badan_hukums as $badan_hukum)
                                                        <option {{$badan_hukum == $business->badan_hukum ? "selected" : ""}} value="{{$badan_hukum}}">{{$badan_hukum}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Kepemilikan</label>
                                                <select name="businesses[{{$i}}][kepemilikan]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Kepemilikan -</option>
                                                    <option {{"Keluarga" == $business->kepemilikan ? "selected" : ""}} value="Keluarga">Keluarga</option>
                                                    <option {{"Pribadi" == $business->kepemilikan ? "selected" : ""}} value="Pribadi">Pribadi</option>
                                                    <option {{"Kongsi" == $business->kepemilikan ? "selected" : ""}} value="Kongsi">Kongsi</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Status kepemilikan</label>
                                                <select name="businesses[{{$i}}][status_kepemilikan]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Status Kepemilikan -</option>
                                                    <option {{"Milik" == $business->status_kepemilikan ? "selected" : ""}} value="Milik">Milik</option>
                                                    <option {{"Sewa" == $business->status_kepemilikan ? "selected" : ""}} value="Sewa">Sewa</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Skala</label>
                                                <select name="businesses[{{$i}}][skala]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Skala -</option>
                                                    <option {{"Mikro" == $business->skala ? "selected" : ""}} value="Mikro">Mikro</option>
                                                    <option {{"Kecil" == $business->skala ? "selected" : ""}} value="Kecil">Kecil</option>
                                                    <option {{"Menengah" == $business->skala ? "selected" : ""}} value="Menengah">Menengah</option>
                                                    <option {{"Besar" == $business->skala ? "selected" : ""}} value="Besar">Besar</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Berdiri Sejak</label>
                                                <input class="form-control" type="text" placeholder="Berdiri Sejak" name="businesses[{{$i}}][berdiri_sejak]" value="{{$business->berdiri_sejak}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Pencapaian</label>
                                                <input class="form-control" type="text" placeholder="Pencapaian" name="businesses[{{$i}}][pencapaian]" value="{{$business->pencapaian}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Alamat</label>
                                                <input class="form-control" type="text" placeholder="Alamat" name="businesses[{{$i}}][alamat]" value="{{$business->alamat}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">No Telepon</label>
                                                <input class="form-control" type="text" placeholder="No Telepon" name="businesses[{{$i}}][no_telepon]" value="{{$business->no_telepon}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input class="form-control" type="text" placeholder="Email" name="businesses[{{$i}}][email]" value="{{$business->email}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Website</label>
                                                <input class="form-control" type="text" placeholder="Website" name="businesses[{{$i}}][website]" value="{{$business->website}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Jumlah SDM / Karyawan</label>
                                                <input class="form-control" type="text" placeholder="Jumlah SDM / Karyawan" name="businesses[{{$i}}][jumlah_sdm]" value="{{$business->jumlah_sdm}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Ijin Usaha</label>
                                                <select name="businesses[{{$i}}][ijin_usaha]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Ijin Usaha -</option>
                                                    @foreach($ijin_usahas as $ijin_usaha)
                                                        <option {{$ijin_usaha == $business->ijin_usaha ? "selected" : ""}} value="{{$ijin_usaha}}">{{$ijin_usaha}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Komunitas</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addCommunity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="communities">
                                @foreach($alumni->communities as $i => $community) 
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="communities[{{$i}}][id]" value="{{$community->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Komunitas ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#community-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeCommunity({{$i}},{{$community->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="community-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input class="form-control" type="text" placeholder="Nama" name="communities[{{$i}}][name]" value="{{$community->name}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Bidang</label>
                                                <select name="communities[{{$i}}][bidang]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Bidang -</option>
                                                    @foreach($communities as $bidang)
                                                        <option {{$bidang == $community->bidang ? "selected" : ""}} value="{{$bidang}}">{{$bidang}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Cakupan</label>
                                                <select name="communities[{{$i}}][cakupan]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Cakupan -</option>
                                                    <option {{"Lokal" == $community->cakupan ? "selected" : ""}} value="Lokal">Lokal</option>
                                                    <option {{"Nasional" == $community->cakupan ? "selected" : ""}} value="Nasional">Nasional</option>
                                                    <option {{"Internasional" == $community->cakupan ? "selected" : ""}} value="Internasional">Internasional</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Kantor</label>
                                                <input class="form-control" type="text" placeholder="Kantor" name="communities[{{$i}}][kantor]" value="{{$community->kantor}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Berdiri sejak</label>
                                                <input class="form-control" type="text" placeholder="Berdiri sejak" name="communities[{{$i}}][berdiri_sejak]" value="{{$community->berdiri_sejak}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Pencapaian</label>
                                                <input class="form-control" type="text" placeholder="Pencapaian" name="communities[{{$i}}][pencapaian]" value="{{$community->pencapaian}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Keaktifan</label>
                                                <select name="communities[{{$i}}][keaktifan]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Keaktifan -</option>
                                                    <option {{"Founder" == $community->keaktifan ? "selected" : ""}} value="Founder">Founder</option>
                                                    <option {{"Pembina" == $community->keaktifan ? "selected" : ""}} value="Pembina">Pembina</option>
                                                    <option {{"Pengurus" == $community->keaktifan ? "selected" : ""}} value="Pengurus">Pengurus</option>
                                                    <option {{"Anggota" == $community->keaktifan ? "selected" : ""}} value="Anggota">Anggota</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">No Telepon</label>
                                                <input class="form-control" type="text" placeholder="No Telepon" name="communities[{{$i}}][no_telepon]" value="{{$community->no_telepon}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input class="form-control" type="text" placeholder="Email" name="communities[{{$i}}][email]" value="{{$community->email}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Website</label>
                                                <input class="form-control" type="text" placeholder="Website" name="communities[{{$i}}][website]" value="{{$community->website}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Jumlah anggota</label>
                                                <input class="form-control" type="text" placeholder="Jumlah anggota" name="communities[{{$i}}][jumlah_anggota]" value="{{$community->jumlah_anggota}}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Pekerjaan / Profesi</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addProfession()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="professions">
                                @foreach($alumni->professions as $i => $profession) 
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="professions[{{$i}}][id]" value="{{$profession->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Pekerjaan / Profesi ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#profession-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeProfession({{$i}},{{$profession->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="profession-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Nama Perusahaan</label>
                                                <input class="form-control" type="text" placeholder="Nama Perusahaan" name="professions[{{$i}}][company_name]" value="{{$profession->company_name}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Jabatan</label>
                                                <input class="form-control" type="text" placeholder="Jabatan" name="professions[{{$i}}][jabatan]" value="{{$profession->jabatan}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Bidang</label>
                                                <select name="professions[{{$i}}][bidang]" class="form-control">
                                                    <option value="-" selected readonly>- Pilih Bidang -</option>
                                                    @foreach($professions as $bidang)
                                                        <option {{$bidang == $profession->bidang ? "selected" : ""}} value="{{$bidang}}">{{$bidang}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Mulai dari</label>
                                                <input class="form-control" type="text" placeholder="Mulai dari" name="professions[{{$i}}][mulai_dari]" value="{{$profession->mulai_dari}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sampai</label>
                                                <input class="form-control" type="text" placeholder="Sampai" name="professions[{{$i}}][sampai]" value="{{$profession->sampai}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Alamat</label>
                                                <input class="form-control" type="text" placeholder="Alamat" name="professions[{{$i}}][alamat]" value="{{$profession->alamat}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <input class="form-control" type="text" placeholder="Keterangan" name="professions[{{$i}}][keterangan]" value="{{$profession->keterangan}}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Pelatihan</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addTraining()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="trainings">
                                @foreach($alumni->trainings as $i => $training) 
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="trainings[{{$i}}][id]" value="{{$training->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Pelatihan ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#training-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeTraining({{$i}},{{$training->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="training-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input class="form-control" type="text" placeholder="Nama" name="trainings[{{$i}}][name]" value="{{$training->name}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Pemberi pelatihan</label>
                                                <input class="form-control" type="text" placeholder="Pemberi pelatihan" name="trainings[{{$i}}][pemberi_pelatihan]" value="{{$training->pemberi_pelatihan}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tahun</label>
                                                <input class="form-control" type="text" placeholder="Tahun" name="trainings[{{$i}}][tahun]" value="{{$training->tahun}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <input class="form-control" type="text" placeholder="Keterangan" name="trainings[{{$i}}][keterangan]" value="{{$training->keterangan}}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Penghargaan</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addAppreciation()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="appreciations">
                                @foreach($alumni->appreciations as $i => $appreciation) 
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="appreciations[{{$i}}][id]" value="{{$appreciation->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Penghargaan ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#appreciation-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeAppreciation({{$i}},{{$appreciation->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="appreciation-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input class="form-control" type="text" placeholder="Nama" name="appreciations[{{$i}}][name]" value="{{$appreciation->name}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Pemberi penghargaan</label>
                                                <input class="form-control" type="text" placeholder="Pemberi penghargaan" name="appreciations[{{$i}}][pemberi_penghargaan]" value="{{$appreciation->pemberi_penghargaan}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tahun</label>
                                                <input class="form-control" type="text" placeholder="Tahun" name="appreciations[{{$i}}][tahun]" value="{{$appreciation->tahun}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <input class="form-control" type="text" placeholder="Keterangan" name="appreciations[{{$i}}][keterangan]" value="{{$appreciation->keterangan}}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label>Minat</label>
                                <button class="btn btn-primary btn-sm" type="button" onclick="addInterest()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="interests">
                                @foreach($alumni->interests as $i => $interest) 
                                    <div class="card card-body mb-3">
                                        <input type="hidden" name="interests[{{$i}}][id]" value="{{$interest->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="title">Minat ke {{$i+1}}</p>
                                            <div>
                                                <button class="btn btn-success" type="button" data-target="#interest-{{$i}}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn btn-outline-danger" type="button" onclick="removeInterest({{$i}},{{$interest->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="interest-{{$i}}">
                                            <div class="form-group">
                                                <label for="">Bidang</label>
                                                <input class="form-control" type="text" placeholder="Bidang" name="interests[{{$i}}][bidang]" value="{{$interest->bidang}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Gaji</label>
                                                <input class="form-control" type="text" placeholder="Gaji" name="interests[{{$i}}][gaji]" value="{{$interest->gaji}}" required>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button class="btn btn-primary">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
<link rel="stylesheet" href="{{asset('plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}">
<script src="{{asset('plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js')}}"></script>
<script>

    async function getDistrict(province_id, target_element)
    {
        document.querySelector(target_element).innerHTML = "<option value=''>Loading...</option>"
        var request = await fetch('/api/get-district/'+province_id)
        var response = await request.json()
        all_district = response
        document.querySelector(target_element).innerHTML = "<option value=''>- Pilih Kabupaten / Kota -</option>"
        response.forEach(val => {
            var option = document.createElement("option");
            option.text = val.city_name;
            option.value = val.city_name;
            document.querySelector(target_element).appendChild(option);
        })
    }

    function updateSkillsEl (){
        let elSkills = document.querySelector("#skills")

        for (let i = 0; i < elSkills.children.length; i++) {
            const element = elSkills.children[i];
            
            element.querySelector("input").placeholder = `Keahlian ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeSkill(${i+1})`)
        }
    }

    function addSkill(){
        let elSkills = document.querySelector("#skills")
        elSkills.innerHTML += `<div class="input-group mb-3">
                                        <input class="form-control" type="text" placeholder="Keahlian ke ${elSkills.childElementCount+1}" name="skills[${elSkills.childElementCount}][name]" value="" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSkill(${elSkills.childElementCount})"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>`

    }

    async function removeSkill(i,id = false){

        let elSkills = document.querySelector("#skills")

        elSkills.removeChild(elSkills.childNodes[i])

        updateSkillsEl()

      if(id){
        await fetch("/api/mobile/alumni/delete-skill/"+id)
      }

    }

    function updateBusinessesEl (){
        let els = document.querySelector("#businesses")

        for (let i = 0; i < els.childElementCount; i++) {
            const element = els.children[i];

            element.querySelector(".title").innerHTML = `Usaha / Bisnis ke ${i+1}`
            element.querySelector("input").placeholder = `Usaha / Bisnis ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeBusiness(${i})`)
        }
    }

    function addBusiness(){
        let els = document.querySelector("#businesses")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Usaha / Bisnis ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#business-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeBusiness(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="business-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input class="form-control" type="text" placeholder="Nama" name="businesses[${els.childElementCount}][name]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Sektor</label>
                        <select name="businesses[${els.childElementCount}][sektor]" class="form-control">
                            <option value="-" selected readonly>- Pilih Sektor -</option>
                            @foreach($sektors as $sektor)
                                <option value="{{$sektor}}">{{$sektor}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Badan Hukum</label>
                        <select name="businesses[${els.childElementCount}][badan_hukum]" class="form-control">
                            <option value="-" selected readonly>- Pilih Badan Hukum -</option>
                            @foreach($badan_hukums as $badan_hukum)
                                <option value="{{$badan_hukum}}">{{$badan_hukum}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kepemilikan</label>
                        <select name="businesses[${els.childElementCount}][kepemilikan]" class="form-control">
                            <option value="-" selected readonly>- Pilih Kepemilikan -</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Pribadi">Pribadi</option>
                            <option value="Kongsi">Kongsi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Status kepemilikan</label>
                        <select name="businesses[${els.childElementCount}][status_kepemilikan]" class="form-control">
                            <option value="-" selected readonly>- Pilih Status Kepemilikan -</option>
                            <option value="Milik">Milik</option>
                            <option value="Sewa">Sewa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Skala</label>
                        <select name="businesses[${els.childElementCount}][skala]" class="form-control">
                            <option value="-" selected readonly>- Pilih Skala -</option>
                            <option value="Mikro">Mikro</option>
                            <option value="Kecil">Kecil</option>
                            <option value="Menengah">Menengah</option>
                            <option value="Besar">Besar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Berdiri Sejak</label>
                        <input class="form-control" type="text" placeholder="Berdiri Sejak" name="businesses[${els.childElementCount}][berdiri_sejak]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Pencapaian</label>
                        <input class="form-control" type="text" placeholder="Pencapaian" name="businesses[${els.childElementCount}][pencapaian]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input class="form-control" type="text" placeholder="Alamat" name="businesses[${els.childElementCount}][alamat]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">No Telepon</label>
                        <input class="form-control" type="text" placeholder="No Telepon" name="businesses[${els.childElementCount}][no_telepon]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input class="form-control" type="text" placeholder="Email" name="businesses[${els.childElementCount}][email]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Website</label>
                        <input class="form-control" type="text" placeholder="Website" name="businesses[${els.childElementCount}][website]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah SDM / Karyawan</label>
                        <input class="form-control" type="text" placeholder="Jumlah SDM / Karyawan" name="businesses[${els.childElementCount}][jumlah_sdm]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Ijin Usaha</label>
                        <select name="businesses[${els.childElementCount}][ijin_usaha]" class="form-control">
                            <option value="-" selected readonly>- Pilih Ijin Usaha -</option>
                            @foreach($ijin_usahas as $ijin_usaha)
                                <option value="{{$ijin_usaha}}">{{$ijin_usaha}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        `

    }

    async function removeBusiness(i,id = false){

        let els = document.querySelector("#businesses")

        els.removeChild(els.childNodes[i])

        updateBusinessesEl()

        if(id){
            await fetch("/api/mobile/alumni/delete-business/"+id)
        }

    }

    function updateCommunitiesEl (){
        let els = document.querySelector("#communities")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector(".title").innerHTML = `Komunitas ke ${i+1}`
            element.querySelector("input").placeholder = `Komunitas ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeCommunity(${i})`)
        }
    }

    function addCommunity(){
        let els = document.querySelector("#communities")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Komunitas ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#community-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeCommunity(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="community-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input class="form-control" type="text" placeholder="Nama" name="communities[${els.childElementCount}][name]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Bidang</label>
                        <select name="communities[${els.childElementCount}][bidang]" class="form-control">
                            <option value="-" selected readonly>- Pilih Bidang -</option>
                            @foreach($communities as $bidang)
                                <option value="{{$bidang}}">{{$bidang}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Cakupan</label>
                        <select name="communities[${els.childElementCount}][cakupan]" class="form-control">
                            <option value="-" selected readonly>- Pilih Cakupan -</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kantor</label>
                        <input class="form-control" type="text" placeholder="Kantor" name="communities[${els.childElementCount}][kantor]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Berdiri sejak</label>
                        <input class="form-control" type="text" placeholder="Berdiri sejak" name="communities[${els.childElementCount}][berdiri_sejak]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Pencapaian</label>
                        <input class="form-control" type="text" placeholder="Pencapaian" name="communities[${els.childElementCount}][pencapaian]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Keaktifan</label>
                        <select name="communities[${els.childElementCount}][keaktifan]" class="form-control">
                            <option value="-" selected readonly>- Pilih Keaktifan -</option>
                            <option value="Founder">Founder</option>
                            <option value="Pembina">Pembina</option>
                            <option value="Pengurus">Pengurus</option>
                            <option value="Anggota">Anggota</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Keaktifan</label>
                        <input class="form-control" type="text" placeholder="Keaktifan" name="communities[${els.childElementCount}][keaktifan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">No Telepon</label>
                        <input class="form-control" type="text" placeholder="No Telepon" name="communities[${els.childElementCount}][no_telepon]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input class="form-control" type="text" placeholder="Email" name="communities[${els.childElementCount}][email]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Website</label>
                        <input class="form-control" type="text" placeholder="Website" name="communities[${els.childElementCount}][website]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah anggota</label>
                        <input class="form-control" type="text" placeholder="Jumlah anggota" name="communities[${els.childElementCount}][jumlah_anggota]" value="">
                    </div>
                </div>
            </div>
                                    
                                    
        `

    }

    async function removeCommunity(i,id = false){

        let els = document.querySelector("#communities")

        els.removeChild(els.childNodes[i])

        updateCommunitiesEl()

        if(id){
            await fetch("/api/mobile/alumni/delete-community/"+id)
        }

    }

    
    function updateProfessionsEl (){
        let els = document.querySelector("#professions")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector(".title").innerHTML = `Pekerjaan / Profesi ke ${i+1}`
            element.querySelector("input").placeholder = `Pekerjaan / Profesi ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeProfession(${i})`)
        }
    }

    function addProfession(){
        let els = document.querySelector("#professions")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Pekerjaan / Profesi ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#profession-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeProfession(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="profession-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Nama Perusahaan</label>
                        <input class="form-control" type="text" placeholder="Nama Perusahaan" name="professions[${els.childElementCount}][company_name]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jabatan</label>
                        <input class="form-control" type="text" placeholder="Jabatan" name="professions[${els.childElementCount}][jabatan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Bidang</label>
                        <select name="professions[${els.childElementCount}][bidang]" class="form-control">
                            <option value="-" selected readonly>- Pilih Bidang -</option>
                            @foreach($professions as $bidang)
                                <option value="{{$bidang}}">{{$bidang}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Mulai dari</label>
                        <input class="form-control" type="text" placeholder="Mulai dari" name="professions[${els.childElementCount}][mulai_dari]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input class="form-control" type="text" placeholder="Sampai" name="professions[${els.childElementCount}][sampai]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input class="form-control" type="text" placeholder="Alamat" name="professions[${els.childElementCount}][alamat]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input class="form-control" type="text" placeholder="Keterangan" name="professions[${els.childElementCount}][keterangan]" value="">
                    </div>
                </div>
            </div>
                                    
                                    
        `
    }

    async function removeProfession(i,id = false){

        let els = document.querySelector("#professions")

        els.removeChild(els.childNodes[i])

        updateProfessionsEl()

        if(id){
            await fetch("/api/mobile/alumni/delete-profession/"+id)
        }

    }

    
    function updateTrainingsEl (){
        let els = document.querySelector("#trainings")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector(".title").innerHTML = `Pelatihan ke ${i+1}`
            element.querySelector("input").placeholder = `Pelatihan ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeTraining(${i})`)
        }
    }

    function addTraining(){
        let els = document.querySelector("#trainings")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Pelatihan ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#training-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeTraining(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="training-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input class="form-control" type="text" placeholder="Nama" name="trainings[${els.childElementCount}][name]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Pemberi pelatihan</label>
                        <input class="form-control" type="text" placeholder="Pemberi pelatihan" name="trainings[${els.childElementCount}][pemberi_pelatihan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <input class="form-control" type="text" placeholder="Tahun" name="trainings[${els.childElementCount}][tahun]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input class="form-control" type="text" placeholder="Keterangan" name="trainings[${els.childElementCount}][keterangan]" value="">
                    </div>
                </div>
            </div>
                                    
                                    
        `
    }

    async function removeTraining(i,id = false){

        let els = document.querySelector("#trainings")

        els.removeChild(els.childNodes[i])

        updateTrainingsEl()

        if(id){
            await fetch("/api/mobile/alumni/delete-training/"+id)
        }

    }


    function updateAppreciationsEl (){
        let els = document.querySelector("#appreciations")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector(".title").innerHTML = `Penghargaan ke ${i+1}`
            element.querySelector("input").placeholder = `Penghargaan ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeAppreciacion(${i})`)
        }
    }

    function addAppreciation(){
        let els = document.querySelector("#appreciations")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Penghargaan ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#appreciation-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeAppreciation(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="appreciation-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input class="form-control" type="text" placeholder="Nama" name="appreciations[${els.childElementCount}][name]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Pemberi penghargaan</label>
                        <input class="form-control" type="text" placeholder="Pemberi penghargaan" name="appreciations[${els.childElementCount}][pemberi_penghargaan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <input class="form-control" type="text" placeholder="Tahun" name="appreciations[${els.childElementCount}][tahun]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input class="form-control" type="text" placeholder="Keterangan" name="appreciations[${els.childElementCount}][keterangan]" value="">
                    </div>
                </div>
            </div>
                                    
                                    
        `
    }

    async function removeAppreciation(i,id = false){

        let els = document.querySelector("#appreciations")

        els.removeChild(els.childNodes[i])

        updateAppreciationsEl()

      if(id){
        await fetch("/api/mobile/alumni/delete-appreciation/"+id)
      }

    }

    function updateInterestsEl (){
        let els = document.querySelector("#interests")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector(".title").innerHTML = `Minat ke ${i+1}`
            element.querySelector("input").placeholder = `Minat ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeInterest(${i})`)
        }
    }

    function addInterest(){
        let els = document.querySelector("#interests")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="title">Minat ke ${els.childElementCount+1}</p>
                    <div>
                        <button class="btn btn-success" type="button" data-target="#interest-${els.childElementCount}" data-toggle="collapse"><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-danger" type="button" onclick="removeInterest(${els.childElementCount})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="collapse" id="interest-${els.childElementCount}">
                    <div class="form-group">
                        <label for="">Bidang</label>
                        <input class="form-control" type="text" placeholder="Bidang" name="interests[${els.childElementCount}][bidang]" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Gaji</label>
                        <input class="form-control" type="text" placeholder="Gaji" name="interests[${els.childElementCount}][gaji]" value="" required>
                    </div>
                </div>
            </div>
                                    
                                    
        `
    }

    async function removeInterest(i,id = false){

        let els = document.querySelector("#interests")

        els.removeChild(els.childNodes[i])

        updateInterestsEl()

      if(id){
        await fetch("/api/mobile/alumni/delete-interest/"+id)
      }

    }

</script>
@endsection