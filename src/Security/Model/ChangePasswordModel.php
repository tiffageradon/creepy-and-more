<?php
namespace App\Security\Model;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordModel
{
    /**
     * @var string
     */
    private $oldPassword;
    /**
     * @var string
     * @Assert\Length(min="8")
     */
    private $newPassword;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
        
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->password = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return boolean
     * @Assert\IsTrue(message="Le nouveau mot de passe ne peut pas être l'ancien, abruti!!!")
     * Ceci est une validation au niveau de la classe
     */
    public function isPasswordNew()
    {
        return $this->getNewPassword() != $this->getOldPassword();
    }
}
