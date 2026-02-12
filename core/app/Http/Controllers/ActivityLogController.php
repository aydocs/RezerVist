<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by category (maps to action_type prefixes)
        if ($request->filled('category')) {
            $category = $request->category;

            switch ($category) {
                case 'auth':
                    $query->whereIn('action_type', ['login', 'logout', 'failed_login', 'user_created', 'user_updated']);
                    break;
                case 'payment':
                    $query->where('action_type', 'like', 'payment_%');
                    break;
                case 'reservation':
                    $query->where('action_type', 'like', 'reservation_%');
                    break;
                case 'business':
                    $query->where('action_type', 'like', 'business_%');
                    break;
                case 'system':
                    $query->where(function ($q) {
                        $q->where('action_type', 'like', 'system_%')
                            ->orWhere('action_type', 'like', 'setting_%')
                            ->orWhere('action_type', 'like', 'contact_%')
                            ->orWhere('action_type', 'like', 'support_%');
                    });
                    break;
            }
        }

        // Filter by specific action type
        if ($request->filled('type')) {
            $query->where('action_type', $request->type);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Search by IP, user name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        $logs = $query->paginate(50)->appends($request->all());

        // Stats
        $totalLogs = ActivityLog::count();
        $loginAttempts = ActivityLog::where('action_type', 'login')->count();
        $failedLogins = ActivityLog::where('action_type', 'failed_login')->count();
        $logouts = ActivityLog::where('action_type', 'logout')->count();

        return view('admin.activity-logs.index', compact('logs', 'totalLogs', 'loginAttempts', 'failedLogins', 'logouts'));
    }
}
