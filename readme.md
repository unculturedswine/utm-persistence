# Site Index

Automatically persists UTM and marketing tracking parameters across page loads and domains. When visitors land on your site with UTM parameters, this plugin ensures those parameters follow them throughout their session, keeping your marketing attribution intact.

---

=== UTM Persistence ===
Contributors: Kinda Useful Plugins
Tags: utm, tracking, analytics, marketing, parameters
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Persists UTM and tracking parameters across domains during a user session.

== Description ==

UTM Persistence helps maintain marketing tracking parameters (UTM parameters) and other tracking codes across page loads and domains. When a user lands on your site with UTM parameters, this plugin ensures those parameters are appended to specified domain links throughout their session.

Features:

* Configure multiple domains to track
* Specify custom tracking parameters beyond standard UTM codes
* Easy-to-use admin interface
* Lightweight and performance optimized
* Works with dynamic content via MutationObserver

Default tracking parameters include:

* utm_source
* utm_medium
* utm_campaign
* utm_adgroup
* utm_content
* utm_term
* gclid (Google Click ID)
* fbclid (Facebook Click ID)
* refer

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings > UTM Persistence to configure domains and parameters
4. Add the domains you want to track (without https:// or trailing slashes)
5. Customize tracking parameters as needed

== Configuration ==

After activation:

1. Go to Settings > UTM Persistence
2. Add domains you want to decorate with UTM parameters
3. Add or remove tracking parameters as needed
4. Save your settings

== Frequently Asked Questions ==

= Does this work with AJAX-loaded content? =

Yes, the plugin uses MutationObserver to handle dynamically added links.

= Can I track custom parameters? =

Yes, you can add any query parameter you want to track through the admin interface.

= Does this slow down my site? =

No, the JavaScript is lightweight and only runs on the frontend when configured domains are present.

== Changelog ==

= 1.0.1 =
* Updated WordPress tested version to 6.7
* Auto-update support

= 1.0.0 =
* Initial release

== License ==

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
