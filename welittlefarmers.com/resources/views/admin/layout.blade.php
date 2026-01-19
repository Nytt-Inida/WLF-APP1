<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - @yield('title', 'Blog Management')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            position: relative;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .admin-header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .logo-area {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo-area h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        /* Payment Badge Styles */
        .payment-badge {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            background: #ff4444;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: bold;
            animation: pulse-badge 2s infinite;
        }
        
        @keyframes pulse-badge {
            0%, 100% {
                transform: translateY(-50%) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(-50%) scale(1.1);
                opacity: 0.8;
            }
        }
        
        .nav-link-payment {
            background: rgba(255, 68, 68, 0.15) !important;
            border-left: 4px solid #ff4444;
        }
        
        .nav-link-payment:hover {
            background: rgba(255, 68, 68, 0.25) !important;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="logo-area">
                    <h4>Admin Panel</h4>
                    <small class="text-white-50">We Little Farmers</small>
                </div>
                <nav class="nav flex-column mt-4">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                        @php
                            $pendingCount = \App\Models\User::where('payment_status', 1)->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="payment-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    
                    @if($pendingCount > 0)
                    <a class="nav-link nav-link-payment" 
                       href="{{ route('admin.dashboard') }}#pending-payments">
                        <i class="fas fa-exclamation-triangle"></i> Pending Payments
                        <span class="payment-badge">{{ $pendingCount }}</span>
                    </a>
                    @endif
                    
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" 
                       href="{{ route('admin.blogs.index') }}">
                        <i class="fas fa-blog"></i> Manage Blogs
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}" 
                       href="{{ route('admin.referrals.settings') }}">
                        <i class="fas fa-gift"></i> Referrals & Discounts
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}" 
                       href="{{ route('admin.certificates.index') }}">
                        <i class="fas fa-certificate"></i> Certificates
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" 
                       href="{{ route('admin.reviews.courses') }}">
                        <i class="fas fa-star"></i> Course Reviews
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}" 
                       href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cogs"></i> Site Settings
                    </a>
                    <a class="nav-link" href="{{ route('blogs.index') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                    <a class="nav-link" href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
                
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10">
                <div class="admin-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@yield('page-title', 'Blog Management')</h5>
                        <div>
                            <span class="text-muted">Welcome, {{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="px-4">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Smooth scroll to pending payments section
        if (window.location.hash === '#pending-payments') {
            setTimeout(() => {
                const element = document.getElementById('pending-payments');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    </script>
    
    @yield('scripts')
</body>
</html>