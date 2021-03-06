=== Double Opt-In for Download ===
Contributors: andyba45
Donate link: http://www.doubleoptinfordownload.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: email, download, mailchimp, aweber, constant contact, ajax, double opt in, email marketing, marketing, email capture
Requires at least: 3.5
Tested up to: 4.3
Stable tag: 2.0.7

Capture visitors names and email addresses by offering FREE downloads to your visitors in exchange for their email address with our Double Opt-In Plugin

== Description ==

The Double Opt-In for download plugin is ideal for building your email list and improving your customer outreach.

You simply upload your free download using the plugin admin interface. Once your file is uploaded, just add the generated shortcode to the pages or posts where you would like to show the sign up form.
 
When the subscriber signs up to receive your free download an email with a verification link is sent to their email address.

For the subscriber to receive the free download they must click on the link in the email. The link returns the subscriber to your website where
the plugin verifies the information and provides the subscriber with a button to retrieve the download.

A link to your download is never revealed.

<h4>Features</h4>

The free version of Double OPT-IN For Download comes with all the basic features you need to get your empire started.

<ul>

    <li>Unlimited number of products you can upload.</li>
    <li>You can offer, .jpg, .jpeg, .png, .bmp, .gif, .pdf, .zip, .doc, .docx, ,mp3 files.</li>
    <li>Subscribers email addresses are verified via a double opt in process.</li>
    <li>Create simple mailing lists.</li>
    <li>Customize your verification email to your subscriber.</li>
    <li>Subscriber can only subscribe once for each product item.</li>
    <li>One click download of email address to a convenient csv file.</li>
    <li>Links to your download are not revealed to your subscribers, keeping them secure.</li>
    <li>Track how many times your products have been downloaded.</li>
    <li>Customizable form text and form button text.</li>
    <li>Customizable Widget Form.</li>
    <li>Customizable Landing Page Button Text.</li>
    <li>Works with Multi-Sites</li>

</ul>

If your looking for something more, like AJAX forms and integrations with email list services like MailChimp, Constant Contact and AWeber, be sure to check out our <a href="http://www.doubleoptinfordownload.com/premium-double-opt-in-for-download/" target="new">Premium Version of Double OPT-IN For Download</a>

<h4>Language Support</h4>

DOIFD has been translated into the following languages:

<ul>
    <li>Dutch - nl_NL</li> 
    <li>French - fr_FR</li>
    <li>German - de_DE</li>
    <li>Italian - it_IT</li>
    <li>Portuguese (Brazil) - pt_BR</li>
    <li>Spanish - es_ES</li>
    <li>Serbian - sr_RS ( Courtesy of Ogi Djuraskovic at <a href="http://firstsiteguide.com/" target=new >FirstGuide.Com</a> )</li>
    
</ul>

<h4>Support</h4>

Double OPT-IN for Download is designed to "Set it & Forget it". But with so many different web site configurations out there hiccups are bound to occur.
For fast response on your support needs please post all questions or suggestions to our <a href="http://www.doubleoptinfordownload.com/forums/forum/double-opt-in-for-download-support/" target=new >Support Forum</a>. The Wordpress support forum is checked periodically but we will
be notified every time a support question is posted on our support forum.

== Installation ==

1. Upload the 'double-opt-in-for-download folder to the "/wp-content/plugins/" directory
2. Activate the Double Opt-In for Download plugin through the 'Plugins' menu in WordPress
3. Create a landing page for your subscribers and add this shortcode to it, [lab_landing_page]. You can change the button text in the download button by adding button_text="My Special Text" to the short code. The whole short code would look like this: [lab_landing_page button_text="My Special Text"]
4. Configure the plugin by going to the "Settings" sub-menu that appears under DOI-Download in your admin menu.
6. Upload your Free download by going to the "Downloads" sub-menu. Give your file a name, select your landing page, attach your free download, then upload it.
7. Once your file is uploaded, copy the generated shortcode and paste it into the pages and posts of your choosing or use the sidebar widget.

= Widget =

You can also use the "Double Opt-In Sign Up" widget on your pages.

After you have completed steps 1 thru 6 above:

1. Add widget to the sidebar of your choosing.
2. Add a title for your free download if you desire.
3. Select the download you would like to use in the dropdown box.
4. Save it.
5. Style the widget accordingly in the options settings.

== Frequently Asked Questions ==

Q: How do I put the form on a page or post?

A: After you have uploaded a download item for your visitors you will see a "Shortode" in the download table.
   It will look something like this: [lab_subscriber_download_form download_id=1]
   Copy it and paste it into your post or page and save the page.
   IMPORTANT: make sure you are in "TEXT" mode when using your post/page editor.

Q: How do I change the text in the form?

A: Add the following to the registration form shortcode: text="Your Text Here" Example: [lab_subscriber_download_form download_id=1 text="Your Text Here"]

