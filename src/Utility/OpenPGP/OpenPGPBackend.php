<?php
declare(strict_types=1);

namespace App\Utility\OpenPGP;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\InternalErrorException;

abstract class OpenPGPBackend implements OpenPGPBackendInterface
{
    use OpenPGPCommonAssertsTrait;

    /**
     * @var string|null fingerprint of the key set to decrypt
     */
    protected $_decryptKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to encrypt
     */
    protected $_encryptKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to encrypt
     */
    protected $_signKeyFingerprint;

    /**
     * @var string|null fingerprint of the key set to verify signature
     */
    protected $_verifyKeyFingerprint;

    /**
     * Constructor.
     *
     * @throws \Cake\Core\Exception\Exception
     */
    public function __construct()
    {
        $this->_encryptKeyFingerprint = null;
        $this->_decryptKeyFingerprint = null;
        $this->_signKeyFingerprint = null;
    }

    /**
     * Import server key in keyring
     *
     * @throws \Cake\Http\Exception\InternalErrorException if server key is undefined or invalid
     * @return void
     */
    public function importServerKeyInKeyring(): void
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $keyFilePath = Configure::read('passbolt.gpg.serverKey.private');

        // If it's not in keyring try to import it
        // Check if file containing the private key exist
        if ($keyFilePath === null) {
            throw new InternalErrorException('The secret key file is not defined.');
        }
        if (!file_exists($keyFilePath)) {
            $msg = __('The OpenPGP server key defined in the config is not found in the file system.');
            throw new InternalErrorException($msg);
        }
        $privateKey = file_get_contents($keyFilePath);
        if ($privateKey === false) {
            $msg = __('The OpenPGP server key defined in the config cannot be opened.');
            throw new InternalErrorException($msg);
        }
        if (!$this->isParsableArmoredPrivateKey($privateKey)) {
            $msg = __('The OpenPGP server key defined on file is not a valid private key.');
            throw new InternalErrorException($msg);
        }

        // try to import it
        $this->importKeyIntoKeyring($privateKey);
        if (!$this->isKeyInKeyring($fingerprint)) {
            $msg = __('There is an issue with the OpenPGP server key.') . ' ';
            $msg .= __('The fingerprint does not match the one associated with the key on file.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * Get the gpg marker.
     *
     * @param string $armored ASCII armored gpg data
     * @return mixed
     * @throws \Cake\Core\Exception\Exception
     */
    protected function getGpgMarker(string $armored)
    {
        $isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $armored, $values);
        if (!$isMarker || !isset($values[2])) {
            throw new Exception(__('No OpenPGP marker found.'));
        }

        return $values[2];
    }

    /**
     * Removes all keys which were set for decryption before
     *
     * @return void
     */
    public function clearDecryptKeys(): void
    {
        $this->_decryptKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for signing before
     *
     * @return void
     */
    public function clearSignKeys(): void
    {
        $this->_signKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for encryption before
     *
     * @return void
     */
    public function clearEncryptKeys(): void
    {
        $this->_encryptKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for verify before
     *
     * @return void
     */
    public function clearVerifyKeys(): void
    {
        $this->_verifyKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set before
     *
     * @return void
     */
    public function clearKeys(): void
    {
        $this->clearDecryptKeys();
        $this->clearEncryptKeys();
        $this->clearSignKeys();
        $this->clearVerifyKeys();
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return void
     */
    public function setVerifyKeyFromFingerprint(string $fingerprint): void
    {
        $this->_verifyKeyFingerprint = $fingerprint;
    }
}
