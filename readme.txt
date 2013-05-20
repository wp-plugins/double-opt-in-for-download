=== Double Opt-In for Download ===
Contributors: Andy Bates / LAB Web Designs
Donate link: http://www.labwebdesigns.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: email, download
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 0.9

Improve your client outreach by offering FREE downloads to your visitors in exchange for their email address with our Double Opt-In Plugin

== Description ==

The Double Opt-In for download plugin is ideal for improving your customer out reach.

You simply upload your free download using the plugin admin interface. Once your file is uploaded, just add the generated shortcode to the pages or posts where you would like to show the sign up form.
 
When the subscriber signs up to receive your free download an email with a verification link is sent to their email address.

For the subscriber to receive the free download they must click on the link in the email. The link returns the subscriber to your website where
the plugin verifies the information and provides the subscriber with a button to retrieve the download.

A link to your download is never revealed.


== Installation ==

1. Upload the 'double-opt-in-for-download folder to the "/wp-content/plugins/" directory
2. Activate the Double Opt-In for Download plugin through the 'Plugins' menu in WordPress
3. Create a landing page for your subscribers and add this shortcode to it, [lab_landing_page].
3. Configure the plugin by going to the "Settings" sub-menu that appears under DOI-Download in your admin menu. Don't forget to select your landing page!!
4. Upload your Free download by going to the "Downloads" sub-menu. Give your file a name, attach it, then upload it.
5. Once your file is uploaded, copy the generated shortcode and paste it into the pages and posts of your choosing.

= Widget =

You can also use the "Double Opt-In Sign Up" widget on your pages.

After you have completed steps 1 thru 5 above:

1. Add widget to the sidebar of your choosing.
2. Add a title for your free download if you desire.
3. Select the download you would like to use in the dropdown box.
4. Save it.

== Frequently Asked Questions ==

Q: How do I put the form on a page or post?

A: After you have uploaded a download item for your visitors you will see a "Shortode" in the download table.
   It will look something like this: [lab_subscriber_download_form download_id=1]
   Copy it and paste it into your post or page and save the page.
   IMPORTANT: make sure you are in "TEXT" mode when using your post/page editor.

Q: How do I change the text in the form?

A: Add the following to the shortcode: text="Your Text Here" Example: [lab_subscriber_download_form download_id=1 text="Your Text Here"]

Q: How do I change the submit button text in the form?

A: Add the following to the shortcode: button_text="Your Submit Button Text Here" Example: [lab_subscriber_download_form download_id=1 button_text="Your Submit Button Text Here"]

Q: Is there a way to get around the file size limit for uploads?

A: Yes! 

1. Create a dummy upload using a similar type file. 
2. Give it the actual name that you want to use.
3. FTP into your site. Go to the wp-content folder, then to uploads, then to doifd_downloads. Look for the file you just uploaded. Take note of the name.
4. Rename the actual file that you want to use to the name that is in the doifd_downloads directory, then upload the file into the directory overwriting the dummy file that is in there.

That will do it.

== Screenshots ==

1. This is a screen shot of the registration forms, both page/post and widget.

2. This is a screen shot of the thank you message after a user registers.

3. This is a screen shot of the admin options.

4. This is a screen shot of the download admin screen.

5. This is a screen shot of the subscribers admin screen.

== Changelog ==

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

The ability to style the widget has now been added in the setting section.