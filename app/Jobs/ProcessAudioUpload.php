<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessAudioUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $item;
    public $tempDryPath;
    public $tempWetPath;

    /**
     * Create a new job instance.
     */
    public function __construct(Item $item, ?string $tempDryPath, ?string $tempWetPath)
    {
        $this->item = $item;
        $this->tempDryPath = $tempDryPath;
        $this->tempWetPath = $tempWetPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("ProcessAudioUpload started for Item ID {$this->item->id}");

        $updates = [];

        if ($this->tempDryPath && Storage::disk('local')->exists($this->tempDryPath)) {
            $newPath = 'samples/' . basename($this->tempDryPath);
            Storage::disk('public')->put($newPath, Storage::disk('local')->get($this->tempDryPath));
            Storage::disk('local')->delete($this->tempDryPath);
            $updates['dry_sample_path'] = $newPath;
        }

        if ($this->tempWetPath && Storage::disk('local')->exists($this->tempWetPath)) {
            $newPath = 'samples/' . basename($this->tempWetPath);
            Storage::disk('public')->put($newPath, Storage::disk('local')->get($this->tempWetPath));
            Storage::disk('local')->delete($this->tempWetPath);
            $updates['wet_sample_path'] = $newPath;
        }

        if (!empty($updates)) {
            $this->item->update($updates);
            Log::info("ProcessAudioUpload finished for Item ID {$this->item->id}");
        }
    }
}
