@extends('layouts.app')

@section('content')
    <div class="container app_content">
        <div class="row">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form id="articleForm" role="form" method="post" action="{{ url('/article') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" placeholder="文章标题" id="title" name="title">

                        <label for="image">Image:</label>
                        <input type="file" id="image" name="file" onchange="uploadImage()">
                        <input type="hidden" name="articleimage" id="articleimage">

                        <label for="content">Content:</label>
                        <div id="summernote">Hello aroundu</div>
                        <input type="hidden" id="content" name="content">

                        <label for="location">Location:</label>
                        <div id="r-result"><input type="text" id="suggestId" size="15" style="width:300px;" /></div>
                        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
                        <div id="allmap"></div>
                        <input type="hidden" id="location" name="location">

                        <label for="type">Type:</label>
                        <input type="radio" name="type" value="1" checked> 长文
                        <input type="radio" name="type" value="2"> 短文

                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5">
                            <button id="submitButton" class="btn btn-primary" type="button" onclick="submitAll();">submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-danger">返回</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <!-- include summernote css-->
    <link href="/dist/summernote.css" rel="stylesheet">
    <style type="text/css">
        #allmap {width: 500px;height: 300px;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
@endsection

@section('js')
    <script>
        function uploadImage() {
            data = new FormData();
            data.append("file", $('#image')[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                type: "POST",
                url: "/uploadImage",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    $('#articleimage').attr('value', url);
                }
            });
        }
    </script>
    <!-- include summernote js-->
    <script src="/dist/summernote.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,
                callbacks: {
                    onImageUpload: function(files, editor, $editable) {
                        sendFile(files[0], editor, $editable);
                    }
                }
            });
            function sendFile(file, editor, $editable) {
                data = new FormData();
                data.append("file", file);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    type: "POST",
                    url: "/uploadImage",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(url) {
                        $('#summernote').summernote("insertImage", url);
                    }
                });
            }
        });

        function submitAll() {
            var markupStr = $('#summernote').summernote('code');
            G('content').value = markupStr;
            G('articleForm').submit();
        }
    </script>
    <script type="text/javascript">
        //百度地图API功能
        function loadJScript() {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "http://api.map.baidu.com/api?v=2.0&ak=OKFfe9ardMcoAhUjfBLGy7S1KkcbrLRo&callback=init";
            document.body.appendChild(script);
        }
        function G(id) {
            return document.getElementById(id);
        }

        function init() {
//            var map = new BMap.Map("allmap");            // 创建Map实例
//            var point = new BMap.Point(116.404, 39.915); // 创建点坐标
//            map.centerAndZoom(point,15);
//            map.enableScrollWheelZoom();                 //启用滚轮放大缩小
            // 百度地图API功能
            var map = new BMap.Map("allmap");
            map.centerAndZoom(new BMap.Point(110.330, 20.022), 14);
            // 添加带有定位的导航控件
            var navigationControl = new BMap.NavigationControl({
                // 靠左上角位置
                anchor: BMAP_ANCHOR_TOP_LEFT,
                // LARGE类型
                type: BMAP_NAVIGATION_CONTROL_LARGE,
                // 启用显示定位
                enableGeolocation: true
            });
            map.addControl(navigationControl);
            // 添加定位控件
            var geolocationControl = new BMap.GeolocationControl();
            geolocationControl.addEventListener("locationSuccess", function(e){
                // 定位成功事件
                var address = '';
                address += e.addressComponent.province;
                address += e.addressComponent.city;
                address += e.addressComponent.district;
                address += e.addressComponent.street;
                address += e.addressComponent.streetNumber;
                alert("当前定位地址为：" + address);
            });
            geolocationControl.addEventListener("locationError",function(e){
                // 定位失败事件
                alert(e.message);
            });
            map.addControl(geolocationControl);
            var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
                {   "input" : "suggestId",
                    "location" : map
                });
            ac.addEventListener("onhighlight", function (e) {
                var str = "";
                var _value = e.fromitem.value;
                var value = "";
                if (e.fromitem.index > -1) {
                    value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                }
                str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

                value = "";
                if (e.toitem.index > -1) {
                    _value = e.toitem.value;
                    value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                }
                str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
                G("searchResultPanel").innerHTML = str;
            });
            var myValue;
            ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
                var _value = e.item.value;
                myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
                setPlace();
            });
            function setPlace(){
                map.clearOverlays();    //清除地图上所有覆盖物
                function myFun(){
                    var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                    alert("您选择的地址为：" + local.getResults().getPoi(0).address);
                    map.centerAndZoom(pp, 18);
                    map.addOverlay(new BMap.Marker(pp));    //添加标注
                    G("location").value = JSON.stringify(local.getResults().getPoi(0));
                }
                var local = new BMap.LocalSearch(map, { //智能搜索
                    onSearchComplete: myFun
                });
                local.search(myValue);
            }
        }
        window.onload = loadJScript;  //异步加载地图
    </script>
@endsection