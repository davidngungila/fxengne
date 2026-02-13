<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_traders' => User::where('role', 'trader')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'active_traders' => User::where('role', 'trader')->where('email_verified_at', '!=', null)->count(),
        ];

        return view('admin.index', compact('stats'));
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }
        
        // 2FA filter
        if ($request->has('2fa') && $request->input('2fa') !== '') {
            if ($request->input('2fa') === 'enabled') {
                $query->whereNotNull('two_factor_secret')
                      ->whereNotNull('two_factor_confirmed_at');
            } else {
                $query->where(function($q) {
                    $q->whereNull('two_factor_secret')
                      ->orWhereNull('two_factor_confirmed_at');
                });
            }
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $users = $query->withCount(['trades', 'mlModels'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Get user statistics
        $userStats = [];
        foreach ($users as $user) {
            $trades = \App\Models\Trade::where('user_id', $user->id)->get();
            $openTrades = $trades->where('state', 'OPEN');
            $closedTrades = $trades->where('state', 'CLOSED');
            
            $userStats[$user->id] = [
                'total_trades' => $trades->count(),
                'open_trades' => $openTrades->count(),
                'closed_trades' => $closedTrades->count(),
                'total_profit' => $closedTrades->sum('realized_pl'),
                'unrealized_pl' => $openTrades->sum('unrealized_pl'),
                'win_rate' => $closedTrades->count() > 0 
                    ? ($closedTrades->where('realized_pl', '>', 0)->count() / $closedTrades->count()) * 100 
                    : 0,
                'ml_models_count' => $user->ml_models_count ?? 0,
            ];
        }
        
        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_traders' => User::where('role', 'trader')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            '2fa_enabled' => User::whereNotNull('two_factor_secret')
                ->whereNotNull('two_factor_confirmed_at')->count(),
            'active_traders' => User::where('role', 'trader')
                ->whereNotNull('email_verified_at')
                ->whereHas('trades')
                ->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
        
        return view('admin.users', compact('users', 'userStats', 'stats'));
    }
    
    /**
     * Get user details
     */
    public function getUserDetails($id)
    {
        $user = User::with(['trades', 'mlModels'])->findOrFail($id);
        
        $trades = $user->trades;
        $openTrades = $trades->where('state', 'OPEN');
        $closedTrades = $trades->where('state', 'CLOSED');
        
        $details = [
            'user' => $user,
            'trading_stats' => [
                'total_trades' => $trades->count(),
                'open_trades' => $openTrades->count(),
                'closed_trades' => $closedTrades->count(),
                'total_profit' => $closedTrades->sum('realized_pl'),
                'unrealized_pl' => $openTrades->sum('unrealized_pl'),
                'win_rate' => $closedTrades->count() > 0 
                    ? ($closedTrades->where('realized_pl', '>', 0)->count() / $closedTrades->count()) * 100 
                    : 0,
                'average_profit' => $closedTrades->count() > 0 
                    ? $closedTrades->avg('realized_pl') 
                    : 0,
                'largest_win' => $closedTrades->max('realized_pl') ?? 0,
                'largest_loss' => $closedTrades->min('realized_pl') ?? 0,
            ],
            'ml_models' => [
                'total' => $user->mlModels()->count(),
                'active' => $user->mlModels()->where('is_active', true)->count(),
                'trained' => $user->mlModels()->where('status', 'trained')->count(),
            ],
            'recent_trades' => $trades->sortByDesc('opened_at')->take(10)->values(),
        ];
        
        return response()->json($details);
    }
    
    /**
     * Export users to CSV
     */
    public function exportUsers(Request $request)
    {
        $query = User::query();
        
        // Apply same filters as users() method
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->get();
        
        $filename = 'users_export_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Email Verified', '2FA Enabled', 
                'Total Trades', 'Open Trades', 'Total Profit', 'Win Rate', 
                'ML Models', 'Created At', 'Last Login'
            ]);
            
            // Data
            foreach ($users as $user) {
                $trades = $user->trades;
                $closedTrades = $trades->where('state', 'CLOSED');
                $winRate = $closedTrades->count() > 0 
                    ? ($closedTrades->where('realized_pl', '>', 0)->count() / $closedTrades->count()) * 100 
                    : 0;
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->hasTwoFactorEnabled() ? 'Yes' : 'No',
                    $trades->count(),
                    $trades->where('state', 'OPEN')->count(),
                    $closedTrades->sum('realized_pl'),
                    number_format($winRate, 2) . '%',
                    $user->mlModels()->count(),
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,trader'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully'
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
