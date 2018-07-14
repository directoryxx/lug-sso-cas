<?php
namespace SSO;

use phpCAS;

// ------------------------------------------------------------------------
//  Constants
// ------------------------------------------------------------------------

/**
 * CAS server host address
 */
define('CAS_SERVER_HOST', 'cas.lug-surabaya.com');

/**
 * CAS server uri
 */
define('CAS_SERVER_URI', '/cas');

/**
 * CAS server port
 */
define('CAS_SERVER_PORT', 443);

// ------------------------------------------------------------------------
//  CAS Initialization
// ------------------------------------------------------------------------

// ONLY DO THIS IF phpCAS EXISTS (i.e. installing via Composer). Thanks to Fariskhi for noticing the bug.
if (class_exists('phpCAS')) {
  /**
   * Create phpCAS client
   */
  phpCAS::client(CAS_VERSION_3_0, CAS_SERVER_HOST, CAS_SERVER_PORT, CAS_SERVER_URI);

  /**
   * Set no validation.
   */
  phpCAS::setNoCasServerValidation();
}

class SSO
{

  /**
   * Authenticate the user.
   *
   * @return bool Authentication
   */
  public static function authenticate() {
    return phpCAS::forceAuthentication();
  }

  /**
   * Check if the user is already authenticated.
   *
   * @return bool Authentication
   */
  public static function check() {
    return phpCAS::checkAuthentication();
  }

  /**
   * Logout from SSO with URL redirection options
   */
  public static function logout($url='') {
    if ($url === '')
      phpCAS::logout();
    else
      phpCAS::logout(['url' => $url]);
  }

  /**
   * Returns the authenticated user.
   *
   * @return Object User
   */
  public static function getUser() {
    $details = phpCAS::getAttributes();

    // Create new user object, initially empty.
    $user = new \stdClass();
    $user->username = phpCAS::getUser();    

    return $details;
  }

  // ----------------------------------------------------------
  // Manual Installation Stuff
  // ----------------------------------------------------------

  /**
   * Sets the path to CAS.php. Use only when not installing via Composer.
   *
   * @param string $cas_path Path to CAS.php
   */
  public static function setCASPath($cas_path) {
    require $cas_path;

    // Initialize CAS client.
    self::init();
  }

  /**
   * Initialize CAS client. Called by setCASPath().
   */
  private static function init() {
    // Create CAS client.
    phpCAS::client(CAS_VERSION_3_0, CAS_SERVER_HOST, CAS_SERVER_PORT, CAS_SERVER_URI);

    // Set no validation.
    phpCAS::setNoCasServerValidation();
  }

}
