# Error Log Test Plugin

A WordPress plugin for testing and validating PHP error logging configurations. Generate various types of PHP errors on demand to ensure your error monitoring and logging systems are working correctly.

## Description

The Error Log Test Plugin provides a safe, controlled environment to generate different types of PHP errors for testing purposes. Whether you're setting up error monitoring, debugging logging issues, or testing your error handling systems, this plugin makes it easy to verify everything is working as expected.

All errors are triggered manually through an admin interface, preventing accidental site crashes during normal operation.

## Features

- 🎯 **On-Demand Error Generation** - Trigger errors only when needed via admin interface
- 📝 **Multiple Error Types** - Generate notices, warnings, fatal errors, and syntax-like errors
- 🔍 **Error Log Detection** - Automatically displays your PHP and WordPress error log locations
- 🛡️ **Safe Testing** - Errors only trigger on button click, not during normal operation
- 📊 **Comprehensive Logging** - All errors are prefixed with `[Error Log Test]` for easy identification
- 🔧 **Custom Error Handler** - Test custom error handling implementations
- 📱 **Admin Integration** - Accessible via Tools menu in WordPress admin

## Installation

### From WordPress Admin

1. Download the plugin ZIP file
2. Navigate to **Plugins → Add New** in your WordPress admin
3. Click **Upload Plugin** and choose the ZIP file
4. Click **Install Now** and then **Activate**

### Manual Installation

1. Upload the `error-log-test-plugin` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Navigate to **Tools → Error Log Test** to access the plugin

### Via Composer

```bash
composer require octahexa/error-log-test-plugin
```

## Usage

### Basic Usage

1. After activation, go to **Tools → Error Log Test** in your WordPress admin
2. You'll see four buttons for different error types:
   - **Generate Syntax-like Errors** - Creates parse errors using eval()
   - **Generate Various Errors** - Creates notices, warnings, and user errors
   - **Test Error Logging** - Tests different error logging methods
   - **Generate Fatal Error** - Creates a fatal error (⚠️ will crash the page!)
3. Click any button to generate the corresponding errors
4. Check your error logs to verify the errors were logged

### Error Log Locations

The plugin displays your current error log locations:
- **PHP Error Log**: Usually at `/var/log/php_errors.log` or similar
- **WordPress Debug Log**: `/wp-content/debug.log` (if enabled)

## Configuration

### Recommended wp-config.php Settings

Add these lines to your `wp-config.php` for optimal error logging:

```php
// Enable WordPress debugging
define('WP_DEBUG', true);

// Log errors to /wp-content/debug.log
define('WP_DEBUG_LOG', true);

// Hide errors from displaying on site
define('WP_DEBUG_DISPLAY', false);

// Use development environment
define('WP_ENVIRONMENT_TYPE', 'development');
```

### PHP Configuration

Ensure these PHP settings in your `php.ini`:

```ini
error_reporting = E_ALL
log_errors = On
error_log = /path/to/error.log
display_errors = Off
```

## Error Types Generated

### Syntax-like Errors
- Missing semicolons
- Unclosed brackets
- Invalid PHP syntax via eval()

### Various PHP Errors
- **E_NOTICE**: Undefined variables
- **E_WARNING**: Missing file includes
- **E_USER_ERROR**: Custom triggered errors
- **E_USER_WARNING**: Custom warning messages
- **E_USER_NOTICE**: Custom notice messages

### Fatal Errors
- Undefined function calls
- Method calls on null objects
- Type declaration mismatches

### Logging Tests
- Direct error_log() calls
- Array and object logging
- Custom error handler implementation
- Different log types and destinations

## Example Output

```
[Error Log Test] === Starting Error Generation ===
[Error Log Test] Timestamp: 2025-01-24 10:30:45
[Error Log Test] Triggering E_NOTICE...
PHP Notice: Undefined variable: undefined_variable_test in /wp-content/plugins/error-log-test/error-log-test.php on line 45
[Error Log Test] Triggering E_WARNING...
PHP Warning: include(/path/to/non/existent/file_5f4d3e2a1b2c3.php): failed to open stream in /wp-content/plugins/error-log-test/error-log-test.php on line 50
[Error Log Test] This is a user-generated error!
```

## Troubleshooting

### Can't See Errors in Log Files

1. Verify PHP error logging is enabled: `ini_get('log_errors')`
2. Check error log path: `ini_get('error_log')`
3. Ensure WordPress debugging is enabled in `wp-config.php`
4. Check file permissions on log files
5. Some hosts disable error logging - contact your hosting provider

### Plugin Crashes Site

If the fatal error test crashes your site:
1. Access your site via FTP/SSH
2. Rename the plugin folder in `/wp-content/plugins/`
3. This will deactivate the plugin
4. Rename it back and avoid the fatal error button

### Errors Not Appearing in WordPress Debug Log

Ensure these constants are defined in `wp-config.php` **before** the line `/* That's all, stop editing! */`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- Write permissions for log files

## Frequently Asked Questions

### Is this plugin safe to use on production sites?

While the plugin only generates errors when manually triggered, it's recommended for development and staging environments only. Fatal errors will crash the current page load.

### Why don't I see syntax errors?

True syntax errors prevent PHP files from parsing. The plugin simulates syntax-like errors using eval() which can be caught and logged.

### Can this plugin fix my error logging?

No, this plugin only generates test errors. It helps you verify your error logging is working but doesn't configure logging itself.

### Will this plugin slow down my site?

No, the plugin only runs when you actively click buttons in the admin interface. It has no impact on normal site operation.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

- **Documentation**: [https://octahexa.com/docs/error-log-test](https://octahexa.com/docs/error-log-test)
- **Issues**: [GitHub Issues](https://github.com/octahexa/error-log-test-plugin/issues)
- **Email**: support@octahexa.com

## Changelog

### 1.0.0 - 2025-01-24
- Initial release
- Added admin interface for error generation
- Implemented multiple error types
- Added error log location detection
- Included activation/deactivation logging

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Credits

- **Author**: Team OctaHexa
- **Company**: [OctaHexa](https://octahexa.com)

---

Made with ❤️ by [OctaHexa](https://octahexa.com)
