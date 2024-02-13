<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ url('/store') }}" enctype="multipart/form-data" method="post">
        @method('post')
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            {{ session('status') }}
        @endif
        <div class="form-group">
            <label for="">File</label>
            <input type="file" name="file">
        </div>
        <button>simpan</button>
    </form>
</body>

</html>
