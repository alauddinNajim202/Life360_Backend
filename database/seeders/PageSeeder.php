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
                "name"    => "FAQ",
                "slug"    => "faq",
                "title"   => "Frequently Asked Questions – Stay Close",
                "content" => "
<h2>Frequently Asked Questions</h2>

<p><strong>1. What is Stay Close – Family Tracker?</strong><br>
Stay Close is a real-time family location sharing app that helps you stay connected with the people you love. You can share your location, set safe places, send SOS alerts, and track family members inside your private Circle.</p>

<p><strong>2. How do I create a Circle?</strong><br>
Open the app, go to the Circles tab, and tap 'Create Circle'. Give it a name (e.g., 'My Family') and share the invite code with your family members.</p>

<p><strong>3. How do I invite members to my Circle?</strong><br>
Inside your Circle, tap 'Invite' to generate a unique code. Share this code via SMS, WhatsApp, or any messaging app. Members join by entering the code in the app.</p>

<p><strong>4. Can I track someone without their knowledge?</strong><br>
No. Stay Close requires every user to install the app and join a Circle voluntarily. We strictly prohibit unauthorized tracking of any individual.</p>

<p><strong>5. How accurate is the location tracking?</strong><br>
Location accuracy depends on your device's GPS, Wi-Fi, and network signal. In open areas, accuracy is typically within 10–20 meters.</p>

<p><strong>6. Does the app work in the background?</strong><br>
Yes. Stay Close uses background location services to keep your location updated even when the app is not actively open. Please ensure background app permissions are enabled.</p>

<p><strong>7. What is a Safe Place (Geofence)?</strong><br>
A Safe Place is a geographic zone (e.g., Home, School) you define on the map. When a family member enters or exits a Safe Place, all Circle members receive a notification.</p>

<p><strong>8. What is an SOS alert?</strong><br>
An SOS alert is an emergency notification. When you trigger SOS, all members of your Circle are immediately notified with your real-time location.</p>

<p><strong>9. How do I cancel my subscription?</strong><br>
You can cancel your subscription at any time through the App Store (iOS) or Google Play Store (Android). Your access remains active until the end of the current billing period.</p>

