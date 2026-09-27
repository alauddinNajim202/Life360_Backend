<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pages')->truncate();
        DB::table('pages')->insert([
            [
                "name" => "FAQ",
                "slug" => "faq",
                "title" => "Frequently Asked Questions",
                "content" => "<h2>Frequently Asked Questions</h2><p><strong>1. How do I create a circle?</strong><br>You can create a circle from your dashboard by clicking on 'Create Circle' and choosing a name and theme.</p><p><strong>2. How do I invite members?</strong><br>Generate an invite code from your circle settings and share it with your family members.</p>"
            ],
            [
                "name" => "Privacy Policy",
                "slug" => "privacy-policy",
                "title" => "Privacy Policy",
                "content" => "<h2>Privacy Policy</h2><p>Welcome to our Family Tracker App. We take your privacy very seriously, especially because this application deals with real-time location data.</p><h3>1. Information We Collect</h3><p>We collect your name, email, phone number, and real-time GPS location when you use the app. We also collect data regarding your Check-Ins, SOS alerts, and Safe Places (Geofencing).</p><h3>2. How We Use Your Data</h3><p>Your location data is strictly used to share your whereabouts with the members of your 'Circles'. We do NOT sell your location data to third-party marketers.</p><h3>3. Data Security</h3><p>All data transmissions are secured using SSL encryption. Your passwords are encrypted, and access to your circles is protected via unique invite codes.</p><h3>4. Your Rights</h3><p>You can delete your account at any time from the app settings, which will permanently erase your location history and profile data from our servers.</p>"
            ],
            [
                "name" => "Terms and Conditions",
                "slug" => "terms-and-conditions",
                "title" => "Terms and Conditions",
                "content" => "<h2>Terms and Conditions</h2><p>By using the Family Tracker App, you agree to the following terms and conditions:</p><h3>1. Appropriate Use</h3><p>You agree to use this application only to track individuals who have given you explicit consent to do so (such as family members). Any unauthorized tracking or stalking using our platform is strictly prohibited and will result in an immediate permanent ban.</p><h3>2. Accuracy of Location Data</h3><p>While we strive for accuracy, GPS data depends on your device and network coverage. We are not responsible for any inaccuracies in location reporting, SOS alert failures, or missed geofence notifications.</p><h3>3. Account Responsibility</h3><p>You are responsible for keeping your circle invite codes secure. Anyone with your active invite code can join your circle and view the locations of all members inside it.</p><h3>4. Service Availability</h3><p>We do not guarantee 100% uptime of our servers. Location tracking may be interrupted by server maintenance, network drops, or device battery optimization settings.</p>"
            ]
        ]);
    }
}