Q: How do I change the submit button text in the form?

A: Add the following to the shortcode: button_text="Your Submit Button Text Here" Example: [lab_subscriber_download_form download_id=1 button_text="Your Submit Button Text Here"]

Q: How do I change the landing page download button text?

A: Add the following to the landing page shortcode: button_text="Your Submit Button Text Here" Example: [lab_landing_page button_text="Your Download Button Text Here"]

Q: Is there a way to get around the PHP file size limit for uploads?

A: Yes! 

1. Create a dummy upload using a similar type file. 
2. Give it the actual name that you want to use.
3. FTP into your site. Go to the wp-content folder, then to uploads, then to doifd_downloads. Look for the file you just uploaded. Take note of the name.
4. Rename the actual file that you want to use to the name that is in the doifd_downloads directory, then upload the file into the directory overwriting the dummy file that is in there.

That will do it.

Q: Does your plugin support languages?

A: As of 1.0.0, yes

The following is a list of languages the plugin supports:

1. Spanish - es_ES
2. French - fr_FR
3. German - de_DE
4. Italian - it_IT
5. Portuguese (Brazil) - pt_BR
6. Dutch - nl_NL
7. Serbian - sr_RS ( Courtesy of Ogi Djuraskovic at <a href="http://firstsiteguide.com/" target=new >FirstGuide.Com</a> )

Post a comment on our Facebook page if you would like to see this plugin translated to your language.

== Screenshots ==

1. This is a screen shot of the registration forms in the 2015 theme, both page/post and widget.

2. This is a screen shot of the thank you message after a user registers in the 2015 them.

3. This is a screen shot of the admin options page.

4. This is a screen shot of the download admin screen.

5. This is a screen shot of the subscribers admin screen.

== Changelog ==

= 2.0.7 =

<ul>
    <li>Updated deprecated class structure for Wordpress 4.3 compatibility</li>
</ul>

= 2.0.6 =

<ul>
    <li>Enhancement - Added Excel ( .xls, .xlsx ) to to allowed uploads/downloads</li>
    <li>Misc Tweaks<li>

</ul>


= 2.0.5 =

<ul>
    <li>Bug Fix - Updated mysql field for total number of downloads from tinyint to int. Total downloads were stopping at 127</li>
    <li>Bug Fix - Changed landing page update order so admin email is only sent once.<li>

</ul>

= 2.0.4 =

<ul>
    <li>Bug Fix - Bug that caused some downloads to not download properly on some systems</li>
    <li>Bug Fix - Warnings given when bulk delete used for downloads.</li>
</ul>

= 2.0.3 =

<ul>
    <li>Added back - Bulk function delete to Subscriber and Download tables</li>
    <li>Added back - Download Name to subscribers table</li>
    <li>Added back - Subscriber table now sortable by Name, Email, Download and time</li>
    <li>Fix - If multiple widgets selected on one sidebar only one would show.</li>
    <li>Fix - Misc PHP notices thanks to <a href="http://www.davidtan.org" target="new">David Tan!</a></li>
</ul>

= 2.0.2 =

<ul>
    <li>Added - Proper update routine</li>
</ul>

= 2.0.1 =

<ul>
    <li>Fix - Subscriber Pagination was not working</li>
</ul>

= 2.0.0 =

<ul>
    <li>Major Update - A lot of backend and interface tweaks.</li>
    <li>Added - Simple mailing list can now be created without a download.</li>
    <li>Added - Custom CSS can be added to the forms in the options panel</li>
</ul>



= 1.1.7 =

<ul>
    <li>Added Serbian (sr_RS) language files ( Courtesy of Ogi Djuraskovic at <a href="http://firstsiteguide.com/" target=new >FirstGuide.Com</a> ).</li>
    <li>Bug Fix - CSV download was not downloading subscribers if the download was deleted.
</ul>

= 1.1.6 =

<ul>
    <li>Added check box to subscriber download to remove duplicate email addresses.</li>
</ul>

= 1.1.5 =

<ul>
    <li>Fixed 500 error in multi-site when activating new site.</li>
    <li>Added Form value arrays with hook for easy manipulation.</li>
</ul>

= 1.1.4 =

<ul>
    <li>Now works with Multi-Sites</li>
    <li>Added Download File Name in CSV Download</li>
    <li>Added Verified column in subscribers to show if the user verified their email address</li>
    <li>Added Dutch Language translation - nl_NL</li>
    <li>Added Portuguese (Brazil) language translation - pt_BR</li>
    <li>Added Italian language translation - it_IT</li>
</ul>

= 1.1.3 =

<ul>
<li>Bug Fix - Fixed bug that caused the initial verification email to not send for some users with certain versions of PHP</li>
</ul>

= 1.1.2 =

