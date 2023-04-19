<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class Usuario implements UserInterface, \Serializable {
    /** @ORM\Id @ORM\Column(type="integer") */
    private $id;
    /** @ORM\Column(type="string") */
    private $nombre;
    /** @ORM\Column(type="string") */
    private $apellido;
    /** @ORM\Column(type="string") */
    private $correo;
    /** @ORM\Column(type="string") */
    private $clave;
    /** @ORM\Column(type="integer") */
    private $rol;
    /** @ORM\Column(type="string") */
    private $fotoPerfil;


    public function getUsuario() {
        return $this->nombre;
    }

    public function getClave() {
        return $this->clave;
    }

    public function setClave($password) {
        $this->clave = $password;
        return $this;
    }

    public function getCorreo() {
        return $this->correo;
    }

    // NECESARIOS PARA LA AUTENTICACIÓN

    public function getRoles() {
        return ['ROLE_USER', 'ROLE_MEDICO'];
    }

    public function getPassword() {
        return $this->getClave();
    }

    public function getSalt() {
        return ;
    }

    public function getUsername() {
        return $this->getUsuario();
    }

    public function eraseCredentials() {
        return;
    }

    public function serialize(){
        return serialize(array(
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->clave,
            $this->rol,
            $this->fotoPerfil
        ));
    }
	
    public function unserialize($serialized){
        list (
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->clave,
            $this->rol,
            $this->fotoPerfil
            ) = unserialize($serialized);
    }


}