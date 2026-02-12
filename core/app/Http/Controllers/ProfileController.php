<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Reservation;
use App\Services\ImageOptimizationService;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }


    public function update(\App\Http\Requests\UpdateProfileRequest $request)
    {
        $user = Auth::user();
        
        $validated = $request->validated();

        // Handle password update separately
        if ($request->filled('current_password') && $request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        \Log::info('Profile Update Data:', $validated);
        
        $result = $user->update($validated);
        
        \Log::info('Profile Update Result:', ['success' => $result]);

        return back()->with('success', 'Profil bilgileriniz güncellendi.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();
        
        // Use FileUploadService for secure and optimized upload
        $paths = \App\Services\FileUploadService::uploadImage($request->file('photo'), 'profile_photos', true);

        // Delete old photo (FileUploadService handles thumbnail deletion if applicable)
        if ($user->profile_photo_path) {
            \App\Services\FileUploadService::delete($user->profile_photo_path);
        }

        $user->update([
            'profile_photo_path' => $paths['original']
        ]);

        return back()->with('success', 'Profil fotoğrafı güncellendi.');
    }

    public function reservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('status', '!=', 'pending_payment')
            ->with(['business', 'menus'])
            ->orderBy('start_time', 'desc')
            ->get();

        return view('profile.reservations', compact('reservations'));
    }

    public function favorites()
    {
        $favorites = Auth::user()->favorites()->with(['business.businessCategory'])->get();
        return view('profile.favorites', compact('favorites'));
    }

    public function support(Request $request)
    {
        $selectedId = $request->query('id');
        
        $messages = \App\Models\ContactMessage::where('user_id', Auth::id())
            ->with(['replies' => function($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->latest()
            ->paginate(10);

        $selectedMessage = null;
        if ($selectedId) {
            $selectedMessage = $messages->firstWhere('id', $selectedId);
            if (!$selectedMessage) {
                // If not in current page, fetch it directly to ensure we have it
                 $selectedMessage = \App\Models\ContactMessage::where('user_id', Auth::id())
                    ->where('id', $selectedId)
                    ->with(['replies' => function($q) {
                        $q->orderBy('created_at', 'asc');
                    }])
                    ->first();
            }
        }

        return view('profile.support', compact('messages', 'selectedMessage'));
    }

    public function replyToSupport(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $contactMessage = \App\Models\ContactMessage::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($contactMessage->status == 'closed') {
            return back()->with('error', 'Bu destek talebi kapatılmıştır, yeni yanıt eklenemez.');
        }

        \App\Models\SupportReply::create([
            'contact_message_id' => $contactMessage->id,
            'message' => $request->message,
            'is_admin' => false,
            'is_read' => false
        ]);

        // Re-open ticket if it was pending (optional logic, but keeps it active)
        $contactMessage->touch(); // Updates updated_at to bring it to top

        return back()->with('success', 'Yanıtınız gönderildi.');
    }

    public function referrals()
    {
        $user = Auth::user();

        // Fix for existing users without referral code
        if (empty($user->referral_code)) {
            $user->referral_code = strtoupper(\Illuminate\Support\Str::random(10));
            $user->save();
        }
        
        // Count of people referred by this user
        $referralCount = \App\Models\User::where('referred_by_id', $user->id)->count();

        // Calculate total earnings from referrals using ActivityLog
        $totalEarnings = \App\Models\ActivityLog::where('user_id', $user->id)
            ->where('action_type', 'referral_reward')
            ->get()
            ->sum(function($log) {
                return $log->metadata['reward'] ?? 0;
            });

        $referredUsers = \App\Models\User::where('referred_by_id', $user->id)
            ->select('name', 'created_at', 'avatar', 'profile_photo_path')
            ->latest()
            ->paginate(10);

        return view('profile.referrals', compact('user', 'referralCount', 'totalEarnings', 'referredUsers'));
    }
}
