<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PostToFacebook extends Command
{
    protected $signature = 'bot:post-facebook';
    protected $description = 'Post scheduled posts to Facebook automatically';

    public function handle()
    {
        $this->info('Bot started...');
        $now = Carbon::now();
        $duePosts = Post::where('status', 'scheduled')
            ->where('scheduled_at', '<=', $now)
            ->get();

        foreach ($duePosts as $post) {
            foreach ($post->groups as $group) {
                // Example: posting to a group using the stored access token
                $response = Http::post("https://graph.facebook.com/{$group->group_id}/feed", [
                    'message' => $post->content,
                    'access_token' => $group->access_token, // must exist
                ]);

                if ($response->successful()) {
                    $this->info("✅ Posted to group {$group->name}");
                } else {
                    $this->error("❌ Failed posting to {$group->name}: " . $response->body());
                }
            }

            // Update status to posted
            $post->update(['status' => 'posted']);
        }

        return 0;
    }
}
