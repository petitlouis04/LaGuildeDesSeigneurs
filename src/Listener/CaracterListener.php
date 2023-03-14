<?php
namespace App\Listener;
use App\Event\CaracterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
class CharacterListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Événements que l'on souhaite écouter
        return array(
            CaracterEvent::CHARACTER_CREATED => 'characterCreated',
            CaracterEvent::CHARACTER_MODIFIED => 'characterModified',
            CaracterEvent::CHARACTER_CREATED_POST_DATABASE => 'characterCreatedPostDatabase', // Nom de la méthode appelée
        );
    }
    // Méthode appelée lorsque l'objet est créé
    public function characterCreated($event)
    {
        // Réception de l'objet Character avec le getter
        $character = $event->getCharacter();
        // Modification de l'objet
        if("Dame" === $character->getKind()) {
                        $character->setLife($character->getLife() + 2);
                    } elseif ("Ennemi" === $character->getKind()) {
                        $character->setLife($character->getLife() - 2);
                               }

        $character->setIntelligence(250);
    }

    public function characterModified($event){
        $character = $event->getCharacter();

        $character->setMirian($character->getMarian() -10);
    }
}