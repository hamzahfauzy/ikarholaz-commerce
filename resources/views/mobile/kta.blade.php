<style>
    @page {
        margin:0px;
        size: 650px 400px;
    }
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


<div id="cardBg">
    <img src="{{$bg}}" alt="" width="100%" height="100%">
    <span id="nama">{{$alumni->name}}</span>
    <span id="ttl">{{$alumni->place_of_birth}}/{{$alumni->date_of_birth}}</span>
    <span id="alamat">{{$alumni->address}}</span>
    <span id="kelas">{{$alumni->class_name}}</span>
    <span id="lulus">{{$alumni->graduation_year}}</span>
</div>