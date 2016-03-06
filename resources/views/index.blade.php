<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Upload and edit images in Laravel using Croppic jQuery plugin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">
    @if(\Session::has('notice'))
        <p class="text-danger">{{\Session::get('notice')}}</p>
    @endif

    <a href="{{url('main/create')}}" class="btn btn-primary">Insert</a>

    @if(\App\User::count() > 0)
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Picture</th>
                <th>Action</th>
            </tr>
            @foreach(\App\User::all() as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{!! HTML::image($user->picture) !!}</td>
{{--                    <td><a href="{{url('main.edit',$user->id)}}">Edit</a> &middot; <a href="{{url('delete',$user->id)}}">Delete</a> </td>--}}
                    <td><a href="{{url('main/'.$user->id.'/edit')}}">Edit</a> &middot; <a href="{{url('main/delete',$user->id)}}">Delete</a> </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No data found</p>
    @endif
</div>
<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>

</body>
</html>