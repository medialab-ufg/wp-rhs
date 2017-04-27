<?php
/**
 * This class emulates the Drupal 7 behavior to check the passwords
 * of imported users
 * 
 * @author leogermani
 * 
 */ 

Class Drupal7PasswordCheck {

    // These refers to the Drupal constants DRUPAL_MIN_HASH_COUNT, DRUPAL_MAX_HASH_COUNT and DRUPAL_HASH_LENGTH
    var $DRUPAL_MIN_HASH_COUNT = 7;
    var $DRUPAL_MAX_HASH_COUNT = 30;
    var $DRUPAL_HASH_LENGTH = 55;
    
    function __construct() {
        
        add_filter('check_password', array(&$this, 'hook'), 10, 4);
        
    }
    
    function hook($check, $password, $hash, $user_id) {
        
        if (strlen($hash) < 50) // they did not come from Drupal
            return $check;
        
        return $this->user_check_password($password, $hash);
        
    }
    
    
    /**
     * 
     * The methods below were extracted from the Drupal 7 code
     * and modified to call each other (and the constants) via
     * class methods and attributes
     * 
     */ 
    
    function user_check_password($password, $pass) {
      if (substr($pass, 0, 2) == 'U$') {
        // This may be an updated password from user_update_7000(). Such hashes
        // have 'U' added as the first character and need an extra md5().
        $stored_hash = substr($pass, 1);
        $password = md5($password);
      }
      else {
        $stored_hash = $pass;
      }

      $type = substr($stored_hash, 0, 3);
      switch ($type) {
        case '$S$':
          // A normal Drupal 7 password using sha512.
          $hash = $this->_password_crypt('sha512', $password, $stored_hash);
          break;
        case '$H$':
          // phpBB3 uses "$H$" for the same thing as "$P$".
        case '$P$':
          // A phpass password generated using md5.  This is an
          // imported password or from an earlier Drupal version.
          $hash = $this->_password_crypt('md5', $password, $stored_hash);
          break;
        default:
          return FALSE;
      }
      return ($hash && $stored_hash == $hash);
    }

    function _password_crypt($algo, $password, $setting) {
      // Prevent DoS attacks by refusing to hash large passwords.
      if (strlen($password) > 512) {
        return FALSE;
      }
      // The first 12 characters of an existing hash are its setting string.
      $setting = substr($setting, 0, 12);

      if ($setting[0] != '$' || $setting[2] != '$') {
        return FALSE;
      }
      $count_log2 = $this->_password_get_count_log2($setting);
      // Hashes may be imported from elsewhere, so we allow != DRUPAL_HASH_COUNT
      if ($count_log2 < $this->DRUPAL_MIN_HASH_COUNT || $count_log2 > $this->DRUPAL_MAX_HASH_COUNT) {
        return FALSE;
      }
      $salt = substr($setting, 4, 8);
      // Hashes must have an 8 character salt.
      if (strlen($salt) != 8) {
        return FALSE;
      }

      // Convert the base 2 logarithm into an integer.
      $count = 1 << $count_log2;

      // We rely on the hash() function being available in PHP 5.2+.
      $hash = hash($algo, $salt . $password, TRUE);
      do {
        $hash = hash($algo, $hash . $password, TRUE);
      } while (--$count);

      $len = strlen($hash);
      $output = $setting . $this->_password_base64_encode($hash, $len);
      // _password_base64_encode() of a 16 byte MD5 will always be 22 characters.
      // _password_base64_encode() of a 64 byte sha512 will always be 86 characters.
      $expected = 12 + ceil((8 * $len) / 6);
      return (strlen($output) == $expected) ? substr($output, 0, $this->DRUPAL_HASH_LENGTH) : FALSE;
    }

    function _password_get_count_log2($setting) {
      $itoa64 = $this->_password_itoa64();
      return strpos($itoa64, $setting[3]);
    }

    function _password_base64_encode($input, $count) {
      $output = '';
      $i = 0;
      $itoa64 = $this->_password_itoa64();
      do {
        $value = ord($input[$i++]);
        $output .= $itoa64[$value & 0x3f];
        if ($i < $count) {
          $value |= ord($input[$i]) << 8;
        }
        $output .= $itoa64[($value >> 6) & 0x3f];
        if ($i++ >= $count) {
          break;
        }
        if ($i < $count) {
          $value |= ord($input[$i]) << 16;
        }
        $output .= $itoa64[($value >> 12) & 0x3f];
        if ($i++ >= $count) {
          break;
        }
        $output .= $itoa64[($value >> 18) & 0x3f];
      } while ($i < $count);

      return $output;
    }

    function _password_itoa64() {
      return './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    }


}

$drupalPasswordCheck = new Drupal7PasswordCheck();

//$p = '$S$DyM/6WLLpHy6yvYDaGfxCCrHl2onFF/plGQldMkv1InIPK88Lfrf';

