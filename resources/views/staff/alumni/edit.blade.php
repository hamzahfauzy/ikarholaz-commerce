@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [],
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
                        <div class="form-group">
                            <label for="">NRA</label>
                            <input type="text" name="NRA" value="{{$alumni->NRA}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="name" value="{{$alumni->user->name}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">No HP</label>
                            <input type="text" name="phone" value="{{$alumni->user->email}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" value="{{$alumni->email}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Tahun Lulus</label>
                            <input type="text" name="graduation_year" value="{{$alumni->graduation_year}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Kelamin</label>
                            <select name="gender" id="" class="form-control">
                                <option value="" readonly selected>- Pilih Jenis Kelamin -</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tempat Lahir</label>
                            <input type="text" name="place_of_birth" value="{{$alumni->place_of_birth}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" value="{{$alumni->date_of_birth}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Negara</label>
                            <input type="text" name="country" value="{{$alumni->country}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Provinsi</label>
                            <select name="province" id="" class="form-control" onchange="getDistrict(this.value,'#city_id')">
                                <option value="" readonly selected>- Pilih Provinsi -</option>
                                @foreach($provincies as $province)
                                    <option {{ $alumni->province == $province->province ? 'selected' : '' }} value="{{$province->province}}">{{$province->province}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Kabupaten / Kota</label>
                            <select name="city" id="city_id" class="form-control">
                                <option value="">- Pilih Provinsi terlebih dahulu -</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="address" id="" cols="30" rows="10" class="form-control">{{$alumni->address}}</textarea>
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
                                        <input type="hidden" name="skills[][id]">
                                        <input class="form-control" type="text" placeholder="Pekerjaan / Keahlian ke {{$i+1}}" name="skills[][name]" value="{{$skill->name}}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSkill({{$i}},{{$skill->id}})"><i class="fas fa-times"></i></button>
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