<ul>
<li>Added Indicator on the upload form showing your PHP maximum file upload size limit</li>
<li>If your file exceeds your upload limit, the plugin will warn you and not let the upload proceed.</li>
<li>You will now have the ability to turn off the use of WordPress’s form nonce. The nonce is a built in security measure to protect your forms from being hijacked. It is not recommended that you disable this, but some users using caching plugins have reported issues and requested this ability.</li>
</ul>

= 1.1.1 =

<ul>
    <li>Fixed bug that caused the widget verification email to be blank</li>
</ul>

= 1.1.0 =

1. Moved the landing page selection option to the download upload form. You can now select/create a landing page for each individual download.

= 1.0.9 =

12/26/2013

1. Fixed widget background and added the ability to add ! to use with important to the widget background form color and form input fields background css fields

= 1.0.8 =

10/18/2013

1. If Privacy Page option is not used the fields are hidden.
2. Updated admin header.
3. Admin widget will now only show if an admin is logged in.

= 1.0.7 =

09/28/2013

1. Added ability to add a link to your privacy policy page in the forms.
2. Added ability to set the form font title color and size.
3. Email Subject line has been translated.

= 1.0.6 =

9/2/2013

1. Added ability to style the page and post form including adding your own css class.
2. Added optional notification to admin of download.
3. Fixed plugin activation error.
4. Corrected misspelling in Widget.
5. Added error messages for invalid or missing verification numbers.
6. Added limited stylesheet for ie8 and below.

= 1.0.5 =

07/29/2013

Fixed bug that blocked subscribers from editing their profile (Yes, a rookie move)

= 1.0.4 =

7/25/2013

1. Discovered and fixed a security issue with the download link

= 1.0.3 =

07/18/2013

1. Resolved download issue with android phones. Downloads now work in all mobile phone, tablets etc.
2. Resolved Download file name issue with Firefox.

= 1.0.2 =

07/14/2013

1. Added option to export all email addresses or just verified email addresses.
2. The name of the file that the user downloads is now the name you gave your download.
3. Added the ability to change the text in the download button.
4. Added date of download to subscriber table.
5. Added admin warning message if the Landing Page is not set in the options secion.


= 1.0.1 =

06/27/2013

1. Added German Translation - de_DE
2. Updated French Translation thanks to Laurent LEMOINE @ http://www.karma.mg.
3. Corrected some misspellings

= 1.0.0 =

1. Added ability to upload mp3 files for download.
2. Added Delete confirmations for downloads and subscribers.
2. Code enhancements for smother operation. (Fingers crossed)
3. Added Error messages to help trouble shoot problems.
4. Improved instructions throughout the pages.
5. Added Spanish (via Google Translate) language translation.
6. Fixed the form. Looked cockeyed in Firefox.

= 0.9 =

1. Added ability to style the widget in the plugin admin panel.
2. Minor code fixes.

= 0.8 =

Corrected spelling error in form

= 0.7 =

05/05/2013

1. Made an adjustment to the mime type that might help with an issue that people are having when downloading to apple products

= 0.6 =

05/04/2013

1. Added ability to change wording on the form submit button. You can now add the following option to the short code to change the button text: button_text="Your Button Text".
   Example: [lab_subscriber_download_form download_id=1 text="Your Custom Text Here" button_text="Your Button Text Here"]
2. Added the ability to change the submit button text in the widget form as well.


= 0.5 =

04/14/2013

1. Added a widget to the admin dashboard that summerizes your total subscribers and downloads.
2. Added an option to display a promotional link to plugin authors website

= 0.4 =

04/09/2013

1. Added the ability to edit downloads
2. You can now replace the current download without changing the shortcode
3. You can now change the display name of the download without changing the shortcode
4. You can now reset the download count
2. Added more support info to plugin header and updated donate nag message

= 0.3 = 

04/07/2013

Changed location of download directory to avoid deletion when upgrading. 

= 0.2 =

04/07/2013

1. Added the ability to add download subscribers to the wordpress users table.
2. Added the ability to use custom text in the subscriber registration shortcode form.
   Example: [lab_subscriber_download_form download_id=1 text="Your Custom Text Here"]
3. Added the ability to add custom text to subscriber registration widget form.
3. Correct formatting error that caused the widget to not work.


= 0.1 =

04/05/2013

* Initial beta release.

== Upgrade Notice ==

= 2.0.7 =

<ul>
    <li>Updated deprecated class structure for Wordpress 4.3 compatibility</li>
</ul>

= 2.0.6 =

<ul>
    <li>Enhancement - Added Excel ( .xls, .xlsx ) to to allowed uploads/downloads</li>
    <li>Misc Tweaks<li>

</ul>

= 2.0.5 =

<ul>
    <li>Bug Fix - Updated mysql field for total number of downloads from tinyint to int. Total downloads were stopping at 127</li>
    <li>Bug Fix - Changed landing page update order so admin email is only sent once.<li>
