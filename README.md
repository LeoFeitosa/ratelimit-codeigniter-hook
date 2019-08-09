# Codeigniter rate limiter hook

Inspired by: https://github.com/alexandrugaidei/ratelimit-codeigniter-filebased

This hook allows you to block ip addresses for a period of time based on a number of pre-established requests.

## Usage

1. Inside the config directory in the config.php file enable the use of hooks:
```php
// application/config/config.php
$config['enable_hooks'] = TRUE;
```

2. Inside the config directory in the hooks.php file add the code below:
```php
// application/config/hooks.php
$hook['post_controller_constructor'] = array(
    'class' => 'ratelimit',
    'function' => 'limit_all',
    'filename' => 'ratelimit.php',
    'filepath' => 'hooks'
);
```

3. Lastly copy and paste the hatelimit.php file into the hooks folder
```php
// application/hooks
```

## Settings
How changes should be made within the file: application/hooks/ratelimit.php
```php
// Number of Requisitions
$max_requests = 100;
// Time it will be locked (seconds)
$sec = 300;
```