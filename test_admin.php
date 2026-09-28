<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Http\Kernel::class);

// Login as admin
\ = App\Models\User::where('email', 'admin@admin.com')->first();
if (\) {
    Auth::guard('web')->login(\);
}

\ = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
\ = \->handle(\);
echo "STATUS CODE: " . \->getStatusCode() . "\n";
if (\->getStatusCode() == 500) {
    if (\->exception) {
        echo \->exception->getMessage() . "\n";
        echo \->exception->getTraceAsString() . "\n";
    }
}
