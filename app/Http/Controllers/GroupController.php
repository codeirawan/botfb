<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Category;
use App\Models\FacebookAccount;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('groups.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'fb_group_id' => 'required|string|max:100|unique:groups,fb_group_id',
            'category_id' => 'nullable|exists:categories,id',
            'active' => 'boolean',
        ]);

        Group::create([
            'name' => $request->name,
            'fb_group_id' => $request->fb_group_id,
            'category_id' => $request->category_id,
            'active' => $request->active ?? true,
        ]);

        return redirect()->route('groups.index')->with('success', 'Group added successfully.');
    }

    public function edit(Group $group)
    {
        $categories = Category::all();
        return view('groups.edit', compact('group', 'categories'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'fb_group_id' => 'required|string|max:100|unique:groups,fb_group_id,' . $group->id,
            'category_id' => 'nullable|exists:categories,id',
            'active' => 'boolean',
        ]);

        $group->update([
            'name' => $request->name,
            'fb_group_id' => $request->fb_group_id,
            'category_id' => $request->category_id,
            'active' => $request->active ?? true,
        ]);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
    /**
     * ✅ Import groups from Facebook Graph API using access token from .env
     */
    public function import()
    {
        $accessToken = config('services.facebook.access_token');

        if (!$accessToken) {
            Log::warning('❌ Import aborted: No Facebook access token found in .env.');
            return redirect()
                ->route('settings.facebook')
                ->with('error', 'Facebook access token not found in .env file.');
        }

        try {
            Log::info('🚀 Starting Facebook group import...');

            $url = 'https://graph.facebook.com/v20.0/me/groups';
            $imported = 0;

            do {
                Log::info('📡 Fetching groups from API', ['url' => $url]);

                $response = Http::get($url, [
                    'access_token' => $accessToken,
                ]);

                if ($response->failed()) {
                    Log::error('❌ HTTP request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return redirect()
                        ->route('groups.index')
                        ->with('error', 'Failed to fetch groups from Facebook.');
                }

                $data = $response->json();

                // Handle Facebook API errors
                if (isset($data['error'])) {
                    Log::error('⚠️ Facebook API Error', [
                        'message' => $data['error']['message'] ?? 'Unknown error',
                        'code' => $data['error']['code'] ?? null,
                    ]);
                    return redirect()
                        ->route('groups.index')
                        ->with('error', $data['error']['message'] ?? 'Facebook API error.');
                }

                // Import group data
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $fbGroup) {
                        $groupId = $fbGroup['id'] ?? null;
                        $groupName = $fbGroup['name'] ?? '(no name)';

                        Log::info('🟢 Importing group', [
                            'fb_group_id' => $groupId,
                            'name' => $groupName,
                        ]);

                        Group::updateOrCreate(
                            ['fb_group_id' => $groupId],
                            [
                                'name' => $groupName,
                                'active' => true,
                            ]
                        );

                        $imported++;
                    }
                } else {
                    Log::info('ℹ️ No group data found in current response.');
                }

                // Next page if exists (Facebook paginates results)
                $url = $data['paging']['next'] ?? null;

                if ($url) {
                    Log::info('➡️ Next page detected, continuing...', ['next' => $url]);
                }

            } while ($url);

            Log::info('✅ Import completed successfully', [
                'total_imported' => $imported,
            ]);

            return redirect()
                ->route('groups.index')
                ->with('success', "Imported {$imported} groups successfully from Facebook.");

        } catch (\Exception $e) {
            Log::error('💥 Exception during import', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('groups.index')
                ->with('error', 'Error importing groups: ' . $e->getMessage());
        }
    }

    public function importpg()
    {
        $accessToken = config('services.facebook.access_token');

        if (!$accessToken) {
            \Log::warning('❌ Import aborted: No Facebook access token found in .env.');
            return redirect()
                ->route('settings.facebook')
                ->with('error', 'Facebook access token not found in .env file.');
        }

        try {
            \Log::info('🚀 Starting Facebook page import...');

            $url = 'https://graph.facebook.com/v20.0/me/accounts';
            $imported = 0;

            do {
                \Log::info('📡 Fetching pages from API', ['url' => $url]);

                $response = \Illuminate\Support\Facades\Http::get($url, [
                    'access_token' => $accessToken,
                ]);

                if ($response->failed()) {
                    \Log::error('❌ HTTP request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return redirect()
                        ->route('groups.index')
                        ->with('error', 'Failed to fetch pages from Facebook.');
                }

                $data = $response->json();

                // Handle Facebook API errors
                if (isset($data['error'])) {
                    \Log::error('⚠️ Facebook API Error', [
                        'message' => $data['error']['message'] ?? 'Unknown error',
                        'code' => $data['error']['code'] ?? null,
                    ]);
                    return redirect()
                        ->route('groups.index')
                        ->with('error', $data['error']['message'] ?? 'Facebook API error.');
                }

                // Import page data
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $fbPage) {
                        $pageId = $fbPage['id'] ?? null;
                        $pageName = $fbPage['name'] ?? '(no name)';
                        $pageAccessToken = $fbPage['access_token'] ?? null;

                        \Log::info('🟢 Importing page', [
                            'fb_page_id' => $pageId,
                            'name' => $pageName,
                        ]);

                        \App\Models\Page::updateOrCreate(
                            ['fb_page_id' => $pageId],
                            [
                                'name' => $pageName,
                                'access_token' => $pageAccessToken,
                                'active' => true,
                            ]
                        );

                        $imported++;
                    }
                } else {
                    \Log::info('ℹ️ No page data found in current response.');
                }

                // Handle pagination
                $url = $data['paging']['next'] ?? null;

                if ($url) {
                    \Log::info('➡️ Next page detected, continuing...', ['next' => $url]);
                }

            } while ($url);

            \Log::info('✅ Page import completed successfully', [
                'total_imported' => $imported,
            ]);

            return redirect()
                ->route('groups.index')
                ->with('success', "Imported {$imported} pages successfully from Facebook.");

        } catch (\Exception $e) {
            \Log::error('💥 Exception during page import', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('groups.index')
                ->with('error', 'Error importing pages: ' . $e->getMessage());
        }
    }

}
