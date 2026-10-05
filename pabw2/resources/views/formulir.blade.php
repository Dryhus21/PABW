<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{route('post.page')}}" method="post">
        <label for="">Nama : </label>
        <input type="text" name= "nama">

        <label for="">NIM : </label>
        <input type="text" name= "nim">

        <label for="">Prodi : </label>
        <input type="text" name= "prodi">

        <button type='submit'>Kirim</button>
    </form>

    
</body>
</html>