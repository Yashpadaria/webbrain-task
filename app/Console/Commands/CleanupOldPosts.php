<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Post;

class CleanupOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-old-posts';
    protected $description = 'Delete posts and comments older than one year';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subYear();
        Post::where('created_at', '<', $date)->delete();
        $this->info('Old posts and comments have been deleted.');
    }
}
