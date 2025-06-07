<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reand Pay</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-xl mx-auto mt-10 p-8 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4 text-center">Checkout Barang</h1>
    <form method="POST" action="/checkout" id="checkout-form" class="space-y-4">
      @csrf
    <input type="text" name="name" placeholder="Nama" required class="w-full border px-4 py-2 rounded" />
    <input type="email" name="email" placeholder="Email" required class="w-full border px-4 py-2 rounded" />
    <input type="text" name="phone" placeholder="Nomor Telepon" required class="w-full border px-4 py-2 rounded" />
    <select name="product_id" class="w-full border px-4 py-2 rounded" required>
        @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }} (Rp {{ number_format($product->price) }})</option>
        @endforeach
    </select>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Bayar Sekarang</button>
</form>

<!-- Tempat menampilkan hasil -->
<div id="payment-details" class="mt-6"></div>
<script>
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const csrf = formData.get('_token');

    const response = await fetch('/checkout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
        },
        body: formData
    });

    const result = await response.json();
    const details = document.getElementById('payment-details');

    if (result.success) {
        const tripay = result.tripay;
        const product = result.product;
        const customer = result.customer;

        details.innerHTML = `
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Rincian Pembayaran</h2>
                <p><strong>Nama:</strong> ${customer.name}</p>
                <p><strong>Email:</strong> ${customer.email}</p>
                <p><strong>Produk:</strong> ${product.name}</p>
                <p><strong>Harga:</strong> Rp ${parseInt(product.price).toLocaleString()}</p>
                <p><strong>Metode Pembayaran:</strong> ${tripay.payment_name}</p>

                ${tripay.qr_url ? `
                    <div class="mt-4 text-center">
                        <p>Scan QR Code:</p>
                        <img src="${tripay.qr_url}" class="mx-auto w-60" />
                    </div>
                ` : ''}

                ${tripay.pay_code ? `
                    <div class="mt-4">
                        <p class="font-medium">Kode Pembayaran:</p>
                        <div class="bg-gray-100 px-4 py-2 rounded text-center font-mono">
                            ${tripay.pay_code}
                        </div>
                    </div>
                ` : ''}

                <div class="mt-4 text-center">
                    <a href="${tripay.checkout_url}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Bayar via Tripay</a>
                </div>
            </div>
        `;
    } else {
        details.innerHTML = `<p class="text-red-600">Terjadi kesalahan: ${result.message || 'Unknown error'}</p>`;
    }
});
</script>

  </div>
</body>
</html>
