//src/Service/CaracterService.php
namespace App\Service;
use DateTime;
use App\Entity\Caracter;
class CaracterService implements CaracterServiceInterface
{
    public function create(): Caracter
    {
        $character = new Caracter();
        $character
            ->setKind('Dame')
            ->setName('Anardil')
            ->setSurname('Amie du Soleil')
            ->setCaste('Magicien')
            ->setKnowledge('Sciences')
            ->setIntelligence(130)
            ->setLife(11)
            ->setImage('/images/cartes/dames/anardil.jpg')
            ->setCreated(new \DateTime())
        ;
        return $character;
    }
}