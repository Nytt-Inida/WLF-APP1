# WeLittleFarmer Application

This repository contains both the Flutter mobile app (frontend) and Laravel backend code for the WeLittleFarmer application.

## Project Structure

```
.
├── LitteleFarmer/          # Flutter mobile app (Frontend)
│   ├── lib/                # Dart source code
│   ├── android/            # Android platform code
│   ├── ios/                # iOS platform code
│   └── ...
└── welittlefarmers.com/    # Laravel backend (Backend)
    ├── app/                # Application logic
    ├── routes/             # API routes
    ├── database/           # Migrations and seeders
    └── ...
```

## Getting Started

### Prerequisites
- For Flutter app: Flutter SDK (3.0+)
- For Laravel backend: PHP 8.1+, Composer

## GitHub Setup Instructions

### 1. Create a GitHub Repository
1. Go to [GitHub](https://github.com) and sign in
2. Click the "+" icon in the top right corner
3. Select "New repository"
4. Name your repository (e.g., `WeLittleFarmer`)
5. Choose Public or Private
6. **Do NOT** initialize with README, .gitignore, or license (we already have these)
7. Click "Create repository"

### 2. Push Your Code to GitHub

Run these commands in your terminal (from the project root directory):

```bash
# Add the remote repository (replace YOUR_USERNAME and REPO_NAME with your GitHub username and repository name)
git remote add origin https://github.com/YOUR_USERNAME/REPO_NAME.git

# Rename the branch to main (if needed)
git branch -M main

# Push your code to GitHub
git push -u origin main
```

### 3. Add Your Friend as Collaborator

1. Go to your repository on GitHub
2. Click on the "Settings" tab (at the top of the repository page)
3. In the left sidebar, click "Collaborators"
4. Click "Add people" button
5. Enter your friend's GitHub username or email address
6. Click "Add [username] to this repository"
7. Choose the access level (usually "Write" access is appropriate for collaborators)
8. Click "Add [username] to this repository"
9. Your friend will receive an email invitation to collaborate

### 4. Your Friend Can Now Clone and Pull

Once your friend accepts the collaboration invitation, they can:

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/REPO_NAME.git

# Or if they already have it, pull the latest changes
git pull origin main
```

## Important Notes

- The `__MACOSX/` folder is excluded from Git
- Sensitive files like `.env`, credentials, and API keys are excluded
- Build artifacts and dependencies (vendor, node_modules, build folders) are excluded

## Development Setup

### Flutter App Setup
```bash
cd LitteleFarmer
flutter pub get
flutter run
```

### Laravel Backend Setup
```bash
cd welittlefarmers.com
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## License
[Add your license here if needed]

