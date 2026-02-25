# Pet Lovers Community Onboarding Wizard (PHP + CSV)

## Features
- 5-step onboarding wizard (separate pages with Next/Previous)
- Login for returning users
- View all member profiles
- Edit profile (username locked)
- Delete profile
- CSV flat-file database (`data/users.csv`, `data/pets.csv`)
- Image uploads for profile and pets

## Requirements
- PHP 8+ with file uploads enabled

## Run Locally
From the project folder:

```bash
php -S localhost:8000
```

Then open `http://localhost:8000/index.php`

## Notes
- Uploaded images are stored in `uploads/profiles` and `uploads/pets`
- CSV files are auto-created with headers on first run
