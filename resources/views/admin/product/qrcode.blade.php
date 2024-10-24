<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code for {{ $product->title }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .card {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        img {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="text-center">QR Code for Product: {{ $product->title }}</h1>
        <p class="text-center">Scan the QR code below to view product details.</p>
        <div class="text-center">
            <img src="data:image/png;base64,{{ $imageData }}" alt="QR Code" class="img-fluid">
        </div>
        <p class="text-center mt-3">Product ID: {{ $product->id }}</p>
        <p class="text-center">Category: {{ $product->category }}</p>
        <p class="text-center">Pricess: {{ $product->price }}</p>
        <p class="text-center">Serial Number: {{ $product->serial }}</p>
        <p class="text-center">Created By: {{ $product->created_by }}</p>
        <p class="text-center">Created At: {{ $product->created_at }}</p>
        <p class="text-center">Updated At: {{ $product->updated_at }}</p>
    </div>
</body>
</html>
