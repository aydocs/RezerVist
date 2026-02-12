<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        if (! in_array($provider, ['google', 'apple'])) {
            return redirect()->route('login')->with('error', 'Geçersiz giriş yöntemi.');
        }

        // Request additional scopes for Google People API
        if ($provider === 'google') {
            return Socialite::driver($provider)
                ->scopes([
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/userinfo.profile',
                    'https://www.googleapis.com/auth/user.phonenumbers.read',
                    'https://www.googleapis.com/auth/user.addresses.read',
                ])
                ->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Giriş işlemi iptal edildi veya bir hata oluştu.');
        }

        // DEBUG: Log what Google actually returns
        \Log::info('Google OAuth Response', [
            'id' => $socialUser->getId(),
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
            'raw_user' => $socialUser->user ?? null,
        ]);

        $user = User::where($provider.'_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($user) {
            // Update social ID and avatar if not set
            $updateData = [];

            if (! $user->{$provider.'_id'}) {
                $updateData[$provider.'_id'] = $socialUser->getId();
            }

            // Always update avatar from social provider
            if ($socialUser->getAvatar()) {
                $updateData['avatar'] = $socialUser->getAvatar();
            }

            if (! empty($updateData)) {
                $user->update($updateData);
            }

            Auth::login($user);
        } else {
            // Create new user with all available data
            $userData = [
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Apple Kullanıcısı',
                'email' => $socialUser->getEmail(),
                $provider.'_id' => $socialUser->getId(),
                'password' => bcrypt(Str::random(24)),
                'role' => 'customer',
            ];

            // Add avatar if available
            if ($socialUser->getAvatar()) {
                $userData['avatar'] = $socialUser->getAvatar();
            }

            // Fetch additional data from Google People API
            if ($provider === 'google' && method_exists($socialUser, 'token')) {
                $accessToken = $socialUser->token;
                $peopleData = $this->fetchGooglePeopleData($accessToken);

                if ($peopleData) {
                    // Extract phone number
                    if (isset($peopleData['phoneNumbers']) && ! empty($peopleData['phoneNumbers'])) {
                        $phone = $peopleData['phoneNumbers'][0]['value'] ?? null;
                        if ($phone) {
                            $userData['phone'] = $phone;
                        }
                    }

                    // Extract address
                    if (isset($peopleData['addresses']) && ! empty($peopleData['addresses'])) {
                        $address = $peopleData['addresses'][0];

                        // Full formatted address
                        if (isset($address['formattedValue'])) {
                            $userData['address'] = $address['formattedValue'];
                        } elseif (isset($address['streetAddress'])) {
                            $userData['address'] = $address['streetAddress'];
                        }

                        // City
                        if (isset($address['city'])) {
                            $userData['city'] = $address['city'];
                        }

                        // Country
                        if (isset($address['country'])) {
                            $userData['country'] = $address['country'];
                        }

                        // Postal code
                        if (isset($address['postalCode'])) {
                            $userData['zip_code'] = $address['postalCode'];
                        }
                    }
                }
            }

            $user = User::create($userData);

            Auth::login($user);
        }

        return redirect()->intended('/')->with('success', 'Hoş geldiniz, '.$user->name);
    }

    /**
     * Fetch additional user data from Google People API
     *
     * @param  string  $accessToken
     * @return array|null
     */
    protected function fetchGooglePeopleData($accessToken)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('https://people.googleapis.com/v1/people/me', [
                'personFields' => 'phoneNumbers,addresses',
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                \Log::info('Google People API Response', $data);

                return $data;
            }

            \Log::warning('Google People API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('Google People API exception', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
