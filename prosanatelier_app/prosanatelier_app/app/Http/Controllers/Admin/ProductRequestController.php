<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest as ProductRequestModel;
use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    public function index(Request $request)
    {
        $statuses = ProductRequestModel::STATUSES;

        $requests = ProductRequestModel::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim((string) $request->q);
                $query->where(function ($inner) use ($q) {
                    $inner->where('customer_name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('product_name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => ProductRequestModel::count(),
            'new' => ProductRequestModel::where('status', 'new')->count(),
            'checking' => ProductRequestModel::where('status', 'checking')->count(),
            'available_soon' => ProductRequestModel::where('status', 'available_soon')->count(),
            'completed' => ProductRequestModel::where('status', 'completed')->count(),
        ];

        return view('admin.product_requests.index', compact('requests', 'statuses', 'summary'));
    }

    public function update(Request $request, ProductRequestModel $productRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(ProductRequestModel::STATUSES))],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $productRequest->update($validated);

        return back()->with('success', 'Product request updated.');
    }

    public function destroy(ProductRequestModel $productRequest)
    {
        $productRequest->delete();

        return back()->with('success', 'Product request deleted.');
    }

    public function export(Request $request)
    {
        $filename = 'product-requests-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Status', 'Name', 'Phone', 'Email', 'Product', 'Brand', 'Product Link', 'Quantity', 'Message', 'Admin Note', 'Source']);

            ProductRequestModel::query()
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
                ->latest()
                ->chunk(200, function ($items) use ($handle) {
                    foreach ($items as $item) {
                        fputcsv($handle, [
                            optional($item->created_at)->format('Y-m-d H:i:s'),
                            $item->status_label,
                            $item->customer_name,
                            $item->phone,
                            $item->email,
                            $item->product_name,
                            $item->brand,
                            $item->product_link,
                            $item->quantity,
                            $item->message,
                            $item->admin_note,
                            $item->source,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
