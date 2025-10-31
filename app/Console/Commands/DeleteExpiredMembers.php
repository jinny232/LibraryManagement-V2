<?php
namespace App\Console\Commands;
use App\Models\Member;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteExpiredMembers extends Command
{
    protected $signature = 'members:delete-expired';
    protected $description = 'Delete members whose expiration date has passed.';

    public function handle()
    {
        $expiredMembersCount = Member::where('expired_at', '<', Carbon::now())->delete();

        $this->info("{$expiredMembersCount} expired members have been deleted.");
    }
}
