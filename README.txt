=== Added to cart notification for WooCommerce ===
Contributors: nevma
Tags: woocommerce, add to cart, notification, cart,
Requires at least: 4.0
Tested up to: 4.7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a brief, dismissable, responsive notification on the front-end, whenever a product is added to the cart.

== Description ==

*Added to cart notification for WooCommerce* plugin provides visual feedback to the user, when they add a product to the cart. Does so by displaying a dismissable notification, at the bottom of the screen.

= Notification contents =

So far, the notification includes a mini-cart and a cross-sells section. However, the notification's contents can be extensively customised by themes and/or other plugins.

= Admin options area =
* There is no admin options page for the plugin, yet. It will be added in a future release. *
Altering the plugin's native behaviour *will* require a little bit of code. See below for more information.

= Customizing the notification =

There are two ways one can customise the notification:

1. Via the theme, by overriding the included templates (similar to WooCommerce's template override mechanism).
2. Programmatically, via the theme or a plugin, by using the action and filter hooks exposed by the plugin. Note that the plugin itself uses these action hooks in order to display its default output, thus providing maximum flexibility.

See the F.A.Q. section for more info.

= Styling the notification =

The plugin does its best to "play nice" with the theme, by using native WooCommerce markup as much as possible, and limiting explicit styling to the bare minimum. As a result, the notification&apo;s look &amp; feel shouldn't deviate noticeably from that of WooCommerce's.

However, one can customise the styling to the extent required, by overriding the plugin's native CSS.

= Responsiveness =

== Installation ==

Nothing out of the ordinary here, really!

1. Install the plugin via "Plugins &gt; Add New".
2. Activate it.

That's it! No options are available up to this version.

De-activate the plugin to disable it. Nothing gets stored to the database, up to this version.

== Frequently Asked Questions ==

= Wher are sections? =

By &quote;section&quote; we refer to an area inside the notification. The mini-cart or the cross-sells are such sections.

= How can I hide a section? =

An answer to that question.

= How can I add a custom section? =

Answer to foo bar dilemma.

= Can I display sections in a custom order? =

= How can I change the number of products in the cross-sells section? =

= How can I change the number of columns in which the cross-sells are split? =

== Screenshots ==

== Changelog ==

= 1.0.0 =

* The first stable version of the plugin.
* Impemented basic functionality and styling.