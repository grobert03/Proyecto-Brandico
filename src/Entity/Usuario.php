<?php
// src/Entity/Usuario.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
/**
 * @ORM\Entity @ORM\Table(name="usuarios")
 */
class Usuario implements UserInterface, \Serializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="correo")
     */
    private $correo;

    /**
     * @ORM\Column(type="string", name = "clave")
     */
    private $clave;
    
    /**
     * @ORM\Column(type="string", name = "nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", name="foto")
     */
    private $foto;

    /**
     * @ORM\Column(type="boolean", name="es_empresa")
     */
    private $es_empresa;

    /**
     * @ORM\Column(type="boolean", name="rol")
     */
    private $rol;

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

    /**
     * @ORM\Column(type="string", name = "recuperacion")
     */
    private $recuperacion;
    /**
     * @ORM\Column(type="datetimetz", name = "expiracion_rec")
     */
    private $expiracion_rec;
    
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

    /**
     * Get the value of es_empresa
     */ 
    public function getEs_empresa()
    {
        return $this->es_empresa;
    }

    /**
     * Set the value of es_empresa
     *
     * @return  self
     */ 
    public function setEs_empresa($es_empresa)
    {
        $this->es_empresa = $es_empresa;

        return $this;
    }

    /**
     * Get the value of provincia
     */ 
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set the value of provincia
     *
     * @return  self
     */ 
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get the value of direccion
     */ 
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */ 
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of foto
     */ 
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */ 
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    public function getRol(){
        return $this->rol;
    }
    public function setRol($rol){
        $this->rol = $rol;
    }

    public function getUserIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the value of recuperacion
     */ 
    public function getRecuperacion()
    {
        return $this->recuperacion;
    }

    /**
     * Set the value of recuperacion
     *
     * @return  self
     */ 
    public function setRecuperacion($recuperacion)
    {
        $this->recuperacion = $recuperacion;

        return $this;
    }

    /**
     * Get the value of expiracion_rec
     */ 
    public function getExpiracion_rec()
    {
        return $this->expiracion_rec;
    }

    /**
     * Set the value of expiracion_rec
     *
     * @return  self
     */ 
    public function setExpiracion_rec($expiracion_rec)
    {
        $this->expiracion_rec = $expiracion_rec;

        return $this;
    }

    //NECESARIOS PARA LA AUTENTICACIÓN
    public function serialize(){
        return serialize(array(
            $this->id,
            $this->correo,
            $this->clave,
            $this->nombre,
            $this->foto,
            $this->es_empresa,
            $this->rol,
            $this->telefono,
            $this->direccion,
            $this->provincia,
            $this->recuperacion,
            $this->expiracion_rec
        ));
    }

    public function unserialize($serialized){
        list (
            $this->id,
            $this->correo,
            $this->clave,
            $this->nombre,
            $this->foto,
            $this->es_empresa,
            $this->rol,
            $this->telefono,
            $this->direccion,
            $this->provincia,
            $this->recuperacion,
            $this->expiracion_rec
            ) = unserialize($serialized);
    }

    /*public function getRoles(){
        return array('ROLE_USER', 'ROLE_ADMINISTRADOR');          
    }*/

    public function getRoles()
    {
        if($this->rol==1)
			return array('ROLE_USER', 'ROLE_ADMIN');
        else
            return array('ROLE_USER', 'ROLE_USUARIO');            
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

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}