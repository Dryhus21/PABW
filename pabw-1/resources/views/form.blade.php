<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Biodata</title>
</head>
<body>
    <h2>Input Biodata</h2>
    <form action="/proses" method="post">
        @csrf
        <label> Nama : </label>
        <input type="text" name="nama"> <br> <br>

        <label> Umur : </label>
        <input type="number" name="umur" id="umur"> <br> <br>

        <label> Alamat :</label>
        <textarea name="alamat"></textarea> <br> <br>

        <button type="submit"> Kirim </button>
    </form>
</body>
</html>