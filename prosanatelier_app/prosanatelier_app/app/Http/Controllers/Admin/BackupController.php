<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class BackupController extends Controller
{
    public function index()
    {
        $databaseName = DB::connection()->getDatabaseName();
        $tableCount = 0;
        $databaseOk = false;

        try {
            $tableCount = count($this->tables());
            $databaseOk = true;
        } catch (Throwable $e) {
            $databaseOk = false;
        }

        return view('admin.backups.index', compact('databaseName', 'tableCount', 'databaseOk'));
    }

    public function database(): StreamedResponse
    {
        $filename = 'prosan-db-backup-' . now()->format('Y-m-d-His') . '.sql';

        return response()->streamDownload(function () {
            $pdo = DB::connection()->getPdo();
            $database = DB::connection()->getDatabaseName();

            echo "-- Prosan Atelier Database Backup\n";
            echo "-- Generated: " . now()->toDateTimeString() . "\n";
            echo "-- Database: `{$database}`\n\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($this->tables() as $table) {
                echo "DROP TABLE IF EXISTS `{$table}`;\n";

                $create = (array) DB::selectOne('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`');
                $createSql = $create['Create Table'] ?? array_values($create)[1] ?? null;

                if ($createSql) {
                    echo $createSql . ";\n\n";
                }

                DB::table($table)->orderByRaw('1')->chunk(300, function ($rows) use ($table, $pdo) {
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        $columns = array_map(fn ($column) => '`' . str_replace('`', '``', $column) . '`', array_keys($data));
                        $values = array_map(function ($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }

                            if (is_bool($value)) {
                                return $value ? '1' : '0';
                            }

                            return $pdo->quote((string) $value);
                        }, array_values($data));

                        echo 'INSERT INTO `' . str_replace('`', '``', $table) . '` (' . implode(',', $columns) . ') VALUES (' . implode(',', $values) . ");\n";
                    }
                });

                echo "\n";
            }

            echo "SET FOREIGN_KEY_CHECKS=1;\n";
        }, $filename, ['Content-Type' => 'application/sql; charset=UTF-8']);
    }

    public function orders(): StreamedResponse
    {
        return $this->csv('prosan-orders-backup-' . now()->format('Y-m-d-His') . '.csv', function ($handle) {
            fputcsv($handle, ['Order Number', 'Customer', 'Phone', 'Email', 'District/City', 'Subtotal', 'Discount', 'Shipping', 'Total', 'Payment Method', 'Payment Status', 'Order Status', 'Created At']);

            DB::table('orders')->orderByDesc('id')->chunk(500, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number ?? '',
                        $order->customer_name ?? '',
                        $order->customer_phone ?? '',
                        $order->customer_email ?? '',
                        $order->city ?? $order->district ?? '',
                        $order->subtotal ?? 0,
                        $order->discount_total ?? 0,
                        $order->shipping_total ?? 0,
                        $order->grand_total ?? 0,
                        $order->payment_method ?? '',
                        $order->payment_status ?? '',
                        $order->order_status ?? '',
                        $order->created_at ?? '',
                    ]);
                }
            });
        });
    }

    public function products(): StreamedResponse
    {
        return $this->csv('prosan-products-backup-' . now()->format('Y-m-d-His') . '.csv', function ($handle) {
            fputcsv($handle, ['ID', 'Name', 'SKU', 'Slug', 'Regular Price', 'Sale Price', 'Purchase Price', 'Stock', 'Status', 'Created At']);

            DB::table('products')->orderBy('id')->chunk(500, function ($products) use ($handle) {
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->id ?? '',
                        $product->name ?? '',
                        $product->sku ?? '',
                        $product->slug ?? '',
                        $product->regular_price ?? 0,
                        $product->sale_price ?? '',
                        $product->purchase_price ?? $product->cost_price ?? '',
                        $product->stock_quantity ?? 0,
                        isset($product->is_active) ? ($product->is_active ? 'active' : 'inactive') : '',
                        $product->created_at ?? '',
                    ]);
                }
            });
        });
    }

    public function customers(): StreamedResponse
    {
        return $this->csv('prosan-customers-backup-' . now()->format('Y-m-d-His') . '.csv', function ($handle) {
            fputcsv($handle, ['Name', 'Phone', 'Email', 'Address', 'District/City', 'Thana/Area', 'Created At']);

            DB::table('customers')->orderByDesc('id')->chunk(500, function ($customers) use ($handle) {
                foreach ($customers as $customer) {
                    fputcsv($handle, [
                        $customer->name ?? '',
                        $customer->phone ?? '',
                        $customer->email ?? '',
                        $customer->address_line ?? $customer->address ?? '',
                        $customer->city ?? $customer->district ?? '',
                        $customer->area ?? $customer->thana ?? '',
                        $customer->created_at ?? '',
                    ]);
                }
            });
        });
    }

    private function csv(string $filename, callable $writer): StreamedResponse
    {
        return response()->streamDownload(function () use ($writer) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            $writer($handle);
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function tables(): array
    {
        $database = DB::connection()->getDatabaseName();
        $key = 'Tables_in_' . $database;

        return collect(DB::select('SHOW TABLES'))
            ->map(function ($row) use ($key) {
                $array = (array) $row;
                return $array[$key] ?? current($array);
            })
            ->filter()
            ->values()
            ->all();
    }
}
