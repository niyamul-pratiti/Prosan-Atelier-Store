# Prosan Atelier v5 Fix Patch

This patch fixes:
- Related product card equal height and top-aligned content
- Admin Customers panel
- Customer account/dashboard/order history
- Mobile responsive overflow on hero/nav/brands/trending products
- Shipping charge: Inside Dhaka ৳60, Outside Dhaka ৳130
- Improved order tracking output
- Featured image + gallery image support and JPG upload route support

Apply files to `/home/niyamulp/prosanatelier_app` except public CSS files, which must also be uploaded to `/home/niyamulp/prosanatelier.com`.

Import SQL once from phpMyAdmin: `prosan_v5_customer_shipping_patch.sql`.

After uploading, delete compiled view files from `storage/framework/views` and cache files from `bootstrap/cache`.