</ul>

= 2.0.4 =

<ul>
    <li>Bug Fix - Bug that caused some downloads to not download properly on some systems</li>
    <li>Bug Fix - Warnings given when bulk delete used for downloads.</li>
</ul>

= 2.0.3 =

<ul>
    <li>Added back - Bulk function delete to Subscriber and Download tables</li>
    <li>Added back - Download Name to subscribers table</li>
    <li>Added back - Subscriber table now sortable by Name, Email, Download and time</li>
    <li>Fix - If multiple widgets selected on one sidebar only one would show.</li>
    <li>Fix - Misc PHP notices thanks to <a href="http://www.davidtan.org" target="new">David Tan!</a></li>
</ul>

= 2.0.2 =

<ul>
    <li>Added - Proper update routine</li>
</ul>


= 2.0.1 =

<ul>
    <li>Fix - Subscriber Pagination was not working</li>
</ul>

= 2.0.0 =

<ul>
    <li>Major Update - A lot of backend and interface tweaks.</li>
    <li>Added - Simple mailing list can now be created without a download.</li>
    <li>Added - Custom CSS can be added to the forms in the options panel</li>
</ul>

= 1.1.7 =

<ul>
    <li>Added Serbian (sr_RS) language files ( Courtesy of Ogi Djuraskovic at <a href="http://firstsiteguide.com/" target=new >FirstGuide.Com</a> )</li>
    <li>Bug Fix - CSV download was not downloading subscribers if the download was deleted.
</ul>

= 1.1.6 =

<ul>
    <li>Added check box to subscriber download to remove duplicate email addresses.</li>
</ul>

= 1.1.5 =

<ul>
    <li>Fixed 500 error in multi-site when activating new site.</li>
    <li>Added Form value arrays with hook for easy manipulation.</li>
</ul>

= 1.1.4 =

<ul>
    <li>Now works with Multi-Sites</li>
    <li>Added Download File Name in CSV Download</li>
    <li>Added Verified column in subscribers to show if the user verified their email address</li>
    <li>Added Dutch Language translation - nl_NL</li>
    <li>Added Portuguese (Brazil) language translation - pt_BR</li>
    <li>Added Italian language translation - it_IT</li>
    <li>Improved Translations for Spanish (es_ES), French (fr_FR) & German (de_DE)
</ul>


= 1.1.3 =

<ul>
<li>Bug Fix - Fixed bug that caused the initial verification email to not send for some users with certain versions of PHP</li>
</ul>

= 1.1.2 =

<ul>
<li>Added Indicator on the upload form showing your PHP maximum file upload size limit</li>
<li>If your file exceeds your upload limit, the plugin will warn you and not let the upload proceed.</li>
<li>You will now have the ability to turn off the use of WordPress’s form nonce. The nonce is a built in security measure to protect your forms from being hijacked. It is not recommended that you disable this, but some users using caching plugins have reported issues and requested this ability.</li>
</ul>

= 1.1.1 =

Fixed bug that caused the widget verification email to be blank.

= 1.1.0 =

PLEASE READ, OTHERWISE YOU MAY FREAK OUT!! The landing page option has been moved from the general options page to the "Upload Download Form". You DO NOT need to do anything right away. The plugin
will continue to use your old landing page until you update your download on the downloads page in the plugin options. To assign a landing page to your download, go to your download and click "Edit" under the name.
When the page loads, select your landing page and save. That's all there is to it.

10/18/2013

1. If Privacy Page option is not used the fields are hidden.
2. Updated admin header.
3. Admin widget will now only show if an admin is logged in.

= 1.0.7 =

1. Added ability to add a link to your privacy policy page in the forms.
2. Added ability to set the form font title color and size.
3. Email Subject line has been translated.

= 1.0.6 =

1. Added ability to style the page and post form including adding your own css class
2. Added optional notification to admin of download.
3. Fixed plugin activation error.
4. Corrected misspelling in Widget.
5. Added error messages for invalid or missing verification numbers.
6. Added limited stylesheet for ie8 and below.

= 1.0.5 =

Fixed bug that blocked subscribers from editing their profile (Yes, a rookie move)

= 1.0.4 =

1. Discovered and fixed a security issue with the download link

= 1.0.3 =

1. Resolved download issue with android phones. Downloads now work in all mobile phone, tablets etc.
2. Resolved Download file name issue with Firefox.

= 1.0.2 =

Added 3 User suggested improvements:
1. Added option to export all email addresses or just verified email addresses.
2. The name of the file that the user downloads is now the name you gave your download.
3. Added the ability to change the text in the download button.
4. Added date of download to subscriber table.

= 1.0.1 =

In this upgrade German Translation - de_DE has been added. French Translation has been updated thanks to Laurent LEMOINE @ http://www.karma.mg and some misspellings have been corrected.
