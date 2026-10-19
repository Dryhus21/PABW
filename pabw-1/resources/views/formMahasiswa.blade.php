<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Biodata</title>
</head>
<body>
    <h2>Input Biodata</h2>
    <form action="/prosesMahasiswa" method="post">
        @csrf

        <label> NIM : </label>
        <input type="text" name="nim"> <br> <br>


        <label> Nama : </label>
        <input type="text" name="nama"> <br> <br>

        <label> Program Studi : </label>
        <input type="text" name="prodi" id="prodi"> <br> <br>

        <label> Semester :</label>
        <input type="number" name="semester" id="semester"> <br> <br>

        <button type="submit"> Kirim </button>
    </form>
</body>
</html>