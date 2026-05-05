<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@batabi-lebu.com')->first();
if ($user) {
    echo "User found: " . $user->name . " (Role: " . $user->role . ")\n";
} else {
    echo "User NOT found!\n";
}
