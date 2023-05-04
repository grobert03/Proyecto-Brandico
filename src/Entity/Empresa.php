<?php
// src/Entity/Empresa.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity @ORM\Table(name="empresa")
 */
class Empresa implements UserInterface, \Serializable{
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
     * @ORM\Column(type="string", name = "cif")
     */
    private $cif;

    /**
     * @ORM\Column(type="string", name = "nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", name = "telefono")
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", name = "direccion")
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", name = "provincia")
     */
    private $provincia;

    
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

    public function getCif(){
        return $this->cif;
    }
    public function setCif($cif){
        $this->cif = $cif;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getTelefono(){
        return $this->telefono;
    }
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    public function getDireccion(){
        return $this->direccion;
    }
    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }

    public function getProvincia(){
        return $this->provincia;
    }
    public function setProvincia($provincia){
        $this->provincia = $provincia;
    }

    
    //NECESARIOS PARA LA AUTENTICACIÓN
    public function serialize(){
        return serialize(array(
            $this->correo,
            $this->clave,
            $this->cif,
            $this->nombre,
            $this->telefono,
            $this->direccion,
            $this->provincia,
        ));
    }

    public function unserialize($serialized){
        list (
            $this->correo,
            $this->clave,
            $this->cif,
            $this->nombre,
            $this->telefono,
            $this->direccion,
            $this->provincia,
            ) = unserialize($serialized);
    }

    /*public function getRoles(){
        return array('ROLE_USER', 'ROLE_ADMINISTRADOR');          
    }*/

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