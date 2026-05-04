<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    /**
     * Get available products with stock > 0.
     * Requirement: A simple GET endpoint to retrieve available products.
     */
    public function index()
    {
        // We only show products that can actually be purchased
        return response()->json(Product::where('stock', '>', 0)->get());
    }

    /**
     * Submit a new order.
     * Requirement: POST endpoint to submit a new order with validation and calculation.
     */
    public function store(Request $request)
    {
        // 1. Validation: Ensure the products exist and quantities are positive integers
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // 2. Use a Database Transaction to ensure all or nothing is saved
            return DB::transaction(function () use ($request) {
                $total = 0;
                $orderItemsData = [];

                // Create the base order linked to the authenticated user[cite: 1]
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => 0 // Temporary total
                ]);

                foreach ($request->items as $item) {
                    // 3. Stock Management: Lock the row to prevent race conditions[cite: 1]
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    // Check if enough stock is available[cite: 1]
                    if ($product->stock < $item['quantity']) {
                        throw new Exception("Insufficient stock for product: {$product->name}");
                    }

                    // 4. Calculation: Total price based on product prices[cite: 1]
                    $lineTotal = $product->price * $item['quantity'];
                    $total += $lineTotal;

                    // Prepare the data for order_items (snapshotting current price)[cite: 1]
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price
                    ];

                    // Deduct stock from the inventory[cite: 1]
                    $product->decrement('stock', $item['quantity']);
                }

                // 5. Bulk insert order items for better performance[cite: 1]
                $order->items()->createMany($orderItemsData);

                // Finalize the total price of the order[cite: 1]
                $order->update(['total_price' => $total]);

                // Return success with eager-loaded items[cite: 1]
                return response()->json([
                    'message' => 'Order placed successfully!',
                    'order' => $order->load('items.product')
                ], 201);
            });
        } catch (Exception $e) {
            // Return clear error messages as required by the test[cite: 1]
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}