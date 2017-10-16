<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Helper\Crypto;

/**
 * @ORM\Entity
 * @ORM\Table(name="pw")
 */
class PW {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $salt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    public function __construct() {
        $this->salt = $this->generateSalt();
    }

    /**
     * Set id
     *
     * @param int $id
     * @return PW
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PW
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return PW
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return PW
     */
    public function setPassword($password) {
        $this->password = Crypto::encrypt($password, hex2bin($this->salt));

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return Crypto::decrypt($this->password, hex2bin($this->salt));
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return PW
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return PW
     */
    public function setNotes($notes) {
        $this->name = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    private function generateSalt() {
        return bin2hex(openssl_random_pseudo_bytes(64));
    }
}