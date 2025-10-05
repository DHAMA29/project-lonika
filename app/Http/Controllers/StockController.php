<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\StockAvailabilityService;
use App\Models\Barang;
use Carbon\Carbon;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockAvailabilityService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Check availability for a specific item and date range
     */
    public function checkAvailability(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $barang = Barang::findOrFail($id);
            
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $availability = $this->stockService->checkAvailability(
                $barang->id,
                $startDate,
                $endDate
            );

            return response()->json([
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama,
                'stok_tersedia' => $barang->stok,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'duration_days' => $startDate->diffInDays($endDate) + 1
                ],
                'availability' => [
                    'is_available' => $availability['available'],
                    'available_quantity' => $availability['available_quantity'],
                    'conflicts' => $availability['conflicts'] ?? []
                ],
                'message' => $availability['available'] 
                    ? 'Item tersedia untuk periode yang diminta' 
                    : 'Item tidak tersedia untuk periode yang diminta'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Get calendar view of availability for a specific month
     */
    public function getCalendarAvailability(Request $request, $id): JsonResponse
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $barang = Barang::findOrFail($id);
        
        $month = $request->month ? Carbon::parse($request->month . '-01') : now();
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $calendar = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate->lte($endOfMonth)) {
            $availability = $this->stockService->checkAvailability(
                $barang->id,
                $currentDate,
                $currentDate
            );

            $calendar[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->format('l'),
                'is_available' => $availability['available'],
                'available_quantity' => $availability['available_quantity'],
                'conflicts' => $availability['conflicts'] ?? []
            ];

            $currentDate->addDay();
        }

        return response()->json([
            'barang_id' => $barang->id,
            'nama_barang' => $barang->nama,
            'stok_tersedia' => $barang->stok,
            'month' => $month->format('Y-m'),
            'calendar' => $calendar
        ]);
    }

    /**
     * Check availability for multiple products (batch)
     */
    public function batchAvailabilityCheck(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'products' => 'required|array',
                'products.*.id' => 'required|integer|exists:barang,id',
                'products.*.quantity' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $startDate = Carbon::parse($request->start_date ?? now());
            $endDate = Carbon::parse($request->end_date ?? now()->addDay());
            
            $results = [];
            
            foreach ($request->products as $productData) {
                $barang = Barang::find($productData['id']);
                $quantity = $productData['quantity'] ?? 1;
                
                if ($barang) {
                    $availability = $this->stockService->checkAvailability(
                        $barang->id,
                        $startDate,
                        $endDate,
                        $quantity
                    );
                    
                    $results[] = [
                        'barang_id' => $barang->id,
                        'nama_barang' => $barang->nama,
                        'requested_quantity' => $quantity,
                        'availability' => [
                            'is_available' => $availability['available'],
                            'available_quantity' => $availability['available_quantity'],
                            'database_stock' => $barang->stok
                        ],
                        'message' => $availability['available'] 
                            ? "Tersedia {$availability['available_quantity']} unit" 
                            : ($availability['message'] ?? 'Tidak tersedia')
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ],
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}