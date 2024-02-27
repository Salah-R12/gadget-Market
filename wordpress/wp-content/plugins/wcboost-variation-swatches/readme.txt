=== WCBoost - Variation Swatches ===
Contributors: wcboost
Tags: woocommerce, product attribute, product color, product size, variation swatches, variable products
Tested up to: 6.4
Stable tag: 1.0.15
Requires PHP: 7.0
Requires at least: 4.5
WC requires at least: 3.0.0
WC tested up to: 8.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html


Enhanced shopping experience with our WooCommerce Variation Swatches plugin. Say goodbye to boring dropdowns and upgrade them to aesthetically beautiful swatches that conveniently highlight product variants. Easily navigate options, explore variations, and make informed decisions, all while adding a touch of elegance to the online shopping journey.

== Description ==

WCBoost - Variation Swatches plugin provides a much nicer way to display variations of variable products. This plugin will help you to set the style for each attribute as color, image, label, or button. With this plugin, you can present product colors, sizes, styles, and many other things in a better way that is not supported by WooCommerce.
This plugin only adds more options to show product variations with swatches. It doesn't touch the default drop-down style of WooCommerce.

With a friendly and easy-to-use interface, you can add a default color, image, or label to each attribute on the attributes management page. It can also help you pick the right style for quick-add attributes right inside the editing product page.

== Why store owners and developers choose this plugin ==

This plugin truly cares about your website. It is not only developed to add functionality to your store but also to care about the performance, SEO score, and your customers. This plugin is carefully developed by experienced developers.

1. It works with all themes. No more customization, it will work with your theme in the first run. With the API this plugin provides, theme developers are also able to make it more beautiful and suitable with the style of the theme.
1. It is very easy to use, even with beginners.
1. You own and control your data - forever. This plugin won't never take any data from your store.
1. It will not mess up your admin dashboard with a new menu and strange pages. It follows and uses the standard options of WooCommerce. You will find it is very easy to use.
1. No Ads, of course. This is a free plugin but it doesn't mean you have to accept Ads in your admin dashboard.
1. It is easy to customize. Even if you are not a developer, with some simple CSS or snippet, you are able to customize the product swatches swatches.
1. It is lightweight. With a simple feature like this, you don't need to be concerned about how it impacts your website's speed.


== Features provided with this plugin ==

* Completely integrated with the WooCommerce plugin
* Work on variable products only
* Auto-convert the default dropdowns to buttons
* Enable Color swatches for product attributes
* Enable Image swatches for product attributes
* Enable Label/Text swatches for product attributes
* Enable Button swatches for product attributes
* Manage attribute swatches globally
* Edit product swatches for every single product
* Control the style of swatches, including shape, size, etc.
* Enable the tooltip for swatches
* Provide inner API that allows themes/plugins to extend
* Create a new attribute swatch in the product editing page


== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser.

1. Log in to your WordPress dashboard, navigate to the Plugins menu, and click Add New.
1. In the search field type "WCBoost - Variation Swatches" and click Search Plugins.
1. Once you’ve found it, you can install it by simply clicking the Install Now button.

= Manual Installation =

1. Download the WCBoost - Variation Swatches plugin to your desktop.
1. Extract the plugin folder to your desktop.
1. Read through the "readme" file thoroughly to ensure you follow the installation instructions.
1. With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
1. Go to Plugins screen and find the "WCBoost - Variation Swatches" in the list.
1. Click Activate to activate it.

= Config attributes =

Even if this plugin has been installed and activated on your site, variable products will still show dropdowns if you've not configured product attributes.

1. Log in to your WordPress dashboard, navigate to the Products menu and click Attributes.
1. Click on the attribute name to edit an existing attribute or in the Add New Attribute form you will see the default Type selector.
1. Click on that Type selector to change the attribute's type. Besides default options Select and Text, there are more than 3 options Color, Image, and Label to choose from.
1. Select the suitable type for your attribute and click Save Change/Add attribute
1. Go back to the Attributes screen. Click the cog icon on the right side of an attribute to start editing terms.
1. Start adding new terms or editing existing terms. There will be a new option at the end of the form that lets you choose the color, upload an image, or type the label for those terms.


== Frequently Asked Questions ==

= Will this plugin work with my theme? =
Yes, it will work with any theme, but may require some styling to make it match nicely.

= Does it work with Multisite? =

Yes, it does work with WordPress multisite.

= How to configure attribute swatches? =

By default, this plugin will convert all variation dropdowns to buttons (Of course, you can disable this feature with a single option in the Customizer > WooCommerce). You are able to change them to other swatch types.
You just need to log in to the admin dashboard, then navigate to Product > Attributes menu to edit product attributes and change the type. You can change it to Color, Image, Label or Button. Then you need to edit/add attribute terms to set a color, upload an image, or set the label the present for that attribute term.

= Where are the plugin's options =

Following the standards of WordPress and WooCommerce, you can find all the settings that relate to the appearance of your website in Appearance > Customize. The settings of this plugin are in the Customize > WooCommerce > Variation Swatches section.

= How to disable out-of-stock swatches =

WooCommerce has an option to hide out-of-stock products from the catalog, it works for the variation swatches too. You just need to enable this option in WooCommerce > Products > Inventory. It is the option "Out of stock visibility".

== Screenshots ==

1. Variation swatches with StoreFront theme
1. Attribute swatches type "Color"
1. Attribute swatches type "Image"
1. Attribute swatches type "Label"
1. Manage attribute swatches in each variable product

== Changelog ==

= 1.0.15 =
* Tweak the CSS for swatches to improve the accessibility.
* Change the label of the swatche shape option.
* Fix the error when unstalling the plugin.
* Ensured compatibility with WordPress 6.4
* Ensured compatibility with WooCommerce 8.3
* Dev - Adds more actions and filters to support themes and plugins.

= 1.0.14 =
* Added support for the WooCommerce HPOS feature.
* Ensured compatibility with WordPress 6.3
* Ensured compatibility with WooCommerce 8.0

= 1.0.13 =
* Improve the accessibility

= 1.0.12 =
* Fix missing files issue

= 1.0.11 =
* Add a new filter hook for swatches output
* Fix js error when the attribute slug contains double quotes

= 1.0.10 =
* Tested the compatibility with the latest version of WordPress and WooCommerce.

= 1.0.9 =
* Improve the compatibility with multilingual plugins

= 1.0.8 =
* Fix wrong spelling keywords

= 1.0.7 =
* Update WordPress and WooCommerce compatibility
* Tweak the js function to control swatches items state independently

= 1.0.6 =
* Compatible with plugin WooCommerce Product Bundles

= 1.0.5 =
* Fix the issue of incorrect swatch data with custom attributes

= 1.0.4 =
* Fix the compatibility issue that cannot map the option of another plugin
* Fix the issue of attribute type is not reset to "Select" when deactivating the plugin

= 1.0.3 =
* Add a new option to show the label of selected attributes
* Fix the style of round button swatches
* Fix some security issues with the translation of the plugin

= 1.0.2 =
* Fix the issue of incorrect swatch display

= 1.0.1 =
* Add a new JS event to support third-party plugins.

= 1.0.0 =
* Initial release.
