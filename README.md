[![N|Solid](https://www.tabscanner.com/wp-content/uploads/2017/08/tabscannerlogo260-padded.png)](https://www.tabscanner.com)

# The world’s most advanced receipt scanning API technology.

The perfect OCR receipt scanning API for developers, utilizing state-of-the-art receipt optical character recognition. Technology designed from the ground up for receipt recognition and data extraction.

Tab Scanner is the world's first truly accurate check and receipt scanning technology. It utilises a highly crafted OCR system with state-of-the-art AI to ensure robust and reliable data extraction at lightning speeds.
- Highly accurate data extraction
- Sub-second processing speeds
- Cross-platform API support
- Easily integrates with your software
- Flexible pricing plans

## About this SDK
This is an official PHP development kit for Tab Scanner API. For more information about Tab Scanner API please visit https://www.tabscanner.com

### Installation

The recommended way to install Tab Scanner PHP SDK is through [Composer](https://getcomposer.org/).

```sh
$ composer require tabscanner/phpsdk
```

### Basic Usage

Visit [Tab Scanner Alpha](https://alpha.tabscanner.com) for your API key 

```php
use Tabscanner\Api;

$api = new Api('ApiKeyHere');

/**
 * Upload receipt to AI server to be processed
 *
 * @param $file array|string
 * array - single HTTP File Upload variable ($_FILES) (for array of files see upload_multiple method)
 * string - file path (used for fopen function)
 * 
 * @return array
 */
$file = 'receipt.jpg'; //direct grab from directory
$file2 = $_FILES['receipt']; //from upload form

$upload_response = $api->upload($file); //or $file2

//receipt token is generated from API after successful upload, else will receive error
$receipt_token = $upload_response['token'];

/**
 * Get result
 *
 * @param $token string
 * @return array - receipt data
 * - will receive status as pending or done
 * - one way to use this method is to create a loop until you get a "status done" response
 */
$result_response = $api->result($receipt_token);
```

### Upcoming Methods
```php
upload_multiple() //accepts multi-dimensional $_FILES
```

