<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display the Facebook connection settings page.
     */
    public function facebook()
    {
        // Example: fetch stored settings from DB (table: settings)
        $settings = DB::table('settings')
            ->whereIn('key', ['facebook_app_id', 'facebook_app_secret', 'facebook_access_token'])
            ->pluck('value', 'key')
            ->toArray();

        return view('settings.facebook', compact('settings'));
    }

    /**
     * Save or update the Facebook API settings.
     */
    public function updateFacebook(Request $request)
    {
        $request->validate([
            'facebook_app_id' => 'required|string|max:255',
            'facebook_app_secret' => 'required|string|max:255',
            'facebook_access_token' => 'required|string',
        ]);

        $data = $request->only(['facebook_app_id', 'facebook_app_secret', 'facebook_access_token']);

        // Store in database (simple key-value storage)
        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('settings.facebook')->with('success', 'Facebook connection settings saved successfully.');
    }

    /**
     * Optional: Verify the Facebook connection using Graph API.
     */
    public function testFacebookConnection()
    {
        try {
            $token = DB::table('settings')->where('key', 'facebook_access_token')->value('value');

            if (!$token) {
                return back()->with('error', 'No access token found.');
            }

            $response = Http::get('https://graph.facebook.com/v20.0/me', [
                'access_token' => $token,
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Failed to connect to Facebook API.');
            }

            $data = $response->json();
            return back()->with('success', 'Connected as: ' . ($data['name'] ?? 'Unknown User'));
        } catch (\Exception $e) {
            return back()->with('error', 'Connection test failed: ' . $e->getMessage());
        }
    }
}
