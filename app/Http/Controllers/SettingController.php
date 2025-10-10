<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookAccount;

class SettingController extends Controller
{
    /**
     * Display Facebook settings form.
     */
    public function facebook()
    {
        $account = FacebookAccount::first();
        return view('settings.facebook', compact('account'));
    }

    /**
     * Store or update the Facebook account settings.
     */
    public function updateFacebook(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fb_user_id' => 'required|string|max:255',
            'app_id' => 'required|string|max:255',
            'app_secret' => 'required|string|max:255',
            'access_token' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $account = FacebookAccount::firstOrNew(['id' => 1]);
                $account->fill([
                    'name' => $request->name,
                    'fb_user_id' => $request->fb_user_id,
                    'app_id' => $request->app_id,
                    'app_secret' => $request->app_secret,
                    'access_token' => $request->access_token,
                ]);
                $account->save();
            });

            return redirect()
                ->route('settings.facebook')
                ->with('success', 'Facebook settings saved successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.facebook')
                ->with('error', 'Failed to save Facebook settings: ' . $e->getMessage());
        }
    }

    /**
     * Test Facebook API connection.
     */
    public function testFacebook(Request $request)
    {
        $account = FacebookAccount::first();

        if (!$account || !$account->access_token) {
            return redirect()
                ->route('settings.facebook')
                ->with('error', 'Access token not found. Please save your Facebook settings first.');
        }

        try {
            $response = Http::get('https://graph.facebook.com/me', [
                'access_token' => $account->access_token,
            ]);

            $data = $response->json();

            if ($response->failed() || isset($data['error'])) {
                $message = $data['error']['message'] ?? 'Facebook API connection failed.';
                return redirect()
                    ->route('settings.facebook')
                    ->with('error', 'Connection test failed: ' . $message);
            }

            return redirect()
                ->route('settings.facebook')
                ->with('success', 'Facebook connection successful! Logged in as: ' . ($data['name'] ?? 'Unknown'));
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.facebook')
                ->with('error', 'Error testing Facebook connection: ' . $e->getMessage());
        }
    }
}
