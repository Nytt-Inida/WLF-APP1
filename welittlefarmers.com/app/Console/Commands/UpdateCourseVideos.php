<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class UpdateCourseVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:update-videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the preview video URLs for courses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->error('No courses found in the database.');
            return 1;
        }
        
        $this->info('Available courses:');
        foreach ($courses as $index => $course) {
            $this->line(sprintf(
                '%d. %s (ID: %d)%s',
                $index + 1,
                $course->title,
                $course->id,
                $course->preview_video_url ? ' [Has video: ' . $course->preview_video_url . ']' : ''
            ));
        }
        
        $courseIndex = $this->ask('Enter the number of the course to update (or "all" to update all)');
        
        if (strtolower($courseIndex) === 'all') {
            $this->updateAllCourses($courses);
        } else {
            $courseIndex = (int)$courseIndex - 1;
            if (!isset($courses[$courseIndex])) {
                $this->error('Invalid course number.');
                return 1;
            }
            $this->updateSingleCourse($courses[$courseIndex]);
        }
        
        $this->info('\nVideo update process completed!');
        return 0;
    }
    
    protected function updateSingleCourse($course)
    {
        $this->info("\nUpdating video for: {$course->title}");
        
        // List available video files
        $videoPath = 'public/videos/courses';
        $files = Storage::files($videoPath);
        
        if (empty($files)) {
            $this->warn("No video files found in storage/app/{$videoPath}");
            $this->warn("Please make sure to upload your video files to: storage/app/{$videoPath}");
            return;
        }
        
        $this->info("\nAvailable video files:");
        foreach ($files as $index => $file) {
            $this->line(sprintf('%d. %s', $index + 1, basename($file)));
        }
        
        $fileIndex = $this->ask('Enter the number of the video file to assign (or leave empty to skip)');
        
        if (empty($fileIndex)) {
            $this->info('Skipping video update for this course.');
            return;
        }
        
        $fileIndex = (int)$fileIndex - 1;
        if (!isset($files[$fileIndex])) {
            $this->error('Invalid file number.');
            return;
        }
        
        $selectedFile = basename($files[$fileIndex]);
        $course->preview_video_url = $selectedFile;
        $course->save();
        
        $this->info("\n✅ Successfully updated {$course->title} with video: {$selectedFile}");
    }
    
    protected function updateAllCourses($courses)
    {
        $this->info('\nUpdating videos for all courses. Enter the video filename for each course.');
        $this->info('You can find your video files in: storage/app/public/videos/courses/');
        
        foreach ($courses as $course) {
            $this->updateSingleCourse($course);
        }
    }
}
