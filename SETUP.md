# Football Social Media Platform - Quick Start Guide

## 🚀 Quick Setup (5 minutes)

### Step 1: Database Setup
```bash
# Create a new MySQL database
mysql -u root -p
> CREATE DATABASE football_social_media;
> EXIT;
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```
DB_DATABASE=football_social_media
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Run Migrations
```bash
php artisan migrate
```

### Step 5: Seed Sample Data (Optional)
```bash
php artisan db:seed
```

### Step 6: Create Storage Link
```bash
php artisan storage:link
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 📝 Create Your First Account

1. Click "Register" on the welcome page
2. Fill in your details:
   - Full Name
   - Username
   - Email
   - Password (minimum 8 characters)
3. Click "Register"
4. You'll be logged in automatically!

---

## 🎮 How to Use

### Create a Post
1. Go to Feed (Home page)
2. Type your message in the text area
3. Optionally select a club
4. Upload an image or video (optional)
5. Click "Post"

### Join a Club
1. Go to Clubs section
2. Browse available clubs
3. Click "Join" on any club
4. Start following club-related discussions

### Engage with Posts
1. **Like** - Click the thumbs up icon
2. **Dislike** - Click the thumbs down icon
3. **Comment** - Click comment and add your thoughts
4. **Share** - Click share to spread the post
5. **Like Comments** - Click heart icon on comments

### Edit Your Profile
1. Click your profile icon (top right)
2. Click "Edit Profile"
3. Update your information:
   - Bio
   - Location
   - Date of Birth
   - Favorite Club
   - Profile Picture
   - Cover Photo
4. Click "Save Changes"

### Follow Other Users
1. Visit a user's profile
2. Click "Follow" button
3. View their posts on your feed

---

## 🔧 Useful Commands

```bash
# Clear application cache
php artisan cache:clear

# Refresh migrations (WARNING: Deletes data)
php artisan migrate:refresh --seed

# Generate API documentation
php artisan route:list

# Access database via CLI
php artisan tinker

# Create admin user (in tinker)
User::create([
    'name' => 'Admin',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
])
```

---

## 🏗️ Project Structure

```
banta/
├── app/Models/           # Database models
├── app/Http/Controllers/ # Controllers for business logic
├── database/migrations/  # Database schema files
├── database/seeders/     # Initial data seeders
├── resources/views/      # Blade templates
├── routes/web.php        # Application routes
├── config/               # Configuration files
└── storage/              # File uploads & logs
```

---

## 🐛 Troubleshooting

### Port 8000 Already in Use
```bash
php artisan serve --port=8001
```

### Migrations Failed
```bash
# Check if MySQL is running and .env is correct
php artisan migrate --force
```

### Can't Upload Files
```bash
# Ensure storage link exists
php artisan storage:link

# Check folder permissions
chmod -R 775 storage/
```

### Password Reset Not Working
```bash
# Ensure MAIL_MAILER is set to 'log' for testing
# Check storage/logs/laravel.log for emails
```

---

## 📚 Features Overview

### ✅ User Features
- ✓ Registration & Login
- ✓ Profile customization
- ✓ Follow/Unfollow users
- ✓ Password reset

### ✅ Post Features
- ✓ Create posts with text
- ✓ Upload images & videos
- ✓ Edit/Delete posts
- ✓ Post types (General, Match Discussion, Transfer News, Stats)

### ✅ Engagement
- ✓ Like/Dislike posts
- ✓ Comment on posts
- ✓ Reply to comments
- ✓ Like comments
- ✓ Share posts

### ✅ Football Features
- ✓ Join clubs
- ✓ Club member groups
- ✓ Player profiles
- ✓ Match scheduling
- ✓ Match discussions

---

## 🎯 Next Steps

1. **Customize Branding**
   - Edit navbar color in `resources/views/layouts/app.blade.php`
   - Update company name in `APP_NAME` in `.env`

2. **Add More Clubs**
   - Edit `database/seeders/ClubSeeder.php`
   - Run `php artisan db:seed`

3. **Enable Email Notifications**
   - Update `MAIL_MAILER` in `.env`
   - Configure mail settings

4. **Deploy to Production**
   - Set `APP_DEBUG=false` in `.env`
   - Set appropriate `APP_URL`
   - Use a proper mail service
   - Enable HTTPS

---

## 📧 Support

For issues or questions:
1. Check the README.md for detailed documentation
2. Review Laravel documentation: https://laravel.com/docs
3. Check application logs: `storage/logs/laravel.log`

---

## 🎉 You're All Set!

Start building your football fan community now!
