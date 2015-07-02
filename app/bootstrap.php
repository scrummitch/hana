<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

// Load empty core extension
require SYSPATH.'classes/Kohana'.EXT;

// Set the default time zone.
date_default_timezone_set('Australia/Sydney');

// Set the default locale.
setlocale(LC_ALL, 'en_US.utf-8');

// Enable the Kohana auto-loader.
spl_autoload_register(array('Kohana', 'auto_load'));

// Enable the Kohana auto-loader for unserialization.
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

Kohana::modules(array(
  'app'        => APPPATH,
  'core'       => SYSPATH,
  // 'auth'       => MODPATH.'auth',       // Basic authentication
  // 'cache'      => MODPATH.'cache',      // Caching with multiple backends
  // 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
  // 'database'   => MODPATH.'database',   // Database access
  // 'image'      => MODPATH.'image',      // Image manipulation
  // 'minion'     => MODPATH.'minion',     // CLI Tasks
  // 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
  // 'unittest'   => MODPATH.'unittest',   // Unit testing
  // 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
));

// Set the default language
I18n::lang('en-us');

if ( ! function_exists('__'))
{
  // I18n translate alias function.
  function __($string, array $values = NULL, $lang = 'en-us')
  {
    return I18n::translate($string, $values, $lang);
  }
}

if (isset($_SERVER['SERVER_PROTOCOL']))
{
  // Replace the default protocol.
  HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

// Set the cookie salt
Cookie::$salt = 'change-me-please';

// Set the default upload directory
Upload::$default_directory = DOCROOT.'../uploads';

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
  Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
  'base_url'   => '/',
));

// Attach the file write to logging. Multiple writers are supported.
Kohana::$log->attach(new Log_File(DOCROOT.'../logs'));

// Attach a file reader to config. Multiple readers are supported.
Kohana::$config->attach(new Config_File);

// Initialize modules
Kohana::init_modules();

require_once VENDORPATH.'autoload.php';

// Load the routes.
require_once Kohana::find_file(NULL, 'routes');
