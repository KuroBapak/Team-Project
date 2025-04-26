<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Thank You, {{ $order->buyer_name }}!</h2>
        </div>
        <div class="card-body">
            <p><strong>Total Items Purchased:</strong> {{ $totalItems }}</p>
            <p><strong>Order Amount:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} IDR</p>
            <hr>
            <h4>Your Verification Code</h4>
            <p class="display-4 text-monospace">{{ $code }}</p>
            <p class="text-muted">
                Please show this code to the cashier/admin to confirm your payment.
            </p>
            <a href="{{ route('products') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
</div>
</body>
</html>
