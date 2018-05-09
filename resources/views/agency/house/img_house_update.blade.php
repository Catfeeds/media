<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>修改房源图片</title>
</head>
<body>
    <div>
        <h1>户型图</h1>
        <h1>室内图</h1>
        {{-- {{dd($result)}} --}}
        {{$result->house_type_img}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script>
        // var house_img = {{$result->house_type_img}}
        // var indoor_img = {{$result->indoor_img}}
    </script>
</body>
</html>