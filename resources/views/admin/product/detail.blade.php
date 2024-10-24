<!-- resources/views/public/product/detail.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
</head>
<body>
    <h1>{{ $product->title }}</h1>
    <p>Category: {{ $product->category }}</p>
    <p>Price: ${{ $product->price }}</p>
    <p>Serial Number: {{ $product->serial }}</p>
    <p>Certificate: {{ $product->certificate }}</p>
    @if($product->image)
        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}">
    @endif
</body>
</html>
