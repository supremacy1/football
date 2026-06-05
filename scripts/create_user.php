<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Test User',
    'username' => 'test',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
]);

echo "USER_CREATED\n";
