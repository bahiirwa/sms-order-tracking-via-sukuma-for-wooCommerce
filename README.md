# SMS Order Tracking via Sukuma for WooCommerce

Send SMS Notifications to your WooCommerce E-Shop when order status or customer notices change using MTN and Airtel Uganda networks.

> To use this plugin you need to open an account on <strong>[Sukumasms](https://send.sukumasms.com)</strong> and have SMS credits in your account.

This is a WordPress and Woocommerce Order SMS notifications via a REST API to send SMS from your websites.

To signup for an account, visit our website by [clicking here](https://send.sukumasms.com)

SMS messages can be sent to the following Ugandan mobile networks

* __mtn__
* __airtel__

### Note

SMS messages can only be sent to Ugandan mobile numbers ( mtn & airtel )

### Plugin Features

*   __Send SMS notifications__ straight from your website.
*   __Send SMS order notifications__ to store admin when an order is made
* 	__Send SMS order notifications__ to customers once their order status changes
* 	__Send SMS__ to the customer from the order details page


### Suggestions / Feature Request

If you have suggestions or a new feature request, feel free to get in touch with us via the contact form on [our website here](https://omukiguy.com/) or the [GitHub Repo](https://github.com/bahiirwa/woocommerce-sms-order-tracking)

### Contribute
To contribute to this plugin feel free to fork it on our [GitHub Repo]( https://github.com/bahiirwa/woocommerce-sms-order-tracking) and send a Pull request.


## Installation

### Automatic Installation
* 	Like all other plugins in the plugin admin area.

### Manual Installation
1. 	Download the plugin zip file
2. 	Login to your WordPress Admin. Click on "Plugins > Add New" from the left hand menu.
3.  Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
4.  Activate the plugin.
5. 	Open the settings page for WooCommerce and click the "SMS Details" tab.
6.	Configure your "WooCommerce SMS Notifications" settings. See below for details.

### Setup and configuration
To configure the plugin, go to __Order SMS__ from the left hand menu to configure SMS settings.

__*API Credentials*__

* __UserName__ - enter your Public Key here, you will get this from the settings page in your Jusibe account
* __Password__ - enter your Access Token here, you will get this from the settings page in your Jusibe account
* __Sender ID__ - enter the Sender ID that will be recorded for each sent SMS. Can be your business Name.

__*Admin SMS Notifications*__

* __Enable Admin New Order SMS Notifcations__  - check the box to enable sending of SMS to the store admin when an order is placed.
* __Admin Mobile Number__  - enter the mobile number of the store admin where new order SMS will be sent to, in the format 256XXXXXXXX. Seperate multiple numbers by commas
* __Admin SMS Message__  - enter the message that will be sent to the store admin when an order is placed.

__*Customer SMS Notifications*__

* __Send SMS Notifications for these order statuses:__  - select the order statuses changes that will send a SMS notification to a customer.

__*Customise SMS Messages*__

* __Default Customer SMS Message__  - This is the default SMS message that is sent when an order status changes.
* __Pending SMS Message__  - enter a custom SMS message to be sent when an order status is changed to pending or leave blank to use the default message.
* __On-Hold SMS Message__  - enter a custom SMS message to be sent when an order status is changed to on-hold or leave blank to use the default message.
* __Processing SMS Message__  -enter a custom SMS message to be sent when an order status is changed to processing or leave blank to use the default message.
* __Completed SMS Message__  - enter a custom SMS message to be sent when an order status is changed to completed or leave blank to use the default message.
* __Cancelled SMS Message__  - enter a custom SMS message to be sent when an order status is changed to cancelled or leave blank to use the default message.
* __Refunded SMS Message__  - enter a custom SMS message to be sent when an order status is changed to refunded or leave blank to use the default message.
* __Failed SMS Message__  - enter a custom SMS message to be sent when an order status is changed to failed or leave blank to use the default message.


__*Send Test SMS*__

This allow you to send a Test SMS.

* __Mobile Number__  - enter the mobile number you are test SMS will be sent to
* __Message__  - enter the test message to be sent.

* Click on __Save Changes__ for the changes you made to be effected.


## Frequently Asked Questions

### What Do I Need To Use The Plugin

1.	You need to have Woocommerce plugin installed and activated on your WordPress site
2.	You need to open an account on [Sukumasms](https://sukumasms.com)
3.	You need to have SMS credits in your [Sukumasms](https://sukumasms.com) account

## Changelog

### 0.1.0 - June 1st, 2020
* First release

##Upgrade Notice

### 0.1.0

## Screenshots

1. Adding Screenshots later
