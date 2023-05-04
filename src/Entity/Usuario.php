<?php
// src/Entity/Usuario.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
/**
 * @ORM\Entity @ORM\Table(name="usuario")
 */
class Usuario implements UserInterface, \Serializable {
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="correo")
     */
    private $correo;

    /**
     * @ORM\Column(type="string", name = "clave")
     */
    private $clave;
    
    /**
     * @ORM\Column(type="string", name = "dni")
     */
    private $dni;

    /**
     * @ORM\Column(type="string", name = "nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", name = "apellidos")
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", name = "telefono")
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer", name = "rol")
     */
    private $rol;

    
    //GETS Y SETS
    public function getCorreo(){
        return $this->correo;
    }
    public function setCorreo($correo){
        $this->correo = $correo;
    }
    
    public function getClave(){
        return $this->clave;
    }
    public function setClave($clave){
        $this->clave = $clave;
    }

    public function getDni(){
        return $this->dni;
    }
    public function setDni($dni){
        $this->dni = $dni;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getApellidos(){
        return $this->apellidos;
    }
    public function setApellidos($apellidos){
        $this->apellidos = $apellidos;
    }

    public function getTelefono(){
        return $this->telefono;
    }
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    public function getRol(){
        return $this->rol;
    }
    public function setRol($rol){
        $this->rol = $rol;
    }

    public function getUserIdentifier()
    {
        return $this->correo;
    }

    //NECESARIOS PARA LA AUTENTICACIÓN
    public function serialize(){
        return serialize(array(
            $this->correo,
            $this->clave,
            $this->dni,
            $this->nombre,
            $this->apellidos,
            $this->telefono,
            $this->rol
        ));
    }

    public function unserialize($serialized){
        list (
            $this->correo,
            $this->clave,
            $this->dni,
            $this->nombre,
            $this->apellidos,
            $this->telefono,
            $this->rol,
            ) = unserialize($serialized);
    }

    public function getRoles(){
        return array('ROLE_USER', 'ROLE_ADMINISTRADOR');          
    }

    public function getPassword(){
        return $this->getClave();
    }

    public function getSalt(){
        return;
    }

    public function getUsername(){
        return $this->getCorreo();
    }

    public function eraseCredentials(){
        return;
    }
}