<?php
$checks = [];
$checks['PHP Version'] = PHP_VERSION;
$checks['PHP SAPI']    = php_sapi_name();

// MariaDB check
try {
    $pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS')
    );
    $checks['MariaDB'] = 'Connected successfully';
} catch (Exception $e) {
    $checks['MariaDB'] = 'Failed: ' . $e->getMessage();
}

// Redis check
try {
    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST'), (int)getenv('REDIS_PORT'));
    $redis->set('docker_test', 'ok');
    $checks['Redis'] = $redis->get('docker_test') === 'ok'
        ? 'Connected and read/write verified'
        : 'Connected but read/write failed';
} catch (Exception $e) {
    $checks['Redis'] = 'Failed: ' . $e->getMessage();
}

$checks['OPcache']  = function_exists('opcache_get_status') ? 'Enabled' : 'Not loaded';
$checks['Imagick']  = extension_loaded('imagick') ? 'Loaded' : 'Not loaded';
$checks['GD']       = extension_loaded('gd') ? 'Loaded' : 'Not loaded';

echo "<h2>Docker LEMP Stack Status</h2><ul>";
foreach ($checks as $k => $v) {
    echo "<li><strong>{$k}:</strong> {$v}</li>";
}
echo "</ul>";
echo "<p><small>Stack: Nginx + PHP-FPM 8.2 + MariaDB 10.11 + Redis 7 (Docker Compose)</small></p>";
