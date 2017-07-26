# wordpress-logger-simple
Simple WordPress Logger

## How to use
```
global $wp_logger;
$wp_logger->logMessage("Your log message");
```

### Change log file
```
add_filter( 'wp_logger_file', 'set_logger_file' );
function set_logger_file() {
    return "my_log.txt";
}
```
