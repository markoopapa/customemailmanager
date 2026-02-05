Markdown
# Custom Email Template Manager for PrestaShop

A powerful and lightweight PrestaShop module that allows you to replace default transactional emails with **modern, responsive, and image-rich templates**. 

It automatically injects product images into order emails and lets you assign specific designs to different email types (e.g., a festive design for customers and a clean data-heavy design for admins).

![Module Logo](logo.png)

## 🚀 Key Features

* **Product Images in Emails:** Automatically adds product thumbnails to the `{items}` variable in emails (works with `new_order`, `order_conf`, etc.).
* **Template Switcher:** Create multiple templates (Modern, Black Friday, Christmas, etc.) and switch between them instantly.
* **Targeted Assignment:** Assign specific templates to specific email types:
    * *Customer Confirmation* (`order_conf`) -> Use a colorful/festive template.
    * *Admin Alert* (`new_order`) -> Use a clean, informative template.
    * *General* (`all`) -> Fallback for all other emails.
* **Live Preview:** Visualize your HTML template with dummy data before saving.
* **Test Email:** Send a test email to yourself directly from the back office.
* **No Core Overrides:** Uses PrestaShop hooks (`actionEmailSendBefore`), ensuring compatibility with updates.

## 📦 Installation

1.  **Download** the repository or zip the `customemailmanager` folder.
2.  Go to your PrestaShop Back Office: **Modules > Module Manager**.
3.  Click **Upload a module** and select the zip file.
4.  Once installed, go to **Design > Email Template Manager** to configure your templates.

## 🛠️ Configuration

1.  Navigate to **Design > Email Template Manager**.
2.  You will see a list of default templates installed (Modern, Christmas, Black Friday).
3.  Click **Edit** on a template to modify the HTML.
4.  Use the **"Target Email Type"** dropdown to decide where this template applies:
    * Select **All Emails** to apply it globally.
    * Select **Customer Confirmation** to apply it only to what the customer sees.
    * Select **Admin Alert** to apply it only to the shop owner's notification.
5.  Set the status to **Active** (Yes) to enable it.

### Available Variables in HTML
You can use standard PrestaShop variables plus the enhanced items table:
* `{items}` - The generated HTML table with product images.
* `{shop_name}` - Your shop's name.
* `{shop_logo}` - URL to your shop's logo.
* `{order_name}` - The order reference (e.g., KLMDSJF).
* `{total_paid}` - Total amount paid.
* `{firstname}`, `{lastname}` - Customer details.

## 🔧 Technical Details

* **Hooks used:** `actionEmailSendBefore`
* **Database:** Creates a table `ps_custom_email_templates` to store HTML designs.
* **Compatibility:** PrestaShop 1.7.x / 8.x

## 📂 Directory Structure

```text
customemailmanager/
├── classes/
│   └── CustomEmailTemplate.php  # ObjectModel
├── controllers/
│   └── admin/
│       └── AdminCustomEmailConfigController.php # Back Office Logic
├── mails/                       # Temporary folder for mail sending
├── sql/
│   ├── install.php              # Database creation
│   └── default_templates.php    # Default HTML layouts
├── views/
│   └── templates/
│       └── admin/
│           └── configure.tpl    # Preview & Test button view
├── customemailmanager.php       # Main module file
├── logo.png                     # Icon
└── README.md                    # This file
🤝 Contributing
Feel free to fork this project and submit pull requests. You can add more beautiful HTML templates to the sql/default_templates.php file!

📄 License
This module is open-source.
