<style>
    #cardBg {
        width: 500;
        height: 300;
        background-size:cover !important;
        background-repeat:no-repeat;
        position:relative;
    }
    
    span{position:absolute;font-size:10px;}

    #nama{
        top:80px;
        left:280px;
    }

    #ttl{
        top:94px;
        left:280px;
    }

    #alamat{
        top:108px;
        left:280px;
    }

    #kelas{
        top:121px;
        left:280px;
    }

    #lulus{
        top:134px;
        left:280px;
    }
</style>

@if((int) $alumni->graduation_year <= 1980) 

<div id="cardBg" style="background:url({{asset('assets/v-card/80.jpg')}})">
    <span id="nama">{{$alumni->name}}</span>
    <span id="ttl">{{$alumni->place_of_birth}}/{{$alumni->date_of_birth}}</span>
    <span id="alamat">{{$alumni->address}}</span>
    <span id="kelas">{{$alumni->class_name}}</span>
    <span id="lulus">{{$alumni->graduation_year}}</span>
</div>

@elseif((int) $alumni->graduation_year <= 1990)

<div id="cardBg" style="background:url({{asset('assets/v-card/90.jpg')}})">
    <span id="nama">{{$alumni->name}}</span>
    <span id="ttl">{{$alumni->place_of_birth}}/{{$alumni->date_of_birth}}</span>
    <span id="alamat">{{$alumni->address}}</span>
    <span id="kelas">{{$alumni->class_name}}</span>
    <span id="lulus">{{$alumni->graduation_year}}</span>
</div>

@elseif((int) $alumni->graduation_year <= 2000) 

<div id="cardBg" style="background:url({{asset('assets/v-card/2000.jpg')}})">
    <span id="nama">{{$alumni->name}}</span>
    <span id="ttl">{{$alumni->place_of_birth}}/{{$alumni->date_of_birth}}</span>
    <span id="alamat">{{$alumni->address}}</span>
    <span id="kelas">{{$alumni->class_name}}</span>
    <span id="lulus">{{$alumni->graduation_year}}</span>
</div>

@else

<div id="cardBg" style="background:url({{asset('assets/v-card/2001.jpg')}})">
    <span id="nama">{{$alumni->name}}</span>
    <span id="ttl">{{$alumni->place_of_birth}}/{{$alumni->date_of_birth}}</span>
    <span id="alamat">{{$alumni->address}}</span>
    <span id="kelas">{{$alumni->class_name}}ss</span>
    <span id="lulus">{{$alumni->graduation_year}}</span>
</div>

@endif