<p><strong>10. How do I delete my account?</strong><br>
Go to Profile → Settings → Delete Account. This permanently removes all your data, location history, and Circle memberships from our servers.</p>
"
            ],
            [
                "name"    => "Privacy Policy",
                "slug"    => "privacy-policy",
                "title"   => "Privacy Policy – Stay Close Family Tracker",
                "content" => "
<h2>Privacy Policy</h2>
<p><em>Last Updated: November 10, 2025</em></p>

<p>Welcome to <strong>Stay Close – Family Tracker</strong>. We are committed to protecting your privacy and ensuring the security of your personal and location data. This Privacy Policy explains what information we collect, how we use it, and your rights regarding your data.</p>

<p>By using Stay Close, you agree to the collection and use of information in accordance with this policy.</p>

<h3>1. Information We Collect</h3>
<p>We collect the following types of information when you use our app:</p>
<ul>
  <li><strong>Account Information:</strong> Your name, email address, phone number, and profile photo.</li>
  <li><strong>Location Data:</strong> Real-time GPS coordinates of your device, updated periodically while you are a member of a Circle.</li>
  <li><strong>Device Information:</strong> Device type, operating system version, unique device identifiers, and push notification tokens.</li>
  <li><strong>Usage Data:</strong> App interactions, check-ins, SOS alert history, geofence events, and Circle activity logs.</li>
  <li><strong>Payment Information:</strong> Subscription status and transaction identifiers processed securely through RevenueCat, Apple App Store, or Google Play Store. We do not store your credit card or payment details directly.</li>
</ul>

<h3>2. How We Use Your Information</h3>
<ul>
  <li>To provide real-time location sharing within your private Circle.</li>
  <li>To send geofence (Safe Place) entry/exit notifications to Circle members.</li>
  <li>To deliver SOS emergency alerts to your Circle.</li>
  <li>To manage your account, subscription, and app settings.</li>
  <li>To improve app performance and fix technical issues.</li>
  <li>To send important service-related notifications (we do not send unsolicited marketing messages).</li>
</ul>

<h3>3. How We Share Your Information</h3>
<p>We <strong>do not sell</strong> your personal data or location information to any third party. We only share your data in the following limited circumstances:</p>
<ul>
  <li><strong>Within your Circle:</strong> Your location and activity are visible only to other members of the Circles you have joined.</li>
  <li><strong>Service Providers:</strong> We use trusted third-party services (e.g., AWS for hosting, RevenueCat for subscriptions, Firebase for push notifications) solely to operate the app. These providers are bound by strict data processing agreements.</li>
  <li><strong>Legal Requirements:</strong> We may disclose information if required by law or to protect the safety of our users.</li>
</ul>

<h3>4. Location Data</h3>
<p>Location data is the core feature of Stay Close. We collect your device's GPS coordinates only while you are an active member of a Circle. You can pause location sharing at any time from within the app. We do not share your location with anyone outside your Circles.</p>

<h3>5. Data Retention</h3>
<p>We retain your data for as long as your account is active. Location history is retained for a limited period for Circle activity logs. When you delete your account, all personal data, location history, and Circle memberships are permanently and irreversibly deleted from our servers within 30 days.</p>

<h3>6. Data Security</h3>
<ul>
  <li>All data transmitted between the app and our servers is encrypted using <strong>TLS/SSL</strong>.</li>
  <li>Passwords are stored using industry-standard <strong>bcrypt hashing</strong>.</li>
  <li>Circle access is protected by <strong>unique invite codes</strong>.</li>
  <li>We conduct regular security reviews of our infrastructure.</li>
</ul>

<h3>7. Children's Privacy</h3>
<p>Stay Close is not directed to children under the age of 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately at <a href='mailto:support@staycloseapp.com'>support@staycloseapp.com</a> and we will delete the information promptly.</p>

<h3>8. Your Rights</h3>
<p>Depending on your location, you may have the following rights regarding your personal data:</p>
<ul>
  <li><strong>Access:</strong> Request a copy of the data we hold about you.</li>
  <li><strong>Correction:</strong> Update inaccurate or incomplete information from your profile settings.</li>
  <li><strong>Deletion:</strong> Delete your account and all associated data at any time from app settings.</li>
  <li><strong>Opt-Out:</strong> Pause location sharing at any time within the app.</li>
</ul>

<h3>9. Third-Party Services</h3>
<p>Stay Close integrates with the following third-party services, each governed by their own privacy policies:</p>
<ul>
  <li>Google Maps / Apple Maps (for map display)</li>
  <li>Firebase (for push notifications)</li>
  <li>RevenueCat (for subscription management)</li>
  <li>Amazon Web Services (for cloud infrastructure)</li>
</ul>

<h3>10. Changes to This Policy</h3>
<p>We may update this Privacy Policy from time to time. We will notify you of any significant changes via in-app notification or email. Continued use of Stay Close after changes means you accept the revised policy.</p>

<h3>11. Contact Us</h3>
<p>If you have any questions or concerns about this Privacy Policy, please contact us:</p>
<p>
  <strong>Stay Close – Family Tracker</strong><br>
  Email: <a href='mailto:support@staycloseapp.com'>support@staycloseapp.com</a>
</p>
"
            ],
            [
                "name"    => "Terms and Conditions",
                "slug"    => "terms-and-conditions",
                "title"   => "Terms and Conditions – Stay Close Family Tracker",
                "content" => "
<h2>Terms and Conditions</h2>
<p><em>Last Updated: November 10, 2025</em></p>

<p>Welcome to <strong>Stay Close – Family Tracker</strong>. These Terms and Conditions ('Terms') govern your use of the Stay Close mobile application and related services ('Service'). By downloading, installing, or using our app, you agree to be bound by these Terms.</p>
<p>Please read these Terms carefully. If you do not agree with any part of these Terms, you must not use the Service.</p>

<h3>1. Acceptance of Terms</h3>
<p>By creating an account and using Stay Close, you confirm that you are at least 13 years of age (or the minimum legal age in your jurisdiction), and that you accept and agree to these Terms and our Privacy Policy.</p>

<h3>2. Description of Service</h3>
<p>Stay Close is a real-time family location sharing application that allows users to:</p>
<ul>
  <li>Share their GPS location with members of their private Circles.</li>
  <li>Receive entry/exit notifications for defined Safe Places (Geofences).</li>
  <li>Send and receive SOS emergency alerts.</li>
  <li>Communicate and check-in with family members.</li>
</ul>

<h3>3. Consent and Authorized Use</h3>
<p>Stay Close is designed <strong>exclusively</strong> for consensual location sharing among family members, friends, or other groups where every member has voluntarily joined. You agree to:</p>
<ul>
  <li>Only add individuals to Circles who have given you their <strong>explicit, informed consent</strong> to share their location.</li>
  <li>Never use Stay Close to track, monitor, or stalk any individual without their full knowledge and consent.</li>
  <li>Not use the app for any illegal surveillance, harassment, or stalking activity.</li>
</ul>
<p>Violation of this clause will result in <strong>immediate and permanent account termination</strong> and may be reported to relevant law enforcement authorities.</p>

<h3>4. Account Registration</h3>
<ul>
  <li>You must provide accurate and complete information when registering.</li>
  <li>You are solely responsible for maintaining the confidentiality of your account credentials.</li>
  <li>You are responsible for all activities that occur under your account.</li>
  <li>Notify us immediately at <a href='mailto:support@staycloseapp.com'>support@staycloseapp.com</a> if you suspect unauthorized use of your account.</li>
</ul>

<h3>5. Circles and Invite Codes</h3>
<ul>
  <li>A Circle is a private group within which members can view each other's locations.</li>
  <li>As a Circle creator, you are responsible for who you share your invite code with.</li>
  <li>Anyone possessing an active invite code can join your Circle and view all members' locations.</li>
  <li>Regenerate your invite code immediately if you believe it has been shared without your authorization.</li>
</ul>

<h3>6. Subscriptions and Payments</h3>
<ul>
  <li>Stay Close offers a free tier with limited features and a premium subscription with full access.</li>
  <li>Subscriptions are billed on a monthly or annual basis and are managed through the Apple App Store or Google Play Store.</li>
  <li>Subscription fees are charged to your App Store / Play Store account upon confirmation of purchase.</li>
  <li>Subscriptions <strong>automatically renew</strong> unless cancelled at least 24 hours before the end of the current billing period.</li>
  <li>You may cancel your subscription at any time through your App Store or Play Store account settings. Access to premium features continues until the end of the billing period.</li>
  <li><strong>No refunds</strong> are issued for the unused portion of a subscription period, except where required by applicable law.</li>
</ul>

<h3>7. Location Data and Accuracy</h3>
<ul>
  <li>Stay Close relies on your device's GPS, Wi-Fi, and network signal to determine location. We do not guarantee 100% accuracy of location data.</li>
  <li>We are not liable for any decisions made based on inaccurate location information.</li>
  <li>Location tracking may be interrupted by poor network connectivity, device battery saver modes, or OS-level background restrictions.</li>
</ul>

<h3>8. SOS Alerts</h3>
<ul>
  <li>Stay Close's SOS feature is a notification tool and is <strong>not a substitute</strong> for emergency services (911, 999, 112, or your local emergency number).</li>
  <li>Always contact your local emergency services in a life-threatening situation.</li>
  <li>We are not liable for any failure of SOS alerts to be delivered due to network issues, device settings, or any other technical reason.</li>
</ul>

<h3>9. Prohibited Conduct</h3>
<p>You agree NOT to:</p>
<ul>
  <li>Use Stay Close for stalking, harassment, or any form of illegal surveillance.</li>
  <li>Attempt to reverse engineer, decompile, or hack the app or its servers.</li>
  <li>Use the app to distribute spam, malware, or any harmful content.</li>
  <li>Create multiple accounts to circumvent bans or restrictions.</li>
  <li>Misrepresent your identity or impersonate another person.</li>
</ul>

<h3>10. Service Availability and Modifications</h3>
<ul>
  <li>We strive for high availability but do <strong>not guarantee</strong> uninterrupted or error-free service.</li>
  <li>We reserve the right to modify, suspend, or discontinue any part of the Service at any time with reasonable notice.</li>
  <li>We are not liable for any loss or inconvenience caused by service interruptions.</li>
</ul>

<h3>11. Intellectual Property</h3>
<p>All content, features, designs, logos, and code within the Stay Close app are the intellectual property of Stay Close and are protected by applicable copyright and trademark laws. You may not copy, reproduce, or distribute any part of the app without our prior written consent.</p>

<h3>12. Disclaimer of Warranties</h3>
<p>Stay Close is provided on an 'as is' and 'as available' basis without warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>

<h3>13. Limitation of Liability</h3>
<p>To the maximum extent permitted by law, Stay Close and its creators shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of or inability to use the Service, even if advised of the possibility of such damages.</p>

<h3>14. Termination</h3>
<p>We reserve the right to suspend or terminate your account at our discretion, without prior notice, if you are found to be in violation of these Terms. You may also delete your account at any time from app settings.</p>

<h3>15. Governing Law</h3>
<p>These Terms are governed by and construed in accordance with applicable laws. Any disputes arising from these Terms or your use of Stay Close shall be resolved through binding arbitration or the courts of the applicable jurisdiction.</p>

<h3>16. Changes to These Terms</h3>
<p>We may update these Terms from time to time. We will notify you of material changes via in-app notification or email. Continued use of Stay Close after the effective date of changes constitutes your acceptance of the revised Terms.</p>

<h3>17. Contact Us</h3>
<p>If you have any questions about these Terms, please contact us:</p>
<p>
  <strong>Stay Close – Family Tracker</strong><br>
  Email: <a href='mailto:support@staycloseapp.com'>support@staycloseapp.com</a>
</p>
"
            ]
        ]);
    }
}
