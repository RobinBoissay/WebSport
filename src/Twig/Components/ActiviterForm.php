<?php

namespace App\Twig\Components;

use App\Entity\Activiter;
use App\Entity\TypeActiviter;
use App\Form\ActiviterType;
use App\Form\ActiviterType2;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ActiviterForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'entity', writable: true)]
    public ?Activiter $activiter = null;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ActiviterType::class, $this->activiter ?? new Activiter(), []);
    }



    #[LiveAction]
    public function save(): Response
    {
        $this->submitForm(); // Valide et hydrate l'entité avec les données du formulaire

        if (!$this->getForm()->isValid()) {
            // Ne rien faire si le formulaire est invalide
        }else{
            $form = $this->getForm();
            $activiter = $form->getData();
            $activiter->setUser($this->getUser());
            // Enregistrement en base de données
            $this->entityManager->persist($activiter);
            $this->entityManager->flush();

            // Optionnel : Ajouter un message flash pour l'utilisateur
            return $this->redirectToRoute('app_activiter');
        }

    }
}