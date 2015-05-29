<?php
namespace Bitrix\Security;

use Bitrix\Main\Text\String;


class Random
{
	const RANDOM_BLOCK_LENGTH = 64;
	// ToDo: In future versions (PHP >= 5.6.0) use shift to the left instead this s**t
	const ALPHABET_NUM = 1;
	const ALPHABET_ALPHALOWER = 2;
	const ALPHABET_ALPHAUPPER = 4;
	const ALPHABET_SPECIAL = 8;
	const ALPHABET_ALL = 15;

	protected $alphabet = array(
		self::ALPHABET_NUM => '0123456789',
		self::ALPHABET_ALPHALOWER => 'abcdefghijklmnopqrstuvwxyz',
		self::ALPHABET_ALPHAUPPER => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		self::ALPHABET_SPECIAL => ',.#!*%$:-^@{}[]()_+=<>?&;'
	);

	/**
	 * Returns random (if possible) alphanum string
	 *
	 * @param int $length Result string length.
	 * @param bool $caseSensitive Generate case sensitive random string (e.g. `SoMeRandom1`).
	 * @return string
	 */
	public function getString($length, $caseSensitive = false)
	{
		$alphabet = self::ALPHABET_NUM | self::ALPHABET_ALPHALOWER;
		if ($caseSensitive)
			$alphabet |= self::ALPHABET_ALPHAUPPER;

		return $this->getStringByAlphabet($length, $alphabet);
	}

	/**
	 * Returns random (if possible) ASCII string for a given alphabet mask (@see self::ALPHABET_ALL)
	 *
	 * @param int $length Result string length.
	 * @param string $alphabet Alpabet masks (e.g. Random::ALPHABET_NUM|Random::ALPHABET_ALPHALOWER).
	 * @return string
	 */
	public function getStringByAlphabet($length, $alphabet)
	{
		$charsetList = $this->getCharsetsforAlphabet($alphabet);
		$charsetVariants = strlen($charsetList);
		$randomSequence = $this->getBytes($length);

		$result = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomNumber = ord($randomSequence[$i]);
			$result .= $charsetList[$randomNumber % $charsetVariants];
		}
		return $result;
	}

	/**
	 * Returns random (if possible) byte string
	 *
	 * @param int $length Result byte string length.
	 * @return string
	 */
	public function getBytes($length)
	{
		$backup = null;

		if (static::isOpensslAvailable())
		{
			$bytes = openssl_random_pseudo_bytes($length, $strong);
			if ($bytes && String::getBinaryLength($bytes) >= $length)
			{
				if ($strong)
					return String::getBinarySubstring($bytes, 0, $length);
				else
					$backup = $bytes;
			}
		}

		if (function_exists('mcrypt_create_iv'))
		{
			$bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
			if ($bytes && String::getBinaryLength($bytes) >= $length)
			{
				return String::getBinarySubstring($bytes, 0, $length);
			}
		}

		if ($file = @fopen('/dev/urandom','rb'))
		{
			$bytes = @fread($file, $length + 1);
			@fclose($file);
			if ($bytes && String::getBinaryLength($bytes) >= $length)
			{
				return String::getBinarySubstring($bytes, 0, $length);
			}
		}

		if ($backup && String::getBinaryLength($backup) >= $length)
		{
			return String::getBinarySubstring($backup, 0, $length);
		}

		$bytes = '';
		while (String::getBinaryLength($bytes) < $length)
		{
			$bytes .= $this->getPseudoRandomBlock();
		}

		return String::getBinarySubstring($bytes, 0, $length);
	}

	/**
	 * Returns pseudo random block
	 *
	 * @return string
	 */
	protected function getPseudoRandomBlock()
	{
		global $APPLICATION;

		if (static::isOpensslAvailable())
		{
			$bytes = openssl_random_pseudo_bytes(static::RANDOM_BLOCK_LENGTH);
			if ($bytes && String::getBinaryLength($bytes) >= static::RANDOM_BLOCK_LENGTH)
			{
				return String::getBinarySubstring($bytes, 0, static::RANDOM_BLOCK_LENGTH);
			}
		}

		$bytes = '';
		for ($i=0; $i < static::RANDOM_BLOCK_LENGTH; $i++)
		{
			$bytes .= pack('S', mt_rand(0,0xffff));
		}

		$bytes .= $APPLICATION->getServerUniqID();

		return hash('sha512', $bytes, true);
	}
	
	/**
	 * Checks OpenSSL available
	 *
	 * @return bool
	 */
	protected function isOpensslAvailable()
	{
		static $result = null;
		if ($result === null)
		{
			$result = (
				function_exists('openssl_random_pseudo_bytes')
				&& (
					// PHP have strange behavior for "openssl_random_pseudo_bytes" on older PHP versions
					!\CSecuritySystemInformation::isRunOnWin()
					|| version_compare(phpversion(),"5.4.0",">=")
				)
			);
		}

		return $result;
	}

	/**
	 * Returns strings with charsets based on alpabet mask (see $this->alphabet)
	 *
	 * Simple example:
	 * <code>
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_NUM|static::ALPHABET_ALPHALOWER);
	 * //output: 0123456789abcdefghijklmnopqrstuvwxyz
	 *
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_SPECIAL|static::ALPHABET_ALPHAUPPER);
	 * //output:ABCDEFGHIJKLMNOPQRSTUVWXYZ,.#!*%$:-^@{}[]()_+=<>?&;
	 *
	 * echo $this->getCharsetsforAlphabet(static::ALPHABET_ALL);
	 * //output: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,.#!*%$:-^@{}[]()_+=<>?&;
	 * </code>
	 *
	 * @param string $alphabet Alpabet masks (e.g. static::ALPHABET_NUM|static::ALPHABET_ALPHALOWER).
	 * @return string
	 */
	protected function getCharsetsforAlphabet($alphabet)
	{
		$result = '';
		foreach ($this->alphabet as $mask => $value)
		{
			if (!($alphabet & $mask))
				continue;

			$result .= $value;
		}

		return $result;
	}

}