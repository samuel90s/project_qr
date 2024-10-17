<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product Title</th>
            <th>Category</th>
            <th>Price</th>
            <th>Serial</th>
            <th>Certificate</th>
            <th>Code Manufacturer</th>
            <th>Branch ID</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->serial }}</td>
                <td>{{ $product->certificate }}</td>
                <td>{{ $product->code_manufactur }}</td>
                <td>{{ $product->branch_id }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
