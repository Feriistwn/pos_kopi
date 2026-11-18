<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('nama')->get();

        $products = Product::with('category')
            ->orderBy('nama')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category->nama ?? 'Tanpa Kategori',
                    'nama' => $product->nama,
                    'harga' => (int) $product->harga,
                    'stok' => (int) $product->stok,
                    'image_url' => $product->image ? asset('storage/'.$product->image) : null,
                ];
            });

        return view('pos.index', [
            'categories' => $categories,
            'productsJson' => $products,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'metode_pembayaran' => ['required', 'in:tunai,qris'],
            'uang_diterima' => ['nullable', 'integer', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.kuantitas' => ['required', 'integer', 'min:1'],
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'items.required' => 'Keranjang pesanan masih kosong.',
            'items.min' => 'Pilih minimal satu produk untuk transaksi.',
            'items.*.kuantitas.min' => 'Kuantitas produk minimal 1.',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {
                $totalHarga = 0;
                $detailsToInsert = [];
                $productUpdates = [];

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if (! $product) {
                        throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                    }

                    $qty = (int) $item['kuantitas'];

                    if ($product->stok < $qty) {
                        throw new \Exception("Stok untuk \"{$product->nama}\" tidak mencukupi. Sisa stok: {$product->stok}.");
                    }

                    $subtotal = $product->harga * $qty;
                    $totalHarga += $subtotal;

                    $detailsToInsert[] = [
                        'product_id' => $product->id,
                        'nama_produk' => $product->nama,
                        'kuantitas' => $qty,
                        'harga_satuan' => $product->harga,
                        'subtotal' => $subtotal,
                    ];

                    $productUpdates[] = [
                        'model' => $product,
                        'new_stok' => $product->stok - $qty,
                    ];
                }

                $metode = $request->metode_pembayaran;
                $uangDiterima = (int) ($request->uang_diterima ?? 0);

                if ($metode === 'tunai' && $uangDiterima < $totalHarga) {
                    throw new \Exception('Jumlah uang tunai yang diterima kurang dari total belanja.');
                }

                // Generate invoice: INV-YYYYMMDD-XXXXX
                $todayPrefix = 'INV-'.Carbon::now()->format('Ymd').'-';
                $lastInvoice = Transaction::where('nomor_invoice', 'like', $todayPrefix.'%')
                    ->latest('id')
                    ->value('nomor_invoice');

                $nextNumber = 1;
                if ($lastInvoice) {
                    $lastSequence = (int) substr($lastInvoice, -5);
                    $nextNumber = $lastSequence + 1;
                }
                $invoiceNumber = $todayPrefix.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'nomor_invoice' => $invoiceNumber,
                    'user_id' => Auth::id(),
                    'total_harga' => $totalHarga,
                    'metode_pembayaran' => $metode,
                    'status' => 'selesai',
                ]);

                foreach ($detailsToInsert as $detail) {
                    $detail['transaction_id'] = $transaction->id;
                    TransactionDetail::create($detail);
                }

                foreach ($productUpdates as $update) {
                    $update['model']->update(['stok' => $update['new_stok']]);
                }

                return $transaction;
            });

            $kembalian = 0;
            if ($request->metode_pembayaran === 'tunai') {
                $kembalian = max(0, ((int) $request->uang_diterima) - $transaction->total_harga);
            }

            return response()->json([
                'sukses' => true,
                'pesan' => 'Transaksi berhasil diproses!',
                'data' => [
                    'id' => $transaction->id,
                    'nomor_invoice' => $transaction->nomor_invoice,
                    'total_harga' => $transaction->total_harga,
                    'metode_pembayaran' => $transaction->metode_pembayaran,
                    'uang_diterima' => (int) $request->uang_diterima,
                    'kembalian' => $kembalian,
                    'receipt_url' => route('pos.receipt', $transaction),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'sukses' => false,
                'pesan' => $e->getMessage(),
            ], 422);
        }
    }

    public function receipt(Transaction $transaction): View
    {
        $transaction->load(['user', 'details.product.category']);

        return view('pos.receipt', compact('transaction'));
    }
}
