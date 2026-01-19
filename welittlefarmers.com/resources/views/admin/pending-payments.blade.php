<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Payments - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 16px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .payments-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #34495e;
            color: white;
        }

        th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid #ecf0f1;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .user-email {
            font-size: 13px;
            color: #7f8c8d;
        }

        .course-title {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .course-price {
            font-size: 13px;
            color: #27ae60;
            font-weight: 600;
        }

        .timestamp {
            font-size: 13px;
            color: #7f8c8d;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-verify {
            background: #27ae60;
            color: white;
        }

        .btn-verify:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn-reject {
            background: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3498db;
        }

        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>⏳ Pending Payments</h1>
            <p class="subtitle">Review and verify manual payment submissions</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
        @endif

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $pendingPayments->count() }}</div>
                <div class="stat-label">Pending Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">₹{{ number_format($pendingPayments->sum('course.price'), 2) }}</div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>

        <!-- Search Filter -->
        <div class="search-filter">
            <input type="text" 
                   class="search-input" 
                   id="searchInput" 
                   placeholder="🔍 Search by user name, email, or course...">
        </div>

        <!-- Payments Table -->
        <div class="payments-table">
            @if($pendingPayments->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">✅</div>
                    <h3>All Clear!</h3>
                    <p>No pending payments to review at the moment.</p>
                </div>
            @else
                <table id="paymentsTable">
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Course Details</th>
                            <th>Submitted On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingPayments as $payment)
                        <tr data-search="{{ strtolower($payment->user->name . ' ' . $payment->user->email . ' ' . $payment->course->title) }}">
                            <td>
                                <div class="user-info">
                                    <div class="user-name">{{ $payment->user->name }}</div>
                                    <div class="user-email">{{ $payment->user->email }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="course-title">{{ $payment->course->title }}</div>
                                <div class="course-price">₹{{ number_format($payment->course->price, 2) }}</div>
                            </td>
                            <td>
                                <div class="timestamp">
                                    {{ $payment->created_at->format('d M Y') }}<br>
                                    {{ $payment->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-pending">⏳ Pending</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <form action="{{ route('admin.payment.verify', $payment->id) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-verify"
                                                onclick="return confirm('Verify this payment? User will get access to the course.')">
                                            ✓ Verify
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.payment.reject', $payment->id) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-reject"
                                                onclick="return confirm('Reject this payment? This will remove the record.')">
                                            ✗ Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#paymentsTable tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Auto-refresh every 30 seconds to check for new payments
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>