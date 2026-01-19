# Install FFmpeg - Fix Repository Error

## ✅ Your Situation

- ✅ **Disk space:** 6.1GB free (plenty!)
- ⚠️ **Repository error:** Old rspamd repository (not critical)
- ✅ **FFmpeg available:** Can install with `apt install ffmpeg`

---

## 🚀 Quick Fix: Install FFmpeg Directly

**The repository error won't stop you!** Just install ffmpeg directly:

```bash
apt install ffmpeg -y
```

**This will work despite the repository warning.**

---

## 🔧 Optional: Fix Repository Error (If You Want)

**The error is from an old repository that's no longer available.** You can remove it:

```bash
# Find the repository file
grep -r "rspamd.com" /etc/apt/sources.list.d/

# Remove the problematic repository (if found)
# Usually in: /etc/apt/sources.list.d/rspamd.list
rm /etc/apt/sources.list.d/rspamd.list 2>/dev/null

# Or comment it out in sources.list
sed -i 's|.*rspamd.com.*|# &|' /etc/apt/sources.list 2>/dev/null

# Update again
apt-get update
```

**But you don't need to do this** - you can just install ffmpeg directly!

---

## 📋 Step-by-Step Installation

### Option 1: Install Directly (Recommended)

```bash
# Install ffmpeg (ignore the repository warning)
apt install ffmpeg -y

# Verify installation
ffmpeg -version

# Should show version info - you're done!
```

### Option 2: Fix Repository First (Optional)

```bash
# Remove problematic repository
rm /etc/apt/sources.list.d/rspamd.list 2>/dev/null

# Update package list
apt-get update

# Install ffmpeg
apt install ffmpeg -y

# Verify
ffmpeg -version
```

---

## ✅ After Installation

**Verify it works:**

```bash
ffmpeg -version
```

**Expected output:**
```
ffmpeg version 4.2.7-0ubuntu0.1
...
```

**If you see version info, you're ready to convert videos!** ✅

---

## 🎯 Next Steps

Once ffmpeg is installed:

1. **Go to videos directory:**
   ```bash
   cd /www/wwwroot/welittlefarmers.com/storage/app/videos
   ```

2. **Backup test video:**
   ```bash
   cd ..
   mkdir -p videos_backup
   cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/
   ```

3. **Convert test video:**
   ```bash
   cd videos
   ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
       -c:v copy \
       -c:a aac \
       -b:a 128k \
       -ar 44100 \
       -ac 2 \
       -movflags +faststart \
       f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new
   ```

4. **Replace original:**
   ```bash
   mv f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
   ```

5. **Test on iOS app!** 🎉

---

## ✅ Summary

**Your situation:**
- ✅ Disk space: Good (6.1GB free)
- ⚠️ Repository warning: Not critical (old repo)
- ✅ FFmpeg: Can install directly

**Solution:**
```bash
apt install ffmpeg -y
```

**That's it!** The repository error won't stop the installation. 🚀
