=== Clean My Archives ===

Contributors: greenshady
Donate link: http://themehybrid.com/donate
Tags: archives, shortcode
Requires at least: 3.1
Tested up to: 3.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An easy-to-use shortcode for displaying post archives on your site.

== Description ==

The Clean My Archives plugin was developed because so many archives plugins were overly complicated.  I wanted something extremely simple to use that simply got the job done on my own [site's archives](http://justintadlock.com/archives).

Therefore, I created a simple `[clean-my-archives]` shortcode that you can place on any page (or any shortcode-ready area) and list your post archives by month and year.

This plugin also integrates with WordPress caching plugins.  So, long lists of archives can be cached for later use and loaded quickly.  If you have many years of blog posts, I highly recommend some sort of persistent caching or paginating your archives (see FAQ).

### Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/support), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 60,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/clean-my-archives). 

### Donations

Yes, I do accept donations.  If you want to buy me a beer or whatever, you can do so from my [donations page](http://themehybrid.com/donate).  I appreciate all donations, no matter the size.  Further development of this plugin is not contingent on donations, but they are always a nice incentive.

== Installation ==

1. Uzip the `clean-my-archives.zip` folder.
2. Upload the `clean-my-archives` folder to your `/wp-content/plugins` directory.
3. In your WordPress dashboard, head over to the *Plugins* section.
4. Activate *Clean My Archives*.

== Frequently Asked Questions ==

### Why was this plugin created?

First and foremost, I wanted a simple archives solution for, what's now, over 10 years of blog posts.  You can check out [my archives](http://justintadlock.com/archives) to see how the plugin performs.

The second reason was to share my solution with other users who want the same simplicity.

### How do I use it?

Add the `[clean-my-archives]` shortcode to a shortcode-ready area, such as the page editor. That's it.  You have nothing more to do.

Of course, there are some other configuration options. Examples follow.

#### Limit the number of posts with the `limit` parameter:

	[clean-my-archives limit="100"]
	
#### Reverse the order with the `order` parameter (default is `DESC`):
	
	[clean-my-archives order="ASC"]

	[clean-my-archives order="DESC"]

#### Use the `year` parameter to limit to a year:

	[clean-my-archives year="2013"]

#### Use the `month` parameter to limit by month:

	[clean-my-archives month="12"]

#### Load specific post types with the `post_type` parameter

	[clean-my-archives post_type="post"]

	[clean-my-archives post_type="post, page"]

### Does it support pagination?

Sort of.  Technically, it doesn't.  However, WordPress pages support pagination.  So, you could enter the following in your page editor to paginate by year.

	[clean-my-archives year="2013"]

	<!-- nextpage -->

	[clean-my-archives year="2012"]

	<!-- nextpage -->

	[clean-my-archives year="2011"]

== Screenshots ==

You can see a [live demo here](http://justintadlock.com/archives).

1. Screenshot of the plugin in action my archives page.

== Changelog ==

To view the change log, look into the `changelog.md` file included with the plugin or visit the [online change log](https://github.com/justintadlock/clean-my-archives/blob/master/changelog.md).