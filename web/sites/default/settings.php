<?php
/**
 * @file
 * Platform.sh example settings.php file for Drupal 8.
 */
// Install with the 'standard' profile for this example.
$settings['install_profile'] = 'thunder';

// You should modify the hash_salt so that it is specific to your application.
$settings['hash_salt'] = 'YbbphNf_6QiVMH_tX0yseEY_GirdkhtKyTLXnjm0jvzUS2O1YDremmjRmha1pQL1JY_3ajPuSQ';
/**
 * Default Drupal 8 settings.
 *
 * These are already explained with detailed comments in Drupal's
 * default.settings.php file.
 *
 * See https://api.drupal.org/api/drupal/sites!default!default.settings.php/8
 */
$databases = array();
$config_directories = array();
$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = __DIR__ . '/services.yml';

// Define a config sync directory outside the document root.
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config_sync';

// Override paths for config files in Platform.sh.
if (isset($_ENV['PLATFORM_APP_DIR'])) {
  $config_directories[CONFIG_SYNC_DIRECTORY] = $_ENV['PLATFORM_APP_DIR'] . '/config';
}

// Set trusted hosts based on real Platform.sh routes.
if (isset($_ENV['PLATFORM_ROUTES'])) {
  $routes = json_decode(base64_decode($_ENV['PLATFORM_ROUTES']), TRUE);
  $settings['trusted_host_patterns'] = array();
  foreach ($routes as $url => $route) {
    $host = parse_url($url, PHP_URL_HOST);
    if ($host !== FALSE && $route['type'] == 'upstream' && $route['upstream'] == $_ENV['PLATFORM_APPLICATION_NAME']) {
      $settings['trusted_host_patterns'][] = '^' . preg_quote($host) . '$';
    }
  }
  $settings['trusted_host_patterns'] = array_unique($settings['trusted_host_patterns']);
}

// Because we're using the Composer toolstack, we replicate the necessary local
// settings file for Drupal here.
if (getenv("PLATFORM_RELATIONSHIPS")) {
  // Configure relationships.
  $relationships = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS']), TRUE);
  foreach ($relationships['database'] as $endpoint) {
    $database = array(
      'driver' => $endpoint['scheme'],
      'database' => $endpoint['path'],
      'username' => $endpoint['username'],
      'password' => $endpoint['password'],
      'host' => $endpoint['host'],
    );
    if (!empty($endpoint['query']['compression'])) {
      $database['pdo'][PDO::MYSQL_ATTR_COMPRESS] = TRUE;
    }
    if (!empty($endpoint['query']['is_master'])) {
      $databases['default']['default'] = $database;
    }
    else {
      $databases['default']['slave'][] = $database;
    }
  }

  // Add migrate relationship.
  if (isset($relationships['migrate'])) {
    foreach ($relationships['migrate'] as $endpoint) {
      $database = array(
        'driver' => $endpoint['scheme'],
        'database' => $endpoint['path'],
        'username' => $endpoint['username'],
        'password' => $endpoint['password'],
        'host' => $endpoint['host'],
      );
      $databases['migrate']['default'] = $database;
    }
  }

  $routes = json_decode(base64_decode($_ENV['PLATFORM_ROUTES']), TRUE);
  if (!isset($conf['file_private_path'])) {
    if(!$application_home = getenv('PLATFORM_APP_DIR')) {
      $application_home = '/app';
    }
    $conf['file_private_path'] = $application_home . '/private';
    $conf['file_temporary_path'] = $application_home . '/tmp';
  }
  $variables = json_decode(base64_decode($_ENV['PLATFORM_VARIABLES']), TRUE);

  // Set redis configuration.
  if (!empty($relationships['redis'][0]) && extension_loaded('redis')) {
    $redis = $relationships['redis'][0];

    $settings['cache']['default'] = 'cache.backend.redis';
    $settings['redis.connection']['host'] = $redis['host'];
    $settings['redis.connection']['port'] = $redis['port'];

    # Enable the redis cache checksum service.
    $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

    // Use redis for container cache.
    $settings['bootstrap_container_definition'] = [
      'parameters' => [],
      'services' => [
        'redis.factory' => [
          'class' => 'Drupal\redis\ClientFactory',
        ],
        'cache.backend.redis' => [
          'class' => 'Drupal\redis\Cache\CacheBackendFactory',
          'arguments' => ['@redis.factory', '@cache_tags_provider.container'],
        ],
        'cache.container' => [
          'class' => '\Drupal\redis\Cache\PhpRedis',
          'factory' => ['@cache.backend.redis', 'get'],
          'arguments' => ['container'],
        ],
        'cache_tags_provider.container' => [
          'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
          'arguments' => ['@redis.factory'],
        ],
      ],
    ];

  }

  // Default PHP settings.
  ini_set('session.gc_probability', 1);
  ini_set('session.gc_divisor', 100);
  ini_set('session.gc_maxlifetime', 200000);
  ini_set('session.cookie_lifetime', 2000000);
  ini_set('pcre.backtrack_limit', 200000);
  ini_set('pcre.recursion_limit', 200000);
}

// Always set the fast backend for bootstrap, default, discover and config,
// otherwise this gets lost when redis is enabled.
$settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['default'] = 'cache.backend.chainedfast';

$settings['cache_prefix'] = 'th_';

// Local settings. These allow local development environments to use their own
// database connections rather than the Platform-only settings above.
if (file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}
