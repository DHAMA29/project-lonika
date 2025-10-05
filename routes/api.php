<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Stock availability API routes (public for frontend use)
Route::prefix('barang')->group(function () {
    Route::get('/{id}/availability', [StockController::class, 'checkAvailability'])
        ->name('api.barang.availability');
    
    Route::get('/{id}/calendar', [StockController::class, 'getCalendarAvailability'])
        ->name('api.barang.calendar');
    
    // Real-time stock check for multiple products
    Route::post('/batch-availability', [StockController::class, 'batchAvailabilityCheck'])
        ->name('api.barang.batch.availability');
});

// Test stock service directly
Route::get('/test-stock-service/{id}', function ($id) {
    try {
        $barang = App\Models\Barang::findOrFail($id);
        $stockService = app(App\Services\StockAvailabilityService::class);
        
        $startDate = \Carbon\Carbon::parse('2024-01-15');
        $endDate = \Carbon\Carbon::parse('2024-01-16');
        
        $availability = $stockService->checkAvailability(
            $barang->id,
            $startDate,
            $endDate
        );
        
        return response()->json([
            'success' => true,
            'barang' => [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'stok' => $barang->stok
            ],
            'availability' => $availability
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('api.test.stock.service');

// Test barang model directly
Route::get('/test-barang/{id}', function ($id) {
    try {
        $barang = App\Models\Barang::findOrFail($id);
        return response()->json([
            'success' => true,
            'barang' => [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'stok' => $barang->stok
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 404);
    }
})->name('api.test.barang');

// Test route for debugging
Route::get('/debug-test', function () {
    return response()->json([
        'message' => 'Debug test working',
        'timestamp' => now()
    ]);
})->name('api.debug.test');

// Test route for stock system validation
Route::get('/test-stock-api', function () {
    return response()->json([
        'message' => 'Stock API is working',
        'timestamp' => now(),
        'endpoints' => [
            'availability' => '/api/barang/{id}/availability?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD',
            'calendar' => '/api/barang/{id}/calendar?month=YYYY-MM'
        ]
    ]);
})->name('test.stock.api');