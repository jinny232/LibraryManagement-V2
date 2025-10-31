<!DOCTYPE html>

<html>
<head>
<title>Barcode for {{ $book->title }}</title>
<style>
body {
font-family: sans-serif;
text-align: center;
padding: 50px;
}
h1 {
color: #333;
}
.barcode {
margin: 20px auto;
width: 250px;
}
</style>
</head>
<body>

<h1>{{ $book->title }}</h1>
<p><strong>ISBN:</strong> {{ $book->isbn }}</p>

<div class="barcode">
    {!! $book->barcode !!}
</div>

</body>
</html>
