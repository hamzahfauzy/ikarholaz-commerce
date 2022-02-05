@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row text-center">
                <div class="col-sm-12">
                    <h3 class="m-t-20">{{__('Profile')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12 col-md-8 m-auto">
                    <div class="card card-body">

                        @if(session('success'))
                            <div class="alert alert-success">{{session('success')}}</div>
                        @endif

                        <form action="{{route('edit-profile')}}" method="post" enctype="multipart/form-data">
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
                                    <label>Pekerjaan / Keahlian</label>
                                    <button class="btn btn-primary btn-sm" type="button" onclick="addSkill()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="skills">
                                    @foreach($alumni->skills as $i => $skill)
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="skills[{{$i}}][id]" value="{{$skill->id}}">
                                            <input class="form-control" type="text" placeholder="Pekerjaan / Keahlian ke {{$i+1}}" name="skills[{{$i}}][name]" value="{{$skill->name}}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-danger" type="button" onclick="removeSkill({{$i}},{{$skill->id}})"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button class="btn btn-primary">Edit Profile</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
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
            
            element.querySelector("input").placeholder = `Pekerjaan / Keahlian ke ${i+1}`
            element.querySelector("button").setAttribute('onclick',`removeSkill(${i+1})`)
        }
    }

    function addSkill(){
        let elSkills = document.querySelector("#skills")
        elSkills.innerHTML += `<div class="input-group mb-3">
                                        <input class="form-control" type="text" placeholder="Pekerjaan / Keahlian ke ${elSkills.childNodes.length}" name="skills[][name]" value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSkill(${elSkills.childNodes.length})"><i class="fas fa-times"></i></button>
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

</script>
@endsection