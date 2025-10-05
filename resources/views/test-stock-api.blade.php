<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock System API Tester</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .result { margin: 10px 0; padding: 10px; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        button { padding: 8px 16px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
        input, select { padding: 5px; margin: 5px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🧪 Stock Management System API Tester</h1>
    
    <div class="test-section">
        <h2>1. Test Availability Checking</h2>
        <div>
            <label>Barang ID: <input type="number" id="barang_id" value="1" min="1"></label><br>
            <label>Tanggal Pinjam: <input type="date" id="tanggal_pinjam" value="{{ date('Y-m-d', strtotime('+1 day')) }}"></label><br>
            <label>Tanggal Kembali: <input type="date" id="tanggal_kembali" value="{{ date('Y-m-d', strtotime('+3 days')) }}"></label><br>
            <label>Quantity: <input type="number" id="quantity" value="1" min="1"></label><br>
            <button onclick="testAvailability()">Test Availability</button>
        </div>
        <div id="availability-result"></div>
    </div>

    <div class="test-section">
        <h2>2. Test Availability Calendar</h2>
        <div>
            <label>Barang ID: <input type="number" id="calendar_barang_id" value="1" min="1"></label><br>
            <label>Start Date: <input type="date" id="start_date" value="{{ date('Y-m-d') }}"></label><br>
            <label>End Date: <input type="date" id="end_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}"></label><br>
            <button onclick="testCalendar()">Get Calendar</button>
        </div>
        <div id="calendar-result"></div>
    </div>

    <div class="test-section">
        <h2>3. Current Stock Status</h2>
        <button onclick="getCurrentStock()">Get Stock Info</button>
        <div id="stock-result"></div>
    </div>

    <div class="test-section">
        <h2>4. Booking History</h2>
        <button onclick="getBookingHistory()">Get Recent Bookings</button>
        <div id="booking-result"></div>
    </div>

    <div class="test-section">
        <h2>5. Test Overlapping Scenarios</h2>
        <p>This will test various overlapping booking scenarios to verify the system works correctly.</p>
        <button onclick="testOverlappingScenarios()">Run Overlap Tests</button>
        <div id="overlap-result"></div>
    </div>

    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showResult(elementId, type, message, data = null) {
            const element = document.getElementById(elementId);
            let html = `<div class="result ${type}">
                <strong>${type.toUpperCase()}:</strong> ${message}
            </div>`;
            
            if (data) {
                html += `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            }
            
            element.innerHTML = html;
        }

        async function testAvailability() {
            const barangId = document.getElementById('barang_id').value;
            const tanggalPinjam = document.getElementById('tanggal_pinjam').value;
            const tanggalKembali = document.getElementById('tanggal_kembali').value;
            const quantity = document.getElementById('quantity').value;

            try {
                const response = await fetch('/peminjaman/check-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        barang_id: parseInt(barangId),
                        tanggal_pinjam: tanggalPinjam,
                        tanggal_kembali: tanggalKembali,
                        quantity: parseInt(quantity)
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    const available = data.availability.available;
                    const type = available ? 'success' : 'error';
                    const message = available ? 'Product is available!' : 'Product is not available!';
                    showResult('availability-result', type, message, data.availability);
                } else {
                    showResult('availability-result', 'error', 'API Error: ' + data.message);
                }
            } catch (error) {
                showResult('availability-result', 'error', 'Network Error: ' + error.message);
            }
        }

        async function testCalendar() {
            const barangId = document.getElementById('calendar_barang_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            try {
                const response = await fetch(`/peminjaman/availability-calendar?barang_id=${barangId}&start_date=${startDate}&end_date=${endDate}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showResult('calendar-result', 'success', 'Calendar data loaded successfully!', data.calendar);
                } else {
                    showResult('calendar-result', 'error', 'API Error: ' + data.message);
                }
            } catch (error) {
                showResult('calendar-result', 'error', 'Network Error: ' + error.message);
            }
        }

        async function getCurrentStock() {
            try {
                // This would be a custom endpoint to show current stock status
                const response = await fetch('/api/stock-status', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    showResult('stock-result', 'success', 'Stock data loaded!', data);
                } else {
                    showResult('stock-result', 'info', 'Custom stock endpoint not available. Create /api/stock-status route for detailed testing.');
                }
            } catch (error) {
                showResult('stock-result', 'info', 'Custom stock endpoint not available. This is optional.');
            }
        }

        async function getBookingHistory() {
            try {
                const response = await fetch('/api/recent-bookings', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    showResult('booking-result', 'success', 'Booking history loaded!', data);
                } else {
                    showResult('booking-result', 'info', 'Custom booking endpoint not available. Create /api/recent-bookings route for detailed testing.');
                }
            } catch (error) {
                showResult('booking-result', 'info', 'Custom booking endpoint not available. This is optional.');
            }
        }

        async function testOverlappingScenarios() {
            const scenarios = [
                {
                    name: "Scenario 1: Same dates",
                    barang_id: 1,
                    tanggal_pinjam: '{{ date("Y-m-d", strtotime("+5 days")) }}',
                    tanggal_kembali: '{{ date("Y-m-d", strtotime("+7 days")) }}',
                    quantity: 1
                },
                {
                    name: "Scenario 2: Overlapping end",
                    barang_id: 1,
                    tanggal_pinjam: '{{ date("Y-m-d", strtotime("+6 days")) }}',
                    tanggal_kembali: '{{ date("Y-m-d", strtotime("+8 days")) }}',
                    quantity: 1
                },
                {
                    name: "Scenario 3: Different product",
                    barang_id: 2,
                    tanggal_pinjam: '{{ date("Y-m-d", strtotime("+5 days")) }}',
                    tanggal_kembali: '{{ date("Y-m-d", strtotime("+7 days")) }}',
                    quantity: 1
                }
            ];

            let results = [];
            
            for (let scenario of scenarios) {
                try {
                    const response = await fetch('/peminjaman/check-availability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(scenario)
                    });

                    const data = await response.json();
                    results.push({
                        scenario: scenario.name,
                        available: data.success ? data.availability.available : false,
                        message: data.success ? data.availability.message : data.message
                    });
                } catch (error) {
                    results.push({
                        scenario: scenario.name,
                        available: false,
                        message: 'Error: ' + error.message
                    });
                }
            }

            showResult('overlap-result', 'info', 'Overlap test completed!', results);
        }

        // Initialize with current date + 1 day
        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            const dayAfter = new Date();
            dayAfter.setDate(dayAfter.getDate() + 3);
            const dayAfterStr = dayAfter.toISOString().split('T')[0];
            
            document.getElementById('tanggal_pinjam').value = tomorrowStr;
            document.getElementById('tanggal_kembali').value = dayAfterStr;
        });
    </script>
</body>
</html>