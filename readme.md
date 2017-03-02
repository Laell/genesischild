#GenesisChild

This is a basic starter **GenesisChild Theme** to be used with the **Genesis Framework** on **WordPress**.

There is also a Mobile First version of this starter theme (Which is the one I prefer to use as my preferred starter) - https://github.com/neilgee/genesischild-mobile-first

This GenesisChild theme has a number of widgets areas, the majority are Front Page only.

###Widgetised Areas
- The theme contains the following widgets
* Pre-Header Left
* Pre-Header Right
* Header Right
* Hero - Home Page only *front-page.php*
* Optin - Home Page only *front-page.php*
* Home Top - Home Page only *front-page.php*
* Home Middle - Home Page only *front-page.php*
* Home Bottom - Home Page only *front-page.php*
* Before Content *posts only*
* After Content *posts only*
* Footer Widget Header
* Footer Widgets 1, 2, 3
* Footer
* Post Footer Left
* Post Footer Right

###Home Page
The Home Page has been widgetized and the default Genesis loop can be removed.


To remove the Genesis home page loop tweak the **front-page.php** file by uncommenting line 88:
```php
gc_genesis_no_content();
```

and commenting line 90:
```php
//genesis();
```

###Example
For an online visual:
http://themes.wpbeaches.com/genesischild/
Areas are highlighted to show boundaries and full width wraps these colors are commented out in the CSS.
Only difference is the main home page 3 modules are now stacked in the theme, Home Top, Home Middle and Home Bottom.

###Menus
- Primary Menu is positioned above Header Right Widget Hook and is set to Primary Location
- Secondary Menu remains in default area and is Secondary Location spanning full width below the logo/header right.
- Responsive menu is added but not enabled, to enable it uncomment in *functions.php* line 39 *	// include_once( get_stylesheet_directory() . '/includes/responsive-menu.php' );*

###Custom Logo
- Custom Logo is supported via the Customizer, suggested size is 400x150px which you can change in the *add_theme_support('custom-logo')* array in *functions.php*
- The header logo  uses an `<img>` element and can support the SVG format.
- SVGs are set to be allowed for upload by the constant 'ALLOW_UNFILTERED_UPLOADS' declared in *functions.php*

###Background Image
- Background Images is supported, a background image can be uploaded in the WP Dashboard via Appearance > Background, this will scale to fit any viewport via BackStretchJS.

To use this you have to enable the BackStretch script in */includes/scripts-styles.php* at line 54

###Customizer
- Customizer options, you can set colors for the options below...
  - link,
  - link hover
  - menu,
  - menu hover,
  - button,
  - button hover,
  - footer links,
  - footer links hover,
  - footer widgets background color

- Site Identity > Logo upload
- Featured Images > background image behind Hero Widget


###Featured Custom Post Type Widget for Genesis
- Bundled in the theme is the 'Featured Custom Post Type Widget for Genesis', the widget is available from the normal widget area in the dashboard *Appearance > Widgets*.

This widget builds on the default Genesis supplied Featured Post widget but can also display Custom Post Types and Taxonomies as well as output them in columns. It is not necessary to install the plugin.

*Ref - https://github.com/calliaweb/featured-custom-post-type-widget-for-genesis


###Javascripts
All scripts are in */includes/scripts-styles.php*
- FontAwesome is disabled.
- placeholder.js is enabled.
- backstretch.min.js is disabled.
- FItVids is disabled

###CSS
All CSS files are in */includes/scripts-styles.php*
- Regular style.css
- 2 x IE styles in CSS directory, one targets IE8 and lower, the other IE9 and lower
- Media Queries set at 767px and 1200px
- Flexbox styles have been used in CSS for *.wrap* and other page layouts - Flexbox and all CSS styles have been autoprefixed to cater for last 5 browser versions.

###WooCommerce
- All WooCommmerce functionality including CSS is only activated if WooCommerce plugin is installed and activated.
- Install and activate Genesis Connect for WooCommerce plugin - https://wordpress.org/plugins/genesis-connect-woocommerce/
- WooCommerce functions are then included and can be seen in */includes/woocommerce.php*, these functions have some active and others commented out, read the comments to see if you require theme
- WooCommerce CSS is at */css/woo.css* it is set to load after default WooCommerce CSS
- Once WooCommerce is active there are a number of additional Customizer options for control of color in WooCommerce elements, this will be visible in the last panel of the Customizer

###Miscellaneous
- PHP is enabled to execute in widget areas
- Shortcode enabled in widget areas
- 'Read More' link is enabled on post excerpts
- HTML Tags and Attributes is removed from comments
- The font 'Open Sans' is enqueued from Google Fonts in *includes/scripts-styles.php*
- Author name removed in Post Meta for posts
- SVG support for image uploads
- Beaver Builder full width CSS styles added - leaves header and footer elements intact but full width for inner page.
- Genesis Theme defaults now in */includes*
- Generic landing page added
- Flexbox sticky footer added on site container with IE10/11 support


###Modular Approach
At the top of functions.php there a number of include files that you can comment/uncomment for adding functionality.
- *customize.php* contains all the Customizer options.
- *output.php* renders the Customizer options CSS
- *responsive-menu.php* adds the Genesis Responsive Menu
- *scripts-styles.php* contains all JS and CSS files for inclusion
- *theme-defaults.php* contains all the theme defaults
- *widgets.php* contains all the widgets that need to be registered
- *woocommerce.php* contains all the woocommerce functionality, commented out snippets included
- *class-featured-custom-post-type-widget.php* widget for the Genesis Featured Custom posts


Download the zip rename the theme '**genesischild**' - place this theme in your WordPress installation **"/wp-content/themes/"** and activate in WordPress Dashboard

![Genesis Child Theme](https://wpbeaches.com/images/gc-screen1.png)

![Genesis Child Theme Customizer](https://wpbeaches.com/images/gc-customizer.png)

![Genesis Child Theme Widget Areas](https://wpbeaches.com/images/gc-screen-widgets.png)

![Genesis Child WooCommerce Customizer](https://wpbeaches.com/images/woocommerce-customizer.png)
