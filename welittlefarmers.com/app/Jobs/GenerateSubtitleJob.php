<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateSubtitleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 1800; // 30 minutes for large videos

    protected $lessonId;
    protected $videoPath;

    /**
     * Create a new job instance.
     */
    public function __construct($lessonId, $videoPath)
    {
        $this->lessonId = $lessonId;
        $this->videoPath = $videoPath;

        Log::info("Generate subtitle job testing: {$this->lessonId}");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lesson = Lesson::find($this->lessonId);

        Log::info("Testing in handle function: {$lesson}");

        if (!$lesson) {
            Log::error("Lesson not found: {$this->lessonId}");
            return;
        }

        try {
            $lesson->update(['subtitle_status' => 'processing']);

            Log::info("Testing in handle function-2: {$lesson}");

            Log::info("🎬 Starting subtitle generation for: {$lesson->title}");

            $url = $this->videoPath;
            Log::info('Testing:-', ['subtitle' => $url]);

            $filename = basename($url);
            Log::info('filename:-', ['filename' => $filename]);

            // Get video file path
            $videoFullPath = storage_path('app/videos/' . $filename);

            if (!file_exists($videoFullPath)) {
                throw new \Exception("Video file not found: {$videoFullPath}");
            }

            $fileSize = filesize($videoFullPath);
            $fileSizeMB = round($fileSize / (1024 * 1024), 2);

            Log::info("📊 Video size: {$fileSizeMB} MB");

            // Generate VTT using Python Whisper
            $vttPath = $this->generateSubtitleWithWhisper($videoFullPath, $lesson->id);

            // Update lesson record
            $lesson->update([
                'vtt_path' => $vttPath,
                'subtitle_status' => 'completed',
                'subtitle_error' => null
            ]);

            Log::info("✅ Subtitle generated successfully!");
        } catch (\Exception $e) {
            $lesson->update([
                'subtitle_status' => 'failed',
                'subtitle_error' => $e->getMessage()
            ]);

            Log::error("❌ Failed: " . $e->getMessage());

            throw $e;
        }
    }

    /**
     * Generate subtitle using Python Whisper
     */
    private function generateSubtitleWithWhisper($videoPath, $lessonId)
    {
        // Output VTT path
        $vttFilename = 'lesson_' . $lessonId . '_' . time() . '.vtt';
        $vttFullPath = storage_path('app/public/subtitles/' . $vttFilename);

        // Create subtitles directory if not exists
        $subtitlesDir = storage_path('app/public/subtitles');
        if (!file_exists($subtitlesDir)) {
            mkdir($subtitlesDir, 0755, true);
        }

        // Path to Python script
        $scriptPath = base_path('scripts/generate_subtitle.py');

        if (!file_exists($scriptPath)) {
            throw new \Exception("Python script not found at: {$scriptPath}");
        }

        Log::info("🐍 Calling Python Whisper...");

        // Build command (Windows-friendly)
        $command = sprintf(
            'python "%s" "%s" "%s" 2>&1',
            $scriptPath,
            $videoPath,
            $vttFullPath
        );

        // Execute command
        exec($command, $output, $returnCode);

        // Log output
        foreach ($output as $line) {
            Log::info("Python: " . $line);
        }

        // Check if successful
        if ($returnCode !== 0 || !file_exists($vttFullPath)) {
            throw new \Exception("Whisper failed: " . implode("\n", $output));
        }

        Log::info("💾 VTT file created: {$vttFullPath}");

        return 'subtitles/' . $vttFilename;
    }

    public function failed(\Throwable $exception)
    {
        $lesson = Lesson::find($this->lessonId);
        if ($lesson) {
            $lesson->update([
                'subtitle_status' => 'failed',
                'subtitle_error' => $exception->getMessage()
            ]);
        }

        Log::error("Job failed for lesson {$this->lessonId}: " . $exception->getMessage());
    }
}
