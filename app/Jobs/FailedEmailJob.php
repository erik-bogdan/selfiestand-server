<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Image;
use App\Models\FailedEmail;
use App\Http\Helpers\EmailHelper;

class FailedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $failedEmails = FailedEmail::where([
            ['is_success', '=', 0],
            ['attempts', '<', 10],
        ])->get();

        foreach ($failedEmails as $failedEmail) {
            $imageObject = Image::where('uuid', $failedEmail->uuid)->first();

            if (!$imageObject) {
                $failedEmail->update([
                    'attempts' => $failedEmail->attempts + 1,
                    'last_attempt' => new \DateTime(),
                ]);
            } else {
                EmailHelper::sendImage($imageObject, $failedEmail->email);
                $failedEmail->update([
                    'attempts' => $failedEmail->attempts + 1,
                    'last_attempt' => new \DateTime(),
                    'is_success' => true,
                ]);
                
                $imageObject->update(['email_sent' => 1]);
            }
        }
    }
}
 