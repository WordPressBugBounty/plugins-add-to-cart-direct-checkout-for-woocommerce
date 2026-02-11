=== Add to Cart Redirect for WooCommerce ===
Contributors: rajeshsingh520
Tags: WooCommerce direct checkout, direct checkout, WooCommerce Single Page Checkout, one page checkout, redirect to checkout
Requires at least: 3.0.1
Tested up to: 6.9
Stable tag: 2.1.91
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Features offered: Add to cart redirect, Quick purchase button, Buy now button, Quick View product, option to change quantity on checkout page.

== Description ==

Streamline the shopping experience by sending customers straight to checkout after adding a product to their cart.

This plugin helps simplify the buying process by minimizing unnecessary steps and distractions.

[Documentation](https://www.piwebsolution.com/user-documentation-direct-checkout-plugin/)

= Key Features =

* Automatically redirect users to the checkout or a custom page after adding a product
* Works with Ajax-based add-to-cart functionality (on category and shop pages only)
* Option to redirect to a category, product, or any custom URL
* Remove the “Continue Shopping” link
* Optionally disable the cart page
* Show cart contents on the checkout page for quick updates
* Customize labels for “Add to Cart,” “Read More,” and “Select Options” buttons
* Add a secondary action button to product and shop pages for faster purchasing
* Display a popup preview of products from archive pages
* Allow quantity updates and product removal directly from the checkout
* Enable One Page Checkout to reduce friction
* Require users to log in or register before checking out
* Support “Sold individually” settings to limit product quantity
* Retain UTM parameters for better order source tracking
* Protect checkout from spam and fake orders using built-in CAPTCHA (no third-party key needed)


= Fast checkout =
WooCommerce Direct Checkout aims to simplify the checkout process, leading to an immediate increase in sales. This plugin for WooCommerce allows you to redirect users to the checkout instead of cart page.

Direct Checkout allows you to remove other unnecessary things in the checkout process like the order comments, shipping address (pro), coupon form.

One Page Checkout – Reduce cart abandonment with our One Page Checkout option. One Page Checkout makes it easier for customers to buy from custom checkout pages by displaying both the product selection and checkout forms on a single page. 

The whole idea behind WooCommerce single page checkout is to reduce the lengthy checkout process.


<blockquote>
Buy Now button for variable products on Category / Shop / Archive pages, so the buyer can directly add the product to the cart even without viewing its variations. **What it does is, it adds the first variation of the variable product to the cart.**
</blockquote>

= PRO Features =

* Override the global redirect setting for individual products
* Set a custom redirect page per product, including on archive pages
* Supports product-level redirects even with Ajax-based add-to-cart buttons
* Option to disable redirect for specific products
* Define unique redirect URLs per product to guide users through targeted funnels
* Modify the label of the secondary action button for product and archive pages
* Change the position of the quick purchase or buy button
* Automatically clear other items from the cart when a new item is added via the quick purchase button
* Choose whether the quick purchase button redirects to the cart or checkout
* Disable the quick purchase button for specific products as needed
* Customize the appearance of the quick view popup, including background and text color
* Redirect users to an external URL after adding a product, if desired
* Set any page as the post-purchase success or thank you page
* Define custom thank you pages for each product, enabling personalized post-checkout experiences
* Premium support with responses to all queries within 24 hours

== Make the Buy Now Button Work Like Amazon (PRO) ==

Let users quickly purchase a single product without affecting the rest of their cart. For example, if a user already has products X, Y, and Z in the cart and clicks "Buy Now" on product A, they’ll be taken to checkout with just product A. The other items (X, Y, Z) remain in the cart for future purchase.


[youtube https://www.youtube.com/watch?v=gC4fO3_1kdU&cc_load_policy=1]

== Frequently Asked Questions ==

= Where are the plugin settings? =
On the left side of the dashboard
WooCommerce > Direct checkout

= How to activate the redirect =
Just install the plugin and activate it, and it will start redirecting to the checkout page.

= I want to change the redirect page =
Go to the plugin Basic settings. See the option **Redirect to page**; there you can select from pages.

= I want to disable redirect =
Go to Basic Settings and click on **Enable redirect on add to cart**. This option is used to enable or disable the redirect.

= I want to set redirect on a product only =
PRO version has this feature.
In the PRO version, you can disable the global redirect and set a redirect for one product.
This way, the user will be redirected only when they add that specific product to the cart, and they won't be redirected when they add other products.

= I want to disable redirect on a few specific products =
PRO version allows you to do this.

= I want to change the redirect page for a specific product =
Pro Version allows you to change the redirect page for a specific product.

= Will it work on the archive page =
Yes, our plugin works on all the add to cart buttons.

= Will it work on the Ajax add to cart button =
Yes, our plugin works on Ajax add to cart buttons as well.

= I want to bypass the cart page in WooCommerce =
You can do so now with our option "Disable cart page"; this will redirect all cart page traffic to the checkout page.

= I want to show the cart page and checkout page as one-page checkout instead of 2 different pages in WooCommerce
You can do that using our option "Enable single page checkout"; after enabling this, the cart and checkout will be shown on the same page.
(Single page checkout will now work if you implement your checkout page using the WooCommerce content block; you should implement your checkout page using the [woocommerce_checkout] shortcode)

= I want to redirect to a category page after adding to cart =
You can do that in the FREE version; you can set one category page as the default redirect,
But in the PRO version you can set a product-level redirect, so if someone buys a pen you can redirect them to the Books category or even a single book (product).
This way you can create a complete funnel of redirects.

= Increase sales by redirecting the customer to a different product when they buy one =
Say someone buys a T-shirt; as soon as they add this T-shirt to the cart, they get redirected to the Paints product page. If they then add some paint to the cart, they will be redirected to another product. This way, they will go on buying.

= I want to change the Add to Cart button text =
Yes, you can change it from the plugin settings.

= Can I enable and customize the quick purchase button? =
Yes, the plugin lets you add a quick purchase button to product and archive pages, and in the PRO version, you can also change its label.

= You can disable the Quick Purchase button for the product archive page =
Yes, you can disable it for the product archive page and keep it running for the product page or vice versa.

= I want to disable the Quick Purchase button for a specific product =
Yes, you can do that in the Pro version; it allows you to disable the Quick Purchase button on a particular product.

= The Buy Now button is not working on a variable product =
At present, it does not work with variable products, but it will start supporting variable products shortly.

= You can have a Quick View option for the product on the archive page =
Using the Quick View button, customers can see the product details from the archive page without leaving the page.

= Quick View will work for the variable product =
 Yes, Quick View will work for variable products; in fact, it is most useful for variable products, as a customer can view the product directly from the archive page and even add it to the cart from the archive page, as the Quick View module allows them to select the product variation.

= Remove Order comments and coupon fields from the checkout page =
You can remove order comments and coupon fields from the checkout page. 

= Remove "Ship to a different address?" =
In the Pro version, you can remove the "Ship to a different address?" option. 

= Remove billing fields from the checkout form =
In the Pro version, you can remove the following billing fields from the checkout form: First name, Last name, City, Country, State, Address line 1, Address line 2, and Postal code.
This feature will only work if your checkout page is made using the WooCommerce shortcode [woocommerce_checkout]. It will not work if your checkout page is made with the WooCommerce Checkout Block.

= Remove shipping fields from the checkout form =
In the Pro version, you can remove the following shipping fields from the checkout form: First name, Last name, City, Country, State, Address line 1, Address line 2, and Postal code.
This feature will only work if your checkout page is made using the WooCommerce shortcode [woocommerce_checkout]. It will not work if your checkout page is made with the WooCommerce Checkout Block.

= I want to redirect to an external URL after adding to cart =
Yes, you can do that in the Pro version.

= Can I enable one-click checkout for variable products on archive pages? =
Yes, in the PRO version, a default variation can be added directly to the cart from archive views.

= The Buy Now button on the archive page is not working for a particular product =
You have to make sure that you have set the default values of all the required variables for that product. If some required variable is not set for the first variation and you have not set a default for that variable, then, in that case, Buy Now for that product will fail with a warning that "required fields cannot be blank".

= I want the product name given on the Checkout page to be linked to the product pages of the respective products =
Yes, you can do that using the Pro version; it gives you the option to link product names on the checkout page to their respective product pages.

= Customize the size of the Quick View box =
You can customize the width of the Quick View box in the Pro version.

= Redirect customers to a custom thank you page on order success =
The Pro version gives you the option to redirect customers to a custom page on successful order placement. 

= Set different thank you page URL for different products =
Pro version allows you to set different thank you page URLs for different products.

= How will the custom thank you page redirect work if there are 2 products in the order with different thank you page URLs =
You have the option to specify the weight of the link on the product page, so when there are 2 products with different thank you pages, their weight is considered, and the product with the highest weight is used for the redirect.

= How to modify the cart details on the WooCommerce checkout page =
Yes, you can do that. The plugin gives you the option to show the quantity field next to each product on the checkout page, and the option to remove products from the checkout page itself.

= How to change quantity in WooCommerce checkout? / I want to give the option to modify product quantity on the WooCommerce checkout page =
Yes, you can do that. The plugin gives you the option to show the quantity field next to each product on the checkout page; from there, customers can modify the quantity on the checkout page itself.

= I want to give the option to remove products from the checkout page =
Yes, the plugin gives you the option to remove products directly from the checkout page without going to the cart page.

= Option to change quantity on the checkout page and remove product is disabled =
This option will not be enabled if you have enabled the single page checkout option "Enable single page checkout" in the Basic settings tab.

= How to force WooCommerce customers to log in or register before they buy =
For this, go to the Checkout settings tab; there you can enable the option "Force login before checkout".

= The registration option is missing on the login page =
To enable the registration option, go to **WooCommerce > Settings > Accounts & Privacy** and enable the option **Allow customers to create an account on the "My account" page**.

= Is it HPOS compatible? =
Yes, the free version and PRO version are both HPOS compatible.

= Can the Buy Now button behave like Amazon’s one-click checkout? =
Yes, in the PRO version, the Buy Now button can mimic Amazon-style behavior. When clicked, the customer is redirected to the checkout with only the selected product, while any other items in the cart are saved for later.

= How to stop spam in WooCommerce orders? / How to prevent spam orders? =
You can stop spam in WooCommerce orders by enabling the option "Enable CAPTCHA on checkout page" in the Checkout settings tab. This will show a simple CAPTCHA on the checkout page so that only humans can place orders.

== Changelog ==

= 2.1.90 =
* capcha to use admin-ajax.php

= 2.1.77 =
* code improvement

= 2.1.73.69 =
* Documentation added

= 2.1.73.67 =
* Tested for WC 10.0.2

= 2.1.73.66 =
* Content change

= 2.1.73.64 =
* UI improvement in Direct checkout for WooCommerce

= 2.1.73.63 =
* Compatible with WC 9.9.5

= 2.1.73.62 =
* Compatible with WC 9.9.3

= 2.1.73.61 =
* Tested for WC 9.8.5

= 2.1.73.60 =
* Tested for WP 6.8.0 and WC 9.8.0
* Translation warning fixed

= 2.1.73.49 =
* Tested for WC 9.7.1

= 2.1.73.47 =
* Tested for WC 9.7.0

= 2.1.73.46 =
* Tested for WC 9.6.2

= 2.1.73.44 =
* Tested for WC 9.6.0

= 2.1.73.43 =
* Quick view variation slider fix

= 2.1.73.40 =
* Select variation popup warning on buy now button click

= 2.1.73.39 =
* Checkout spam protection with captcha added

= 2.1.73.37 =
* PHP 8.2 deprecation warning fixed 

= 2.1.73.36 =
* Tested for WP 6.7.0

= 2.1.73.34 =
* Content change

= 2.1.73.32 =
* Tested for WC 9.3.0

= 2.1.73.31 =
* Tested for WC 9.2.3

= 2.1.73.30 =
* Tested for WC 9.1.4

= 2.1.73.29 =
* Tested for WP 6.6.1 and WC 9.1.0

= 2.1.73.27 =
* Tested for WP 6.6.0
* Code improvement

= 2.1.73.26 =
* Tested for WC 9.0.3

= 2.1.73.24 =
* Tested for WC 9.0.0

= 2.1.73.11 =
* New implementation for the single page checkout, with an option to fall back to the old way of implementation

= 2.1.73.6 =
* Tested for WP 6.4.2
* Compatible with PHP 8.2

= 2.1.73.4 =
* Tested for WC 8.3.0

= 2.1.73.3 =
* Tested for WP 6.4.0

= 2.1.73.1 =
* Small change in checkout quantity changer; now it will have the cart item as ID rather than a random ID 

== Privacy ==

If you choose to opt in from the plugin settings, or submit optional feedback during deactivation, this plugin may collect basic technical information, including:

- Plugin version  
- WordPress version  
- WooCommerce version  
- Site URL
- Deactivation reason (if submitted)

This data is used solely to improve plugin quality, compatibility, and features. No personal or user-specific data is collected without consent.