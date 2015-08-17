=== Emma Emarketing Plugin for WordPress ===
Contributors: ahsodesigns
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q7FRK4XEF8EAS
Tags: Plugin, Emma, MyEmma, emarketing, form, custom, api, widget, shortcode, subscription
Author URI: http://ahsodesigns.com
Plugin URI: http://www.ahsodesigns.com/products/emma-emarketing-plugin/
Requires at least: 3.1
Tested up to: 3.4.1
Stable tag: 1.0.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The Emma Emarketing plugin allows you to quickly and easily add a signup form for your Emma list as a widget or a shortcode on your WordPress 3.0 or higher site.

After the plugin is installed, the setup page will guide you through entering your Emma API login information, selecting your group, setting up the fields for your form, customizing the form, and then adding the Widget to your site. The great news is that this whole process will take less than five minutes and everything can be done via the WordPress Dashboard Setting GUI – no file editing at all!

To see the plugin at work, visit: http://ahsodesigns.com/products/emma-emarketing-plugin/

There is also an instructional video on YouTube to guide you through the process: http://www.youtube.com/watch?v=e2vjCk5E4Tg


== Installation ==

<strong>About the plugin:</strong>

The Emma Emarketing plugin allows you to quickly and easily add a signup form for your Emma list as a widget on your WordPress 3.0 or higher site.

After the plugin is installed, the setup page will guide you through entering your Emma API login information, selecting your group, setting up the fields for your form, customizing the form, and then adding the Widget to your site. The great news is that this whole process will take less than five minutes and everything can be done via the WordPress Dashboard Setting GUI – no file editing at all!

&nbsp;

<strong>USAGE DOCS</strong>

&nbsp;

Login to the Emma Dashboard from http://myemma.com/login/

<strong>ACCOUNT INFORMATION TAB</strong>

<strong>Account Login Information:</strong>

Click on 'Account &amp; billing' in the upper right hand of your Emma dashboard. This will take you to your “Manage your account settings” page.  In the Account settings section, the forth tab is <strong>API key</strong>.  Click on Generate new key to create your API key.

Once you create the key, you will need to copy your account number, public api key, and private api key into the corresponding fields in the plugin.

The plugin will now be able to connect your WordPress site to your Emma account.  You may now assign a group to hold the email addresses that you capture from your form.

<strong> </strong>

<strong>FORM SETUP</strong>

<strong>Include fields</strong> are the information that you can capture from users who submit the form.  This information is captured and then put into the Emma group you specified in the account information tab.

<strong>Form size</strong> includes four default sizes are included to be used on your sidebar widget area.

<strong>Form placeholders</strong> is where your default text goes for the fields on the form.

<strong>Custom Confirmation Email Message</strong> you can specify a short email message to be sent to the subscriber.

<strong>Give props</strong> is where you can choose whether or not to display the Emma logo on your site.  The default setting is no.

&nbsp;

<strong>FORM CUSTOMIZATION</strong>

<strong>Form fields</strong> are the border width, color, border type, text color and background color of the individual fields the form.

<strong>Submit button</strong> are the settings for the submit button on the form.

<strong>Submit button hover state</strong> are the settings for the hover property of the submit button

&nbsp;

<strong>DISPLAYING THE FORM ON YOUR SITE</strong>

To insert the form as a <strong>widget</strong> on your sidebar, go to Appearance -> Widgets and then move the “Emma Emarketing Subscription Form” to the widget area where you want the form to appear.

To insert the form as a <strong>shortcode</strong> within your site, insert &#91; emma_form &#93; within your text editor where you want the form to appear.

== Screenshots ==

1. This is the Account Information tab of the plugin settings, here you enter your account keys and account number, then select the group you wish to add members to.
2. This is the Form Setup tab of the plugin settings, here you configure the form's output on your site, you can also choose to add a stylish emma logo to your form, and share some love.
3. This is the Form Customization tab of the plugin settings, here you can style your form, choose colors, border types, and so on.
4. This is the Help tab of the plugin settings, it contains instructions on how to get up and running with your new Emma Emarketing WordPress Plugin.

== Frequently Asked Questions ==

1. How do I put the form on my website?
   - Once you have activated the plugin, setup your Emma account to work with their new API, and configured your Emma Emarketing Settings, navigate to Appearance->Widgets. This plugin comes with a widget called 'Emma Emarketing Subscription Form'. The Widgets are listed in alphabetical order.

2. How do I add memnbers to a specific group?
   - Once you've configured the plugin, and entered your account ID, Private and Public API keys,
   a select menu with the available groups for your account will show at the bottom of the account information tab
   on the Emma Emarketing Settigns page in the Wordpress admin.
   simply select the group you want members to be added to and click 'Save'.

3. How do i use the shortcode?
   - If you only want the form on a single page, you can use the shortcode, simply type [emma_form] in the HTML view in the post editor, for more information on shortcodes, check the almighty codex: [Shortcodes](http://codex.wordpress.org/Shortcode" WordPress Codex - Shortcodes")

== Upgrade Notice ==

2. This Plugin requires Wordpress version 3.1 and above
3. This Plugin requires PHP version 5.2.6, as it uses json_encode with integers in the Emma_API class

== Changelog ==

- v 1.0.5 - added confirmation email message, nomenclature updates, Emma_API class fixes, relegated error handling to object making the call, Emma_API fits the adapter pattern better now. more bugfixes.

- v 1.0.4 - bugfixes, updated readme.txt

- v 1.0.3 - fixed accidental php short tag, ( tyty @avioli ), updated readme.txt, spelling errors, and nomenclature updates.

- v 1.0.2 - cleaned up OOP structure. switched to WP naming conventions, fixed bug where users weren't being assigned to groups,

- v 1.0.1 - typed active group_id as integer for uptake to Emma. Emma required group_ids submitted as an array of integers. in older versions of PHP json_encode types integers as strings.

- v 1.0 - it's stable. It needs some cleaning, but it flies, and flies well.
