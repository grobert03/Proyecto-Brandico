<?php
// src/Entity/Seguidor.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity @ORM\Table(name="seguidores")
 */
class Seguidor  {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id_seguido", referencedColumnName="id")
     */
    private $id_seguido;
    /**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id_seguidor", referencedColumnName="id")
     */
    private $id_seguidor;
    
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


    /**
     * Get the value of id_seguidor
     */ 
    public function getId_seguidor()
    {
        return $this->id_seguidor;
    }

    /**
     * Set the value of id_seguidor
     *
     * @return  self
     */ 
    public function setId_seguidor($id_seguidor)
    {
        $this->id_seguidor = $id_seguidor;

        return $this;
    }

    /**
     * Get the value of id_seguido
     */ 
    public function getId_seguido()
    {
        return $this->id_seguido;
    }

    /**
     * Set the value of id_seguido
     *
     * @return  self
     */ 
    public function setId_seguido($id_seguido)
    {
        $this->id_seguido = $id_seguido;

        return $this;
    }

    
}