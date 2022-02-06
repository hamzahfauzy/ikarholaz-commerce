<style>
    @page {
        margin:0px;
        size: 500px 300px;
    }
    #cardBg {
        width: 500px;
        height: 300px;
        background-size:cover !important;
        background-repeat:no-repeat;
        position:relative;
    }
    
    span{position:absolute;font-size:10px;}

    #nama{
        top:74px;
        left:280px;
    }

    #ttl{
        top:88px;
        left:280px;
    }

    #alamat{
        top:100px;
        left:280px;
    }

    #kelas{
        top:113px;
        left:280px;
    }

    #lulus{
        top:126px;
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