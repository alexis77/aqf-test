<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted({"ROLE_USER"})
 */
class DefaultController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     * @IsGranted({"ROLE_USER"})
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('mission_list');
        }

        return $this->redirectToRoute('app_login');
    }
}
