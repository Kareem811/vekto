<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your order!</h1>
    <p>Your order has been placed successfully. Here are your details:</p>

    <h2>Order #{{ $order->id }}</h2>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Total:</strong> ${{ $order->total_amount }}</p>
    <h3>Items:</h3>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->product->name }} - (x{{ $item->quantity }}) - ${{ $item->price * $item->quantity }}</li>
        @endforeach
    </ul>
    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
    <p>We'll notify you once your order is shipped.</p>
</body>
</html>
