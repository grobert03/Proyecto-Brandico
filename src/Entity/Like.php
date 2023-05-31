<?php
// src/Entity/Like.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity @ORM\Table(name="likes")
 */
class Like {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Publicacion")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id")
     * 
     */
    private $id_post;

    /**
     * @ORM\OneToOne(targetEntity="Comentario")
     * @ORM\JoinColumn(name="id_comentario", referencedColumnName="id")
     * 
     */
    private $id_comentario;

    /**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    private $id_usuario;


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
     * Get the value of id_post
     */ 
    public function getId_post()
    {
        return $this->id_post;
    }

    /**
     * Set the value of id_post
     *
     * @return  self
     */ 
    public function setId_post($id_post)
    {
        $this->id_post = $id_post;

        return $this;
    }

    /**
     * Get the value of id_comentario
     */ 
    public function getId_comentario()
    {
        return $this->id_comentario;
    }

    /**
     * Set the value of id_comentario
     *
     * @return  self
     */ 
    public function setId_comentario($id_comentario)
    {
        $this->id_comentario = $id_comentario;

        return $this;
    }

    /**
     * Get the value of id_usuario
     */ 
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    /**
     * Set the value of id_usuario
     *
     * @return  self
     */ 
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }
}