# mackerel-client-php

[![Latest Unstable Version](https://poser.pugx.org/ariarijp/mackerel-client/v/unstable)](https://packagist.org/packages/ariarijp/mackerel-client)
[![Total Downloads](https://poser.pugx.org/ariarijp/mackerel-client/downloads)](https://packagist.org/packages/ariarijp/mackerel-client)

mackerel-client-php is an unofficial port of [mackerelio/mackerel-client-ruby](https://github.com/mackerelio/mackerel-client-ruby).

## Usage

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$client = new \Mackerel\Client([
    'mackerel_api_key' => 'YOUR_MACKEREL_API_KEY_HERE',
]);
$host = $client->getHost('HOST_ID');
```

## License

MIT

## Author

[ariarijp](https://github.com/ariarijp)
