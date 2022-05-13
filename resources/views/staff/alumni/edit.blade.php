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
                            <input type="number" name="year_in" value="{{old('year_in') ?? $alumni->year_in}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Tahun Lulus</label>
                            <input type="text" name="graduation_year" value="{{old('graduation_year') ?? $alumni->graduation_year}}" class="form-control">
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
                                            <p>Usaha / Bisnis ke {{$i+1}}</p>
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
                                                <input class="form-control" type="text" placeholder="Sektor" name="businesses[{{$i}}][sektor]" value="{{$business->sektor}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Badan Hukum</label>
                                                <input class="form-control" type="text" placeholder="Badan Hukum" name="businesses[{{$i}}][badan_hukum]" value="{{$business->badan_hukum}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Kepemilikan</label>
                                                <input class="form-control" type="text" placeholder="Kepemilikan" name="businesses[{{$i}}][kepemilikan]" value="{{$business->kepemilikan}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Status kepemilikan</label>
                                                <input class="form-control" type="text" placeholder="Status kepemilikan" name="businesses[{{$i}}][status_kepemilikan]" value="{{$business->status_kepemilikan}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Skala</label>
                                                <input class="form-control" type="text" placeholder="Skala" name="businesses[{{$i}}][skala]" value="{{$business->skala}}">
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
                                                <label for="">Ujin Usaha</label>
                                                <input class="form-control" type="text" placeholder="Ujin Usaha" name="businesses[{{$i}}][ijin_usaha]" value="{{$business->ijin_usaha}}">
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
                                            <p>Komunitas ke {{$i+1}}</p>
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
                                                <input class="form-control" type="text" placeholder="Bidang" name="communities[{{$i}}][bidang]" value="{{$community->bidang}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Cakupan</label>
                                                <input class="form-control" type="text" placeholder="Cakupan" name="communities[{{$i}}][cakupan]" value="{{$community->cakupan}}">
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
                                                <input class="form-control" type="text" placeholder="Keaktifan" name="communities[{{$i}}][keaktifan]" value="{{$community->keaktifan}}">
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
                                            <p>Pekerjaan / Profesi ke {{$i+1}}</p>
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
                                                <input class="form-control" type="text" placeholder="Bidang" name="professions[{{$i}}][bidang]" value="{{$profession->bidang}}">
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

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector("input").placeholder = `Usaha / Bisnis ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeBusiness(${i+1})`)
        }
    }

    function addBusiness(){
        let els = document.querySelector("#businesses")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p>Usaha / Bisnis ke ${els.childElementCount+1}</p>
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
                        <input class="form-control" type="text" placeholder="Sektor" name="businesses[${els.childElementCount}][sektor]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Badan Hukum</label>
                        <input class="form-control" type="text" placeholder="Badan Hukum" name="businesses[${els.childElementCount}][badan_hukum]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Kepemilikan</label>
                        <input class="form-control" type="text" placeholder="Kepemilikan" name="businesses[${els.childElementCount}][kepemilikan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Status kepemilikan</label>
                        <input class="form-control" type="text" placeholder="Status kepemilikan" name="businesses[${els.childElementCount}][status_kepemilikan]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Skala</label>
                        <input class="form-control" type="text" placeholder="Skala" name="businesses[${els.childElementCount}][skala]" value="">
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
                        <label for="">Ujin Usaha</label>
                        <input class="form-control" type="text" placeholder="Ujin Usaha" name="businesses[${els.childElementCount}][ijin_usaha]" value="">
                    </div>
                </div>
            </div>
        `

    }

    async function removeBusiness(i,id = false){

        let els = document.querySelector("#businesses")

        els.removeChild(els.childNodes[i])

        updateBusinessesEl()

    //   if(id){
    //     await fetch("/api/mobile/alumni/delete-business/"+id)
    //   }

    }

    function updateCommunitiesEl (){
        let els = document.querySelector("#communities")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector("input").placeholder = `Komunitas ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeCommunity(${i+1})`)
        }
    }

    function addCommunity(){
        let els = document.querySelector("#communities")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p>Komunitas ke ${els.childElementCount+1}</p>
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
                        <input class="form-control" type="text" placeholder="Bidang" name="communities[${els.childElementCount}][bidang]" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Cakupan</label>
                        <input class="form-control" type="text" placeholder="Cakupan" name="communities[${els.childElementCount}][cakupan]" value="">
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

    //   if(id){
    //     await fetch("/api/mobile/alumni/delete-business/"+id)
    //   }

    }

    
    function updateProfessionsEl (){
        let els = document.querySelector("#professions")

        for (let i = 0; i < els.children.length; i++) {
            const element = els.children[i];
            
            element.querySelector("input").placeholder = `Pekerjaan / Profesi ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeProfession(${i+1})`)
        }
    }

    function addProfession(){
        let els = document.querySelector("#professions")
        els.innerHTML += `
            <div class="card card-body mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p>Pekerjaan / Profesi ke ${els.childElementCount+1}</p>
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
                        <input class="form-control" type="text" placeholder="Bidang" name="professions[${els.childElementCount}][bidang]" value="">
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

    //   if(id){
    //     await fetch("/api/mobile/alumni/delete-business/"+id)
    //   }

    }

</script>
@endsection