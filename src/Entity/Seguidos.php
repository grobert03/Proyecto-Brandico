<?php
// src/Entity/Seguidos.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity @ORM\Table(name="seguidos")
 */
class Seguidos implements UserInterface, \Serializable{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="seguidor")
     */
    private $seguidor;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="seguido")
     */
    private $seguido;

    
    //GETS Y SETS
    public function getSeguidor(){
        return $this->seguidor;
    }
    public function setSeguidor($seguidor){
        $this->seguidor = $seguidor;
    }
    
    public function getSeguido(){
        return $this->seguido;
    }
    public function setSeguido($seguido){
        $this->seguido = $seguido;
    }

}