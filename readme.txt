=== Plugin Name ===
Contributors: chungyc
Donate link: http://chungyc.org/software/friend-connect-activities/donate
Tags: google friend connect, google, social, integration
Requires at least: 2.8
Tested up to: 2.8.4
Stable tag: 1.0

Post activities to Google Friend Connect without requiring a matching local account.

== Description ==

Embedding [Google Friend Connect][google friend connect] into a WordPress site is like having two independent worlds on the same website: neither Google Friend Connect nor WordPress knows what is happening on the other side.  This plugin attempts to narrow the gap by posting WordPress activities to Google Friend Connect.

Whenever a signed-in Google Friend Connect user does any of the following, this plugin will update the user's Friend Connect activities correspondingly:

* A post is published.
* A comment is written.
* A link is added.

This plugin does not link Google Friend Connect users with local WordPress accounts.  This makes it ideal for sites which do not wish to create local accounts for every Google Friend Connect visitor but still want some more interaction between WordPress and Google Friend Connect.

[google friend connect] : http://www.google.com/friendconnect/

== Installation ==

Makes sure that your site has already been configured with [Google Friend Connect][google friend connect].  Appropriate members or signup widgets should also be included (this can be done by manually inserting the corresponding code or using plugins such as [Google Friend Connect Integration][gfci] or [Google Friends Connect Widget][gfcw]).

Once Google Friend Connect has been set up for your site, the 'Activities for Google Friend Connect' plugin can be installed and configured:

1. Upload the `activities-for-google-friend-connect` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure the Friend Connect Site ID in the 'GFC Activities' section of the 'Settings' menu.  The site ID can be found on the overview page for your site on [Google Friend Connect][google friend connect].

[google friend connect] : http://www.google.com/friendconnect/
[gfci]: http://wordpress.org/extend/plugins/google-friend-connect-integration/
[gfcw]: http://wordpress.org/extend/plugins/google-friendsconnect-widget/

== Frequently Asked Questions ==

= Are local accounts created for Google Friend Connect users? =

No.  One of the goals of this plugin was to post activities to Google Friend Connect without having to create corresponding local accounts, so it uses authentication cookies to post activities.

= Are names and websites filled in automatically? =

No.  Commenters will still have to fill them in manually, although it is possible that they could be extracted from the Friend Connect profile automatically in the future.

== Screenshots ==

1. Publishing activity posted through the plugin.
2. Just set the site ID for a site already configured for Google Friend Connect.

== Changelog ==

= 1.0 =
* Add support for localization.
* Shorten options menu title to 'GFC Activities'.

= 0.02 =
* `readme.txt` update

= 0.01 =
* Initial version.

== Implementation notes ==

* This plugin uses `gfca` to prefix all functions.  (Abbreviation of 'Google Friend Connect Activities'.)
* `gfca` is also used as the options and localization domains.
