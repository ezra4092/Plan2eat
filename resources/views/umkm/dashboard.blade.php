<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard UMKM</title>
</head>
<body>
    
    <h2>Dashboard UMKM</h2>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <form action="{{ route('umkm.profil.index') }}" method="GET">
        <button type="submit">Profil</button>
    </form>

</body>
</html>