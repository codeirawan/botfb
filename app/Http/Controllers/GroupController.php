<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Category;
use App\Models\FacebookAccount;
use Illuminate\Support\Facades\Http;

class GroupController extends Controller
{
    /**
     * Display a list of all groups.
     */
    public function index()
    {
        $groups = Group::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form to create a new group manually.
     */
    public function create()
    {
        $categories = Category::all();
        return view('groups.create', compact('categories'));
    }

    /**
     * Store a newly created group in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'fb_group_id'  => 'required|string|max:100|unique:groups,fb_group_id',
            'category_id'  => 'nullable|exists:categories,id',
            'privacy'      => 'nullable|in:public,closed,secret',
            'active'       => 'boolean',
        ]);

        Group::create([
            'name'         => $request->name,
            'fb_group_id'  => $request->fb_group_id,
            'category_id'  => $request->category_id,
            'privacy'      => $request->privacy ?? 'public',
            'active'       => $request->active ?? true,
            'facebook_account_id' => auth()->id(), // You can adjust based on your auth logic
        ]);

        return redirect()->route('groups.index')->with('success', 'Group added successfully.');
    }

    /**
     * Show the form for editing a group.
     */
    public function edit(Group $group)
    {
        $categories = Category::all();
        return view('groups.edit', compact('group', 'categories'));
    }

    /**
     * Update an existing group.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'fb_group_id'  => 'required|string|max:100|unique:groups,fb_group_id,' . $group->id,
            'category_id'  => 'nullable|exists:categories,id',
            'privacy'      => 'nullable|in:public,closed,secret',
            'active'       => 'boolean',
        ]);

        $group->update([
            'name'         => $request->name,
            'fb_group_id'  => $request->fb_group_id,
            'category_id'  => $request->category_id,
            'privacy'      => $request->privacy ?? 'public',
            'active'       => $request->active ?? true,
        ]);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    /**
     * Delete a group.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }

    /**
     * Show the import page.
     */
    public function import()
    {
        return view('groups.import');
    }

    /**
     * Handle importing groups from Facebook API.
     */
    public function storeImport(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        // Example Facebook API call
        try {
            $response = Http::get('https://graph.facebook.com/v20.0/me/groups', [
                'access_token' => $request->access_token,
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Failed to fetch groups from Facebook.');
            }

            $data = $response->json();

            if (!empty($data['data'])) {
                foreach ($data['data'] as $fbGroup) {
                    Group::updateOrCreate(
                        ['fb_group_id' => $fbGroup['id']],
                        [
                            'name' => $fbGroup['name'] ?? 'Unnamed Group',
                            'privacy' => $fbGroup['privacy'] ?? 'public',
                            'active' => true,
                            'facebook_account_id' => auth()->id(),
                        ]
                    );
                }
            }

            return redirect()->route('groups.index')->with('success', 'Groups imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing groups: ' . $e->getMessage());
        }
    }
}
