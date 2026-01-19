import whisper
import sys
import os
os.environ["PATH"] += os.pathsep + r"C:\Program Files\ffmpeg-8.0.1-essentials_build\bin" 

def generate_subtitle(video_path, output_path):
    try:
        print(f"Loading Whisper model...")
        
        # Use 'base' model (faster, good quality)
        # Options: tiny, base, small, medium, large
        model = whisper.load_model("base")
        
        print(f"Processing video: {video_path}")
        
        # Transcribe
        result = model.transcribe(
            video_path,
            language='en',  # Change to 'es', 'fr', etc. for other languages
            task='transcribe'
        )
        
        # Generate VTT format
        vtt_content = generate_vtt(result)
        
        # Save to file
        with open(output_path, 'w', encoding='utf-8') as f:
            f.write(vtt_content)
        
        print(f"Subtitle saved from script: {output_path}")
        return True
        
    except Exception as e:
        print("Error form python script:", e)
        return False

def generate_vtt(result):
    """Convert Whisper result to VTT format"""
    vtt = "WEBVTT\n\n"
    
    for i, segment in enumerate(result['segments']):
        start = format_timestamp(segment['start'])
        end = format_timestamp(segment['end'])
        text = segment['text'].strip()
        
        vtt += f"{i + 1}\n"
        vtt += f"{start} --> {end}\n"
        vtt += f"{text}\n\n"
    
    return vtt

def format_timestamp(seconds):
    """Convert seconds to VTT timestamp format (HH:MM:SS.mmm)"""
    hours = int(seconds // 3600)
    minutes = int((seconds % 3600) // 60)
    secs = int(seconds % 60)
    millis = int((seconds % 1) * 1000)
    
    return f"{hours:02d}:{minutes:02d}:{secs:02d}.{millis:03d}"

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python generate_subtitle.py <video_path> <output_path>")
        sys.exit(1)
    
    video_path = sys.argv[1]
    output_path = sys.argv[2]
    
    if not os.path.exists(video_path):
        print(f"Video file not found from script: {video_path}")
        sys.exit(1)
    
    success = generate_subtitle(video_path, output_path)
    sys.exit(0 if success else 1)