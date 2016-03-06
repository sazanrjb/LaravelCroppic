<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Upload and edit images in Laravel using Croppic jQuery plugin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    {!! HTML::style('css/croppic.css') !!}
    {!! HTML::style('css/main.css') !!}

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Mrs+Sheppards&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

</head>
<body>

<div class="container">
    @if(\Session::has('notice'))
        <p class="text-danger">{{\Session::get('notice')}}</p>
    @endif

    {!! Form::open(array('method'=>'PATCH','action'=>['MainController@update',$user->id])) !!}
        <div>
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{$user->name}}">
        </div>

        <div class="row margin-bottom-40">
            <div class=" col-md-3">
                Select Profile Picture
                <div id="cropContainerEyecandy"></div>
                <input type="hidden" id="imgName" name="imgName">
                Current picture
                <img src="{{$user->picture}}" width="100px" class="img img-responsive">
            </div>
        </div>

        <input type="submit" value="Submit">
    {!! Form::close() !!}
</div>
<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>
{!! HTML::script('js/jquery.mousewheel.min.js') !!}
{!! HTML::script('js/croppic.min.js') !!}
{!! HTML::script('js/main.js') !!}
<script>
    var eyeCandy = $('#cropContainerEyecandy');
    var croppedOptions = {
        uploadUrl: '{{url('upload')}}',
        cropUrl: '{{url('crop')}}',
        {{--loadPicture:'{{url($user->picture)}}',--}}
        modal:true,
        cropData:{
            'width' : eyeCandy.width(),
            'height': eyeCandy.height()
        },
        onReset:function(){ console.log('onReset') },
        onAfterImgCrop:function(data){ if(data.status == 'success'){ $('#imgName').val(data.url) }},
    };

    $('.cropControlRemoveCroppedImage').on('click',function(){
        console.log($('#imgName').val());
    });
    var cropperBox = new Croppic('cropContainerEyecandy', croppedOptions);
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>