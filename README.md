# Football Social Media Platform

A Laravel-based social media platform designed for football (soccer) fans to connect, share posts, engage with content, and follow their favorite clubs and players.

## Features

### 👤 User System
- User registration and account creation
- Email verification
- Login/Logout functionality
- Password reset functionality
- User profiles with customizable information
- Profile pictures and cover photos
- Follow/Unfollow other users
- User bio and location information

### 📝 Post System
- Create, edit, and delete posts
- Upload images and videos with posts
- Post types: General, Match Discussion, Transfer News, Player Statistics
- News feed with paginated posts
- Rich text posting
- Post sharing

### 💬 Engagement Features
- Like posts
- Dislike posts
- Comment on posts
- Reply to comments
- Like comments
- View engagement metrics

### ⚽ Football-Specific Features
- Club management and joining
- Club member groups
- Player statistics and profiles
- Match scheduling and score updates
- Match discussions
- Club-specific posts and news
- Favorite club selection
- Player profiles with stats (Goals, Assists, Matches Played)

## Technology Stack

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade Templates with Bootstrap 5
- **Authentication**: Laravel built-in authentication
- **File Upload**: Local storage with Laravel filesystem
- **UI Framework**: Bootstrap 5 with custom styling

## Database Schema

### Tables
- `users` - User accounts and profiles
- `posts` - User posts
- `comments` - Comments on posts
- `post_likes` - Post engagement (likes/dislikes)
- `comment_likes` - Comment engagement
- `clubs` - Football clubs
- `club_members` - Club membership relationships
- `follows` - User follow relationships
- `matches` - Football matches
- `players` - Player information
- `password_reset_tokens` - Password reset tokens

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer

### Steps

1. **Clone or extract the project**
   ```bash
   cd banta
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create .env file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database in .env**
   ```
   DB_DATABASE=footbal_bant
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Run seeders (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create storage link for file uploads**
   ```bash
   php artisan storage:link
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - Navigate to `http://localhost:8000` in your browser

## Project Structure

```
banta/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/ (Authentication controllers)
│   │   │   ├── Post/ (Post & Comment controllers)
│   │   │   ├── ProfileController.php
│   │   │   └── ClubController.php
│   │   └── Requests/ (Form validation)
│   └── Models/ (Database models)
├── database/
│   ├── migrations/ (Database schema)
│   └── seeders/ (Initial data)
├── resources/
│   ├── views/
│   │   ├── auth/ (Authentication pages)
│   │   ├── posts/ (Post pages)
│   │   ├── profile/ (Profile pages)
│   │   ├── clubs/ (Club pages)
│   │   └── layouts/ (Layout templates)
├── routes/
│   └── web.php (Application routes)
└── config/ (Configuration files)
```

## Routes Overview

### Authentication Routes
- `GET /register` - Show registration form
- `POST /register` - Register new user
- `GET /login` - Show login form
- `POST /login` - Login user
- `POST /logout` - Logout user
- `GET /forgot-password` - Password reset request
- `POST /reset-password` - Reset password

### Feed Routes
- `GET /feed` - View news feed
- `GET /posts/create` - Create post form
- `POST /posts` - Store new post
- `GET /posts/{post}` - View single post
- `GET /posts/{post}/edit` - Edit post form
- `PUT /posts/{post}` - Update post
- `DELETE /posts/{post}` - Delete post

### Engagement Routes
- `POST /posts/{post}/like` - Like a post
- `POST /posts/{post}/dislike` - Dislike a post
- `POST /posts/{post}/share` - Share a post
- `POST /posts/{post}/comments` - Add comment
- `PUT /comments/{comment}` - Edit comment
- `DELETE /comments/{comment}` - Delete comment
- `POST /comments/{comment}/like` - Like comment

### Profile Routes
- `GET /profile` - Edit own profile
- `PUT /profile` - Update profile
- `GET /profile/{user}` - View user profile
- `POST /profile/{user}/follow` - Follow user
- `POST /profile/{user}/unfollow` - Unfollow user

### Club Routes
- `GET /clubs` - List all clubs
- `GET /clubs/{club}` - View club details
- `POST /clubs/{club}/join` - Join club
- `POST /clubs/{club}/leave` - Leave club
- `GET /matches/create` - Create match form
- `POST /matches` - Store new match
- `PUT /matches/{match}/score` - Update match score

## Key Models & Relationships

### User Model
- Has many posts
- Has many comments
- Can follow/be followed by other users
- Has many club memberships
- Can like/dislike posts and comments

### Post Model
- Belongs to user
- Belongs to club (optional)
- Has many comments
- Has many likes/dislikes
- Tracks engagement metrics

### Club Model
- Has many members (users)
- Has many posts
- Has many players
- Has home and away matches

### Comment Model
- Belongs to post
- Belongs to user
- Can have parent comment (replies)
- Has many likes

## Usage Examples

### Create a Post
1. Navigate to `/posts/create`
2. Fill in post content
3. Optionally select a club
4. Upload images/videos if needed
5. Submit to create the post

### Join a Club
1. Go to `/clubs` to view all clubs
2. Click "Join" on any club
3. You'll be added as a member

### Interact with Posts
1. Like/Dislike posts on the feed
2. View individual posts with all comments
3. Add comments and reply to other comments
4. Like comments from other users

## Future Enhancements

- Real-time notifications
- Direct messaging between users
- Advanced search functionality
- Hashtag support
- Post recommendations based on interests
- Live match commentary
- API endpoints for mobile apps
- Social media sharing integration
- User roles and permissions
- Moderation tools

## Support & Troubleshooting

### Common Issues

**Database connection error:**
- Ensure MySQL is running
- Check DB credentials in .env
- Run `php artisan migrate`

**File upload not working:**
- Run `php artisan storage:link`
- Check storage/ directory permissions

**Page not found:**
- Ensure you're running `php artisan serve`
- Clear cache: `php artisan cache:clear`

## License

This project is created as an educational platform for football fans.
