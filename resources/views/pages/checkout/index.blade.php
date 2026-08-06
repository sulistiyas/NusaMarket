@extends('layouts.app')

@section('title', 'Checkout — NusaMarket')

@section('content')
<h1 class="text-2xl font-bold mb-6">Pengiriman & Checkout</h1>

<form action="{{ route('checkout.store') }}" method="POST">
    @csrf
    <div class="cart-layout">
        {{-- Form Alamat Pengiriman --}}
        <div>
            <div class="card p-6 mb-6">
                <h3 class="font-bold text-lg mb-4"><i class="fas fa-map-marker-alt text-primary mr-2"></i> Alamat Pengiriman</h3>
                
                <div class="form-grid cols-2 mb-4">
                    <div class="form-group">
                        <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', '081234567890') }}" required>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', 'Jl. Merdeka No. 45, RT 02/RW 05') }}</textarea>
                </div>

                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', 'Jakarta Selatan') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Pos <span class="text-danger">*</span></label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', '12345') }}" required>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan per Toko --}}
            <div class="card p-6">
                <h3 class="font-bold text-lg mb-4"><i class="fas fa-store text-primary mr-2"></i> Rincian Pesanan Toko</h3>
                
                @foreach($groupedByStore as $storeId => $items)
                    <div class="border-b pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                        <div class="font-semibold text-sm text-primary mb-3">
                            <i class="fas fa-store"></i> {{ $items->first()->product->store->name ?? 'Toko NusaMarket' }}
                        </div>
                        @foreach($items as $item)
                            <div class="flex justify-between items-center mb-2 text-sm">
                                <div>
                                    <span class="font-medium">{{ $item->product->name }}</span>
                                    <span class="text-muted text-xs">x {{ $item->quantity }}</span>
                                </div>
                                <div class="font-semibold">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="cart-summary-card">
            <h3 class="font-bold text-lg mb-4">Ringkasan Pembayaran</h3>
            
            <div class="summary-row">
                <span>Subtotal Produk</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row">
                <span>Ongkos Kirim ({{ $groupedByStore->count() }} Toko)</span>
                <span>Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row grand-total">
                <span>Total Tagihan</span>
                <span class="text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3 mt-6 text-center justify-center">
                <i class="fas fa-check-circle mr-2"></i> Buat Pesanan Sekarang
            </button>
        </div>
    </div>
</form>
@endsection
