<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessApplication;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // System Commission is now 5% per business
        $totalRevenueQuery = \App\Models\Reservation::where('reservations.status', '!=', 'cancelled')
            ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
            ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue');

        $totalRevenue = $totalRevenueQuery->first()->revenue ?? 0;
        $totalVolume = \App\Models\Reservation::where('status', '!=', 'cancelled')->sum('price');

        $stats = [
            'pending_applications' => BusinessApplication::where('status', 'pending')->count(),
            'active_businesses' => Business::where('is_active', true)->count(),
            'inactive_businesses' => Business::where('is_active', false)->count(),
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'total_reservations' => \App\Models\Reservation::count(),
            'total_revenue' => $totalRevenue,
            'today_revenue' => \App\Models\Reservation::where('reservations.status', '!=', 'cancelled')
                ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
                ->whereDate('reservations.created_at', now())
                ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue')
                ->first()->revenue ?? 0,
            'total_volume' => $totalVolume,
            'pending_reviews' => \App\Models\Review::where('status', 'pending')->count(),
        ];

        // Weekly Revenue Data (Last 7 Days)
        $revenueData = [];
        $reservationData = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('D');

            $dailyRevenue = \App\Models\Reservation::where('reservations.status', '!=', 'cancelled')
                ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
                ->whereDate('reservations.created_at', $date->format('Y-m-d'))
                ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue')
                ->first()->revenue ?? 0;

            $revenueData[] = (float) $dailyRevenue;
            $reservationData[] = \App\Models\Reservation::whereDate('created_at', $date->format('Y-m-d'))
                ->count();
        }

        // --- Sophisticated Growth Metrics (Last 7 Days vs Previous 7 Days) ---

        // 1. Revenue Growth
        $currentWeekRevenue = \App\Models\Reservation::where('reservations.status', '!=', 'cancelled')
            ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
            ->where('reservations.created_at', '>=', now()->subDays(7))
            ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue')
            ->first()->revenue ?? 0;

        $previousWeekRevenue = \App\Models\Reservation::where('reservations.status', '!=', 'cancelled')
            ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
            ->whereBetween('reservations.created_at', [now()->subDays(14), now()->subDays(7)])
            ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue')
            ->first()->revenue ?? 0;

        $revenueGrowth = $previousWeekRevenue > 0 ? (($currentWeekRevenue - $previousWeekRevenue) / $previousWeekRevenue) * 100 : ($currentWeekRevenue > 0 ? 100 : 0);

        // 2. Reservation Growth
        $currentWeekReservations = \App\Models\Reservation::where('created_at', '>=', now()->subDays(7))->count();
        $previousWeekReservations = \App\Models\Reservation::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->count();
        $reservationGrowth = $previousWeekReservations > 0 ? (($currentWeekReservations - $previousWeekReservations) / $previousWeekReservations) * 100 : ($currentWeekReservations > 0 ? 100 : 0);

        // 3. User Growth
        $currentWeekUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        $previousWeekUsers = User::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->count();
        $userGrowth = $previousWeekUsers > 0 ? (($currentWeekUsers - $previousWeekUsers) / $previousWeekUsers) * 100 : ($currentWeekUsers > 0 ? 100 : 0);

        // 4. Pending Applications
        $newApplicationsThisWeek = BusinessApplication::where('created_at', '>=', now()->subDays(7))->count();

        $growth = [
            'revenue' => round($revenueGrowth, 1),
            'reservations' => round($reservationGrowth, 1),
            'users' => round($userGrowth, 1),
            'new_apps' => $newApplicationsThisWeek,
        ];

        // Recent Activity Feed
        $recentActivities = collect();

        // 1. System Logs (The Main Source of Truth now)
        \App\Models\ActivityLog::with('user')
            ->latest()
            ->take(15) // Fetch enough to cover
            ->get()
            ->each(function ($log) use ($recentActivities) {

                $icon = match ($log->action_type) {
                    'login', 'logout' => 'login',
                    'user_created', 'user_updated', 'user_deleted' => 'user',
                    'reservation_created', 'reservation_updated', 'reservation_cancelled' => 'calendar',
                    'payment_success', 'payment_failed', 'wallet_topup', 'wallet_payment' => 'credit-card',
                    'business_application' => 'document-text',
                    default => 'clipboard-list'
                };

                $color = match ($log->action_type) {
                    'login', 'logout' => 'gray',
                    'user_created', 'payment_success', 'wallet_topup' => 'green',
                    'user_deleted', 'payment_failed', 'reservation_cancelled' => 'red',
                    'reservation_created', 'reservation_updated' => 'blue',
                    'business_application' => 'amber',
                    default => 'indigo'
                };

                $typeLabel = match ($log->action_type) {
                    'login' => 'Oturum Açma',
                    'logout' => 'Oturum Kapatma',
                    'user_created' => 'Yeni Kayıt',
                    'reservation_created' => 'Rezervasyon',
                    'payment_success' => 'Ödeme Başarılı',
                    'wallet_topup' => 'Cüzdan Yükleme',
                    'wallet_topup_initiated' => 'Ödeme Başlatıldı',
                    default => 'Sistem Logu'
                };

                $userName = $log->user ? $log->user->name : 'Sistem';

                $recentActivities->push([
                    'type' => $typeLabel,
                    'icon' => $icon,
                    'color' => $color,
                    'message' => $log->description,
                    'time' => $log->created_at->diffForHumans(),
                    'created_at' => $log->created_at,
                    'details' => $userName,
                ]);
            });

        // 2. Legacy Fallbacks (If ActivityLog is empty, we still want to show something from raw tables)
        // Only add if we don't have enough logs, or just strictly rely on Logs now that we implemented them.
        // But to be safe and "rich", let's keep specific meaningful raw events if they aren't logged yet (e.g. old data)
        // However, user said "let everything fall here", implying the Log flow is what they check.
        // Let's keep the manual queries but deduplicate if feasible, OR just prioritize the Logs if we trust the instrumentation.
        // Given I just instrumented everything, Logs are better. But let's keep specific "New User" from DB just in case.

        if ($recentActivities->isEmpty()) {
            // Recent Reservations Fallback
            \App\Models\Reservation::with(['user', 'business'])
                ->latest()
                ->take(5)
                ->get()
                ->each(function ($reservation) use ($recentActivities) {
                    $recentActivities->push([
                        'type' => 'Rezervasyon',
                        'icon' => 'calendar',
                        'color' => 'blue',
                        'message' => $reservation->user->name.' → '.$reservation->business->name,
                        'time' => $reservation->created_at->diffForHumans(),
                        'created_at' => $reservation->created_at,
                    ]);
                });
        }

        // Sort by created_at
        $recentActivities = $recentActivities->sortByDesc('created_at')->values()->take(10);
        // Top Businesses by Reservations
        $topBusinesses = Business::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();

        // Category Distribution & Revenue
        $categoryStats = \App\Models\Category::withCount(['businesses', 'businesses as reservations_count' => function ($query) {
            $query->join('reservations', 'businesses.id', '=', 'reservations.business_id')
                ->where('reservations.status', '!=', 'cancelled');
        }])->get();

        return view('admin.dashboard', compact(
            'stats',
            'revenueData',
            'reservationData',
            'dates',
            'growth',
            'recentActivities',
            'topBusinesses',
            'categoryStats'
        ));
    }

    public function applications(Request $request)
    {
        $query = \App\Models\BusinessApplication::with(['user', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        // Get counts for each status
        $totalApplications = \App\Models\BusinessApplication::count();
        $pendingCount = \App\Models\BusinessApplication::where('status', 'pending')->count();
        $approvedCount = \App\Models\BusinessApplication::where('status', 'approved')->count();
        $rejectedCount = \App\Models\BusinessApplication::where('status', 'rejected')->count();

        return view('admin.applications.index', compact('applications', 'totalApplications', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function showApplication($id)
    {
        $application = BusinessApplication::with('category')->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    public function updateApplication(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,under_review', // approved, rejected, under_review
            'admin_note' => 'nullable|string',
        ]);

        $application = BusinessApplication::findOrFail($id);

        $application->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Find or create user to notify (using application email)
        $user = \App\Models\User::where('email', $application->email)->first();
        $tempPassword = null;

        // Determine password source: Custom input or Random
        $passwordToSet = $request->custom_password ?? \Illuminate\Support\Str::random(10);

        if (! $user && $request->status === 'approved') {
            // Check if phone already exists
            if (\App\Models\User::where('phone', $application->phone)->exists()) {
                return redirect()->back()->with('error', 'Bu telefon numarası ('.$application->phone.') sistemde kayıtlı başka bir kullanıcıya ait. Lütfen kullanıcının bilgilerini kontrol edin veya manuel olarak düzenleyin.');
            }

            // Auto-create user for the approved business
            $tempPassword = $passwordToSet;
            $user = \App\Models\User::create([
                'name' => $application->business_name.' Yetkilisi',
                'email' => $application->email,
                'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
                'plain_password' => $tempPassword,
                'phone' => $application->phone,
                'role' => 'business',
                'email_verified_at' => now(), // Auto verify
            ]);
        } elseif ($user && $request->status === 'approved' && $request->filled('custom_password')) {
            // If user exists BUT admin explicitly set a password, update it
            $tempPassword = $passwordToSet;
            $user->password = \Illuminate\Support\Facades\Hash::make($tempPassword);
            $user->plain_password = $tempPassword;
            $user->save();
        }

        // Notify the user about the status change
        // We find the user by application user_id for reliability
        $applicant = \App\Models\User::find($application->user_id);

        if ($request->status === 'approved' && $applicant) {
            // 1. Upgrade role to business
            $applicant->update(['role' => 'business']);

            // 2. Create Business Profile (Passive/Draft mode)
            $business = \App\Models\Business::updateOrCreate(
                ['owner_id' => $applicant->id],
                [
                    'name' => $application->business_name,
                    'slug' => \Illuminate\Support\Str::slug($application->business_name),
                    'category_id' => $application->category_id,
                    'address' => $application->address,
                    'phone' => $application->phone,
                    'email' => $application->email,
                    'description' => $application->description,
                    'is_verified' => true,
                    'verified_at' => now(),
                    'is_active' => false, // Passive until setup is complete
                    'latitude' => 41.0082, // Default coords
                    'longitude' => 28.9784,
                ]
            );

            // 3. Sync Tags (Features)
            if ($application->tags && is_array($application->tags)) {
                $business->tags()->sync($application->tags);
            }

            // 4. Link user to business
            $applicant->update(['business_id' => $business->id]);
        }

        $targetUser = $applicant ?? $user;

        if ($targetUser) {
            $targetUser->notify(new \App\Notifications\BusinessApplicationStatusNotification(
                $application->status,
                $application->business_name,
                $application->admin_note,
                $tempPassword
            ));
        }

        return redirect()->route('admin.applications.show', $id)->with('success', 'Başvuru onaylandı. Kullanıcı işletme rolüne yükseltildi ve taslak işletme profili oluşturuldu.');
    }

    public function viewDocument($id, $field)
    {
        $application = BusinessApplication::findOrFail($id);

        // Security check: Only admins can view documents
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bu belgeyi görüntülemek için yetkiniz bulunmamaktadır.');
        }

        // Allowed fields for security
        $allowedFields = [
            'trade_registry_document',
            'tax_document',
            'license_document',
            'id_document',
            'bank_document',
        ];

        if (! in_array($field, $allowedFields)) {
            abort(403, 'Geçersiz belge alanı.');
        }

        $path = $application->$field;

        if (! $path) {
            abort(404, 'Belge yolu bulunamadı.');
        }

        // Normalize path separators for Storage facade (always forward slashes)
        $path = str_replace(['\\', '/'], '/', $path);

        // Debug: Check local disk (private)
        if (\Storage::disk('local')->exists($path)) {
            return \Storage::disk('local')->response($path);
        }

        // Check public disk for legacy files
        if (\Storage::disk('public')->exists($path)) {
            return \Storage::disk('public')->response($path);
        }

        \Log::error("Document not found on any disk: {$path} (App ID: {$id})");
        abort(404, 'Belge fiziksel olarak bulunamadı.');
    }

    public function handleNotification($user, $application, $request, $tempPassword)
    {
        // Notification Logic
        if ($user) {
            $user->notify(new \App\Notifications\BusinessApplicationStatusNotification($request->status, $application->business_name, $request->admin_note, $tempPassword));
        } else {
            // Fallback for rejection of non-existing user
            \Illuminate\Support\Facades\Notification::route('mail', $application->email)
                ->notify(new \App\Notifications\BusinessApplicationStatusNotification($request->status, $application->business_name, $request->admin_note));
        }

        // Business Creation Logic if Approved
        if ($request->status === 'approved') {
            // Check if business already exists for this email/app to prevent duplicates
            // Or just create it.

            $business = Business::create([
                'name' => $application->business_name,
                'category_id' => $application->category_id,
                'description' => $application->description,
                'address' => $application->address,
                'phone' => $application->phone,
                // 'email' => $application->email, // Business table doesn't have email usually, user has.
                'is_active' => true,
                'owner_id' => $user->id, // User is guaranteed to exist now (created above)
                // Add lat/lng if available in application or default to 0
                'latitude' => 41.0082,
                'longitude' => 28.9784,
                'rating' => 0,
            ]);

            // If user exists (always true here), update role
            if ($user && $user->role !== 'business') {
                $user->role = 'business';
                $user->save();
            }

            // Seed Menu for this new business immediately
            \App\Models\Menu::factory()->count(10)->create(['business_id' => $business->id]);
        }

        return redirect()->route('admin.applications.index')
            ->with('success', 'Başvuru durumu güncellendi: '.ucfirst($request->status));
    }

    public function searchUsers(Request $request)
    {
        $searchQuery = $request->get('q', '');
        $role = $request->get('role', '');

        if (strlen($searchQuery) < 1) {
            return response()->json([]);
        }

        $query = \App\Models\User::where('role', '!=', 'admin');

        if (! empty($role)) {
            $query->where('role', $role);
        }

        $users = $query->where(function ($q) use ($searchQuery) {
            $q->where('name', 'like', '%'.$searchQuery.'%')
                ->orWhere('email', 'like', '%'.$searchQuery.'%');
        })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'role']);

        return response()->json($users);
    }

    public function users(Request $request)
    {
        $query = \App\Models\User::where('role', '!=', 'admin');

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search Filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Load relationships for stats
        $users = $query->withCount(['reservations', 'businesses'])
            ->orderBy($request->filled('search') ? 'name' : 'created_at', $request->filled('search') ? 'asc' : 'desc')
            ->paginate(15)
            ->appends($request->all());

        // Stats for filters
        $totalUsers = \App\Models\User::where('role', '!=', 'admin')->count();
        $businessUsersCount = \App\Models\User::where('role', 'business')->count();
        $customerUsersCount = \App\Models\User::where('role', 'customer')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'businessUsersCount', 'customerUsersCount'));
    }

    // User Management
    public function editUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,business,admin',
            'password' => 'nullable|min:6',
        ]);

        // Update basic fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        // Log the activity
        \App\Models\ActivityLog::logActivity(
            'user_updated',
            "Kullanıcı güncellendi: {$user->name} ({$user->email})",
            ['user_id' => $user->id, 'updated_by' => auth()->id()],
            auth()->id()
        );

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla güncellendi!');
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        // Log the activity
        \App\Models\ActivityLog::logActivity(
            'user_deleted',
            "Kullanıcı silindi: {$userName}",
            ['user_id' => $id, 'deleted_by' => auth()->id()],
            auth()->id()
        );

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla silindi!');
    }

    // Business Management Link (Since existing BusinessController manages businesses, we might just redirect or add admin specific override here)
    // For now, let's assume Admin uses the same Vendor forms

    private function getReportsData(Request $request)
    {
        // Get date range from request or use defaults
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Basic query builder for reuse
        $detailsQuery = Reservation::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'cancelled');

        // 1. Total Volume (Gross)
        $totalVolume = (clone $detailsQuery)->sum('price') ?? 0;

        // 2. Net Income (5% Commission)
        $netIncome = (clone $detailsQuery)
            ->join('businesses', 'reservations.business_id', '=', 'businesses.id')
            ->selectRaw('SUM(COALESCE(reservations.total_amount, reservations.price, 0) * COALESCE(businesses.commission_rate, 5) / 100) as revenue')
            ->first()->revenue ?? 0;

        // Count
        $totalReservations = (clone $detailsQuery)->count();

        // Financial Summary
        $totalTopups = WalletTransaction::where('type', 'topup')
            ->where('status', 'success')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('amount');

        $totalWalletPayments = WalletTransaction::where('type', 'payment')
            ->where('status', 'success')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('amount');

        $totalRefunds = \App\Models\RefundRequest::where('status', 'refunded')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('amount') ?? 0;

        // Get detailed reservations for the table
        $reservations = Reservation::with(['user', 'business'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'res_page')
            ->appends($request->all());

        // Get wallet transactions for the "Financial" tab
        $walletTransactions = WalletTransaction::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'fin_page')
            ->appends($request->all());

        return compact(
            'startDate',
            'endDate',
            'totalVolume',
            'netIncome',
            'totalReservations',
            'totalTopups',
            'totalWalletPayments',
            'totalRefunds',
            'reservations',
            'walletTransactions'
        );
    }

    public function reports(Request $request)
    {
        $data = $this->getReportsData($request);

        return view('admin.reports.index', $data);
    }

    // Unified Settings Management
    public function settings()
    {
        // Get all settings and group them by 'group'
        $settingsRaw = \App\Models\Setting::all();
        $settings = $settingsRaw->groupBy('group');

        // Stats for settings overview
        $totalSettings = $settingsRaw->count();
        $lastUpdate = $settingsRaw->max('updated_at');

        // Prepare detailed timezone data
        $timezones = [];
        foreach (\DateTimeZone::listIdentifiers() as $tz) {
            $dateTime = new \DateTime('now', new \DateTimeZone($tz));
            $offset = $dateTime->getOffset();
            $offsetPrefix = $offset >= 0 ? '+' : '-';
            $offsetFormatted = 'UTC '.$offsetPrefix.sprintf('%02d:%02d', abs($offset) / 3600, (abs($offset) % 3600) / 60);

            // Smarter name display: Europe/Istanbul -> Istanbul (Europe)
            $parts = explode('/', $tz);
            $cityName = end($parts);
            $cityName = str_replace('_', ' ', $cityName);
            $continentName = count($parts) > 1 ? $parts[0] : '';
            $displayName = $continentName ? $cityName.' ('.$continentName.')' : $cityName;

            $timezones[] = [
                'id' => $tz,
                'name' => $displayName,
                'city' => $cityName,
                'continent' => $continentName,
                'offset' => $offsetFormatted,
                'current_time' => $dateTime->format('H:i'),
            ];
        }

        return view('admin.settings.index', compact('settings', 'totalSettings', 'lastUpdate', 'timezones'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token', '_method', 'active_tab');
        $activeTab = $request->input('active_tab', 'general');

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                // Handling for checkboxes
                if (str_ends_with($key, '_present')) {
                    $original_key = str_replace('_present', '', $key);
                    if (! isset($data[$original_key])) {
                        $value = '0';
                        $key = $original_key;
                    } else {
                        continue;
                    }
                }

                $value = is_null($value) ? '' : (string) $value;
                $group = explode('_', $key)[0];
                if (! in_array($group, ['general', 'seo', 'contact', 'social', 'system', 'mail'])) {
                    $group = 'general';
                }

                $oldValue = Setting::where('key', $key)->value('value') ?? '';

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => $group]
                );

                if ($oldValue !== $value) {
                    \App\Models\ActivityLog::logSettingChange($key, $oldValue, $value);
                }

                \Illuminate\Support\Facades\Cache::forget('settings.'.$key);
            }

            \Illuminate\Support\Facades\DB::commit();
            \Illuminate\Support\Facades\Cache::forget('global_settings');

            \App\Models\ActivityLog::logActivity(
                'settings_updated_batch',
                'Sistem ayarları toplu olarak güncellendi.',
                ['updated_keys' => array_keys($data)]
            );

            return redirect()->back()
                ->with('success', 'Ayarlar başarıyla güncellendi.')
                ->with('active_tab', $activeTab);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return redirect()->back()
                ->with('error', 'Ayarlar güncellenirken bir hata oluştu: '.$e->getMessage())
                ->withInput();
        }
    }

    public function platformActivity()
    {
        $activities = collect();
        // Recent New Users (Last 50)
        \App\Models\User::where('role', '!=', 'admin')
            ->latest()
            ->take(50)
            ->get()
            ->each(function ($user) use ($activities) {
                $roleText = match ($user->role) {
                    'business' => 'İşletme Sahibi', // ## Faz 6: İşletme Sahibi (Vendor) Özellikleri [/]
                    // - [ ] İşletme analitikleri (Dashboard & Grafikler) [/]
                    // - [ ] Personel yönetim sistemi (CRUD & Atama) [ ]
                    // - [ ] Kapasite planlama & Masa/Yer Yönetimi [ ]
                    // - [ ] Müşteri veritabanı (CRM) [ ]
                    'customer' => 'Müşteri',
                    'admin' => 'Yönetici',
                    default => 'Kullanıcı'
                };

                $activities->push([
                    'type' => 'Yeni Kayıt',
                    'icon' => 'user',
                    'color' => 'green',
                    'message' => $user->name.' olarak katıldı ('.$roleText.')',
                    'time' => $user->created_at->diffForHumans(),
                    'created_at' => $user->created_at,
                    'details' => $user->email,
                ]);
            });

        // Recent Reservations (Last 50)
        \App\Models\Reservation::with(['user', 'business'])
            ->latest()
            ->take(50)
            ->get()
            ->each(function ($reservation) use ($activities) {
                $statusText = match ($reservation->status) {
                    'pending' => 'onay bekliyor',
                    'confirmed' => 'onaylandı',
                    'completed' => 'tamamlandı',
                    'cancelled' => 'iptal edildi',
                    default => $reservation->status
                };

                $activities->push([
                    'type' => 'Rezervasyon',
                    'icon' => 'calendar',
                    'color' => 'blue',
                    'message' => $reservation->user->name.', '.$reservation->business->name.' için rezervasyon yaptı',
                    'time' => $reservation->created_at->diffForHumans(),
                    'created_at' => $reservation->created_at,
                    'details' => ($reservation->date ? \Carbon\Carbon::parse($reservation->date)->format('d.m.Y') : 'Tarih Belirtilmedi').' - '.$reservation->guest_count.' Kişi',
                ]);
            });

        // Recent Applications (Last 50)
        \App\Models\BusinessApplication::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->each(function ($app) use ($activities) {
                $activities->push([
                    'type' => 'Başvuru',
                    'icon' => 'document-text',
                    'color' => 'amber',
                    'message' => $app->user->name.' işletme başvurusu yaptı',
                    'time' => $app->created_at->diffForHumans(),
                    'created_at' => $app->created_at,
                    'details' => $app->business_name,
                ]);
            });

        // System Logs from ActivityLog Table (Last 100)
        // This captures everything else: Settings, Updates, Logins, etc.
        \App\Models\ActivityLog::with('user')
            ->whereNotIn('action_type', ['user_created', 'reservation_created', 'business_application']) // Avoid duplicates with manual queries above if logged
            ->latest()
            ->take(100)
            ->get()
            ->each(function ($log) use ($activities) {

                $icon = match ($log->action_type) {
                    'login', 'logout' => 'login',
                    'user_updated', 'user_deleted' => 'user',
                    'setting_change', 'settings_updated_batch' => 'cog',
                    'system_alert' => 'exclamation',
                    'reservation_updated', 'reservation_cancelled', 'reservation_confirmed' => 'calendar',
                    'support_ticket_created', 'contact_form_mail' => 'chat',
                    default => 'clipboard-list'
                };

                $color = match ($log->action_type) {
                    'login', 'logout', 'payment_success', 'payment_failed', 'wallet_topup', 'wallet_payment' => 'indigo', // System/Wallet = Purple/Indigo
                    'user_created', 'user_updated' => 'emerald', // New User = Green
                    'user_deleted', 'reservation_cancelled', 'payment_failed_error' => 'red', // Errors = Red
                    'reservation_created', 'reservation_updated', 'reservation_confirmed' => 'blue', // Reservation = Blue
                    'business_application' => 'amber', // Application = Amber
                    default => 'indigo'
                };

                $typeLabel = match ($log->action_type) {
                    'login' => 'Oturum Açma',
                    'logout' => 'Oturum Kapatma',
                    'user_created' => 'Yeni Kayıt',
                    'user_updated' => 'Profil Güncelleme',
                    'reservation_created', 'reservation_updated' => 'Rezervasyon',
                    'reservation_cancelled' => 'İptal Edildi',
                    'payment_success' => 'Ödeme Başarılı',
                    'wallet_topup' => 'Sistem Logu', // Matches "Cuzdan islemi" screenshot showing "SISTEM LOGU"
                    'wallet_topup_initiated' => 'Sistem Logu',
                    'business_application' => 'Başvuru',
                    default => 'Sistem Logu'
                };

                $userName = $log->user ? $log->user->name : 'Sistem';

                $activities->push([
                    'type' => $typeLabel,
                    'icon' => $icon,
                    'color' => $color,
                    'message' => $log->description,
                    'time' => $log->created_at->diffForHumans(),
                    'created_at' => $log->created_at,
                    'details' => $userName.' tarafından',
                    'metadata' => $log->metadata, // Pass metadata
                    'raw_data' => $log,
                ]);
            });

        // Sort by created_at desc
        $sortedActivities = $activities->sortByDesc('created_at')->values();

        // Manual Pagination
        $perPage = 20;
        $page = request()->get('page', 1);
        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedActivities->forPage($page, $perPage),
            $sortedActivities->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.platform-activity.index', compact('paginatedActivities'));
    }

    public function authActivities()
    {
        return $this->getFilteredActivities('auth', [
            'login', 'logout', 'failed_login', 'registration',
            'auth_created', 'auth_updated', 'auth_deleted',
        ], 'Giriş & Güvenlik Günlükleri', 'Shield');
    }

    public function businessActivities()
    {
        return $this->getFilteredActivities('business', [
            'business_application', 'business_update', 'application_status_update',
            'business_created', 'business_updated', 'business_deleted',
        ], 'İşletme & Başvuru Günlükleri', 'Building');
    }

    public function reservationActivities()
    {
        return $this->getFilteredActivities('reservation', [
            'reservation_created', 'reservation_updated', 'reservation_cancelled', 'reservation_confirmed', 'reservation_completed',
            'reservation_deleted',
        ], 'Rezervasyon Günlükleri', 'Calendar');
    }

    public function systemActivities()
    {
        return $this->getFilteredActivities('system', [
            'setting_change', 'settings_updated_batch', 'system_alert',
            'system_created', 'system_updated', 'system_deleted',
        ], 'Sistem & Denetim Günlükleri', 'Cog');
    }

    private function getFilteredActivities($category, $types, $title, $icon)
    {
        $query = \App\Models\ActivityLog::with('user')
            ->whereIn('action_type', $types)
            ->latest();

        // Simple date filtering if provided
        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('admin.activities.index', compact('activities', 'title', 'icon', 'category'));
    }

    public function popularBusinesses()
    {
        $businesses = \App\Models\Business::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->paginate(15);

        return view('admin.popular-businesses.index', compact('businesses'));
    }

    public function contactMessages(Request $request)
    {
        $query = \App\Models\ContactMessage::with('user')->latest();

        // Stats Calculation
        $stats = [
            'total' => \App\Models\ContactMessage::count(),
            'pending' => \App\Models\ContactMessage::whereNull('replied_at')->where('status', '!=', 'closed')->count(),
            'today' => \App\Models\ContactMessage::whereDate('created_at', now())->where('status', '!=', 'closed')->count(),
            'closed' => \App\Models\ContactMessage::where('status', 'closed')->count(),
        ];

        // Advanced Filters
        if ($request->status === 'pending') {
            $query->whereNull('replied_at');
        } elseif ($request->status === 'replied') {
            $query->whereNotNull('replied_at')->where('status', '!=', 'closed');
        } elseif ($request->status === 'closed') {
            $query->where('status', 'closed');
        }

        if ($request->priority && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('subject', 'like', '%'.$request->search.'%')
                    ->orWhere('message', 'like', '%'.$request->search.'%');
            });
        }

        $messages = $query->paginate(50);

        $selectedMessage = null;
        $history = collect();

        if ($request->id) {
            $selectedMessage = \App\Models\ContactMessage::with(['replies', 'user' => function ($q) {
                $q->withCount(['reservations', 'businesses']);
            }])->find($request->id);

            if ($selectedMessage) {
                if (! $selectedMessage->is_read) {
                    $selectedMessage->update(['is_read' => true]);
                }

                $history = \App\Models\ContactMessage::where('id', '!=', $selectedMessage->id)
                    ->where(function ($q) use ($selectedMessage) {
                        $q->where('email', $selectedMessage->email);
                        if ($selectedMessage->user_id) {
                            $q->orWhere('user_id', $selectedMessage->user_id);
                        }
                    })
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.contact-messages.index', compact('messages', 'selectedMessage', 'stats', 'history'))->render(),
                'stats' => $stats,
            ]);
        }

        return view('admin.contact-messages.index', compact('messages', 'selectedMessage', 'stats', 'history'));
    }

    public function replyToContactMessage(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:10',
            'admin_notes' => 'nullable|string',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
        ]);

        return \DB::transaction(function () use ($request, $id) {
            $message = \App\Models\ContactMessage::with('user')->findOrFail($id);

            try {
                // 1. Update Database FIRST (Ensure data persistence)
                $messsageUpdated = $message->update([
                    'replied_at' => now(), // Keep track of last reply time
                    'is_read' => true,
                    'status' => 'replied',
                    'admin_notes' => $request->admin_notes ?? $message->admin_notes,
                    'priority' => $request->priority ?? $message->priority,
                    'reply' => $request->reply, // Update legacy field for backward compatibility
                ]);

                // Create Thread Entry
                \App\Models\SupportReply::create([
                    'contact_message_id' => $message->id,
                    'message' => $request->reply,
                    'is_admin' => true,
                    'is_read' => false,
                ]);

                // 2. Send Email (Optional but important)
                try {
                    \Illuminate\Support\Facades\Mail::to($message->email)->send(new \App\Mail\ContactMessageReply($message, $request->reply));
                } catch (\Exception $mailException) {
                    \Log::error('Support reply email failed: '.$mailException->getMessage());
                    // We do NOT stop the process effectively, just log it.
                }

                // 3. Send System Notification
                if ($message->user_id) {
                    $message->user->notify(new \App\Notifications\SystemNotification(
                        'Destek Talebiniz Yanıtlandı',
                        'Açmış olduğunuz "'.$message->subject.'" konulu destek talebiniz yönetici tarafından yanıtlandı.',
                        'chat-alt-2',
                        'support',
                        route('profile.support', ['id' => $message->id])
                    ));
                }

                // 4. Log Activity
                \App\Models\ActivityLog::logActivity(
                    'support_replied',
                    "Destek talebine yanıt verildi: {$message->subject}",
                    ['ticket_id' => $message->id]
                );

                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Yanıt başarıyla gönderildi ve kullanıcı bilgilendirildi.',
                        'replied_at' => $message->replied_at->diffForHumans(),
                        // Partial return to update the chat feed instantly without full reload
                        'reply_html' => '<div class="flex items-start gap-6 flex-row-reverse group">
                            <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center shrink-0 shadow-xl shadow-gray-200 border-4 border-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div class="flex-1 flex flex-col items-end max-w-2xl">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-[10px] text-gray-300">Az önce</span>
                                    <span class="text-[10px] font-black text-indigo-600 tracking-wider uppercase">DESTEK UZMANI</span>
                                </div>
                                <div class="bg-indigo-600 p-7 rounded-[32px] rounded-tr-none shadow-[20px_20px_40px_rgba(79,70,229,0.1)]">
                                    <div class="text-[15px] text-white leading-[1.8] whitespace-pre-wrap font-medium">'.e($request->reply).'</div>
                                </div>
                            </div>
                        </div>',
                    ]);
                }

                return redirect()->back()->with('success', 'Yanıt başarıyla gönderildi.');

            } catch (\Exception $e) {
                \Log::error('Support reply failed: '.$e->getMessage());
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'İşlem sırasında bir hata oluştu.'], 500);
                }

                return redirect()->back()->with('error', 'İşlem sırasında bir hata oluştu.');
            }
        });
    }

    public function closeContactMessage(Request $request, $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);

        \DB::transaction(function () use ($message) {
            $message->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            \App\Models\ActivityLog::logActivity(
                'support_closed',
                "Destek talebi kapatıldı: {$message->subject}",
                ['ticket_id' => $message->id]
            );
        });

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Talep başarıyla kapatıldı.',
                'closed_at' => $message->closed_at->diffForHumans(),
            ]);
        }

        return redirect()->back()->with('success', 'Talep başarıyla kapatıldı.');
    }

    public function updateContactMessageNotes(Request $request, $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);

        $message->update([
            'admin_notes' => $request->admin_notes,
            'priority' => $request->priority,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notlar ve öncelik güncellendi.',
            ]);
        }

        return redirect()->back()->with('success', 'Notlar ve öncelik güncellendi.');
    }

    public function exportActivityLog()
    {
        // Increase memory limit for large logs if needed
        ini_set('memory_limit', '512M');

        $activities = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->get();

        $filename = 'platform_activities_'.date('Y-m-d_H-i').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($activities) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($file, $bom = chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['ID', 'Tarih', 'İşlem Tipi', 'Açıklama', 'Kullanıcı', 'Detaylar']);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->created_at->format('d.m.Y H:i:s'),
                    $activity->action_type,
                    $activity->description,
                    $activity->user ? $activity->user->name : 'Sistem',
                    json_encode($activity->metadata, JSON_UNESCAPED_UNICODE),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportReports(Request $request)
    {
        $data = $this->getReportsData($request);
        $format = $request->get('format', 'pdf');

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.export_pdf', $data);

            return $pdf->download('finansal-rapor-'.now()->format('Y-m-d').'.pdf');
        }

        // CSV Export fallback
        $filename = 'finansal-rapor-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fwrite($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($file, ['ID', 'Tarih', 'İşletme', 'Müşteri', 'Tutar', 'Durum']);

            foreach ($data['reservations'] as $res) {
                fputcsv($file, [
                    $res->id,
                    $res->created_at->format('d.m.Y H:i'),
                    $res->business->name,
                    $res->user->name,
                    $res->total_amount,
                    $res->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function systemHealth()
    {
        $health = [];

        // 1. Database Health
        try {
            \DB::connection()->getPdo();
            $dbStatus = 'Healthy';
            $dbName = \DB::connection()->getDatabaseName();

            // Get DB Size (MySQL/MariaDB specific)
            $dbSize = \DB::select('SELECT SUM(data_length + index_length) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = ?', [$dbName])[0]->size;
            $health['database'] = [
                'status' => 'OK',
                'name' => $dbName,
                'size' => round($dbSize, 2).' MB',
                'connection' => config('database.default'),
            ];
        } catch (\Exception $e) {
            $health['database'] = ['status' => 'Error', 'message' => $e->getMessage()];
        }

        // 2. Server Metrics
        $health['server'] = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ];

        // 3. Storage Health
        $path = storage_path();
        $health['storage'] = [
            'disk_free' => round(disk_free_space($path) / 1024 / 1024 / 1024, 2).' GB',
            'disk_total' => round(disk_total_space($path) / 1024 / 1024 / 1024, 2).' GB',
            'is_writable' => is_writable($path) ? 'OK' : 'ReadOnly',
            'temp_writable' => is_writable(sys_get_temp_dir()) ? 'OK' : 'ReadOnly',
        ];

        // 4. Cache & Queue
        $health['cache'] = [
            'driver' => config('cache.default'),
            'status' => \Cache::store()->getStore() ? 'Active' : 'Error',
        ];

        $health['queue'] = [
            'driver' => config('queue.default'),
            'failed_jobs' => \DB::table('failed_jobs')->count(),
        ];

        // 5. App Env


        
        $health['environment'] = [
            'env' => config('app.env'),
            'debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'url' => config('app.url'),
        ];

        return view('admin.health', compact('health'));
    }
}
