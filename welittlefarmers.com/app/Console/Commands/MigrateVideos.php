<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigrateVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate videos from public to storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting video migration...');

        // *** Your local video folder ***
        $localSourceFolder = 'E:\Work\lfwebsite\Little_farmer_img\exam_vid\exam_vid';

        $lessons = Lesson::all();
        $count = 0;

        foreach ($lessons as $lesson) {
            $videoUrl = $lesson->video_url;

            // Extract filename from URL
            $path = parse_url($videoUrl, PHP_URL_PATH);
            $filename = basename($path);

            // Full path to source video
            $sourcePath = $localSourceFolder . DIRECTORY_SEPARATOR . $filename;

            if (File::exists($sourcePath)) {

                $destinationPath = 'videos/' . $filename;

                Storage::disk('local')->put(
                    $destinationPath,
                    File::get($sourcePath)
                );

                // Update DB with new secure storage filename
                $lesson->update(['video_url' => $filename]);

                $count++;

                $this->info("Migrated: {$filename}");
            } else {
                $this->error("File not found: {$sourcePath}");
            }
        }

        $this->info("Migration complete! Moved {$count} videos.");
        return 0;
    }
}