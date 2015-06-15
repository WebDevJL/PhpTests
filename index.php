<?php
include_once 'UUID.php';

function TestEncryption($key, $values) {
  $enc = MCRYPT_RIJNDAEL_128;
  $mode = MCRYPT_MODE_CBC;
  $iv = mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM);
  $output = '<h3>Encryption tests</h3>';
  foreach ($values as $value) {
    $output .= '<h4>Value to encrypt/decrypt: ' . utf8_decode($value) . '</h4><ol>';
    $encrypted = mcrypt_encrypt($enc, $key, $value, $mode, $iv);
    $output .= "<li>encrypted => " . $encrypted . "</li>";
    $encrypted_encoded = base64_encode($encrypted);
    $output .= "<li>encrypted_encoded base64 => " . $encrypted_encoded . "</li>";
    $encrypted_decoded = base64_decode($encrypted_encoded, TRUE);
    $output .= "<li>encrypted_decoded base 64 => " . $encrypted_decoded . "</li>";
    $decrypted = mcrypt_decrypt($enc, $key, $encrypted_decoded, $mode, $iv);
    $output .= "<li>decrypted => " . utf8_decode($decrypted) . "</li>";
    if (!strcmp($value, $decrypted)) {
      $output .= "<li><b>Problem in encryption!!!</b></li>";
    }
    $output .= "</ol>";

    echo $output;
  }
}

function TestHashing($key, $values, $length = NULL) {
  $output = '<h3>Hashing tests</h3><ol>';
  foreach ($values as $value) {
    $guid = UUID::v4();
    $output .= '<li>Value to hash: ' . utf8_decode($value) . ' => ' .
        '<ul><li>' . $key . '</li>' .
        '<li>' . $guid . '</li>' .
        '<li>' . HashValue($key, $guid, $value, $length) . '</li>' .
        '<li>' . strlen(HashValue($key, $guid, $value, $length)) . '</li>' .
        '</ul></li>';
  }
  $output .= '</ol>';
  echo $output;
}

function HashValue($commonSalt, $dynamicSalt, $data, $hashLength = NULL) {
  return
      !is_null($hashLength) && is_int($hashLength) ?
      substr(sha1($commonSalt . $dynamicSalt . $data), 0, $hashLength) :
      sha1($commonSalt . $dynamicSalt . $data);
}
?>
<html>
  <head>

  </head>
  <body>
    <?php
    $values = ["1111", "&é\"'(-è_", ""];
    $key = "g496ljl683yfidzju2k94f1751lo7wsw";

    TestEncryption($key, $values);
    TestHashing($key, $values);
    TestHashing($key, $values, 20);
    ?>
  </body>
</html>