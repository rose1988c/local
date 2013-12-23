<?php
echo $d = _encrypt('236564333');

echo " \n ";

echo _decrypt('jJYUkPuOqJsi8GAvud2kN1fmeKZFvPszCdHTRI2nINI=');

/**
 * 解密
 *
 * @param string $encryptedText 已加密字符串
 * @param string $key  密钥
 * @return string
 */
function _decrypt($encryptedText,$key = 'C952135661B6D95EB5A3E37F7EFC0F18')
{
    $cryptText = base64_decode($encryptedText);
    $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
    $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $cryptText, MCRYPT_MODE_ECB, $iv);
    return trim($decryptText);
}

/**
 * 加密
 *
 * @param string $plainText	未加密字符串
 * @param string $key		 密钥
 */
function _encrypt($plainText,$key = 'C952135661B6D95EB5A3E37F7EFC0F18')
{
    $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
    $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plainText, MCRYPT_MODE_ECB, $iv);
    return trim(base64_encode($encryptText));
}
