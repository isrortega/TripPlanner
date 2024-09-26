<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Form\DriverType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/drivers')]
class DriverController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'driver_index', methods: ['GET'])]
    public function index(): Response
    {
        $drivers = $this->entityManager->getRepository(Driver::class)->findAll();
        return $this->render('driver/index.html.twig', [
            'drivers' => $drivers,
        ]);
    }

    #[Route('/new', name: 'driver_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $driver = new Driver();
        $form = $this->createForm(DriverType::class, $driver);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($driver);
            $this->entityManager->flush();

            $this->addFlash('success', 'Driver created successfully!');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/new.html.twig', [
            'driver' => $driver,
            'form' => $form->createView(),
        ]);
    }

     #[Route('/{id}', name: 'driver_show', methods: ['GET'])]
    public function show(Driver $driver): Response
    {
        return $this->render('driver/show.html.twig', [
            'driver' => $driver,
        ]);
    }

    #[Route('/{id}/edit', name: 'driver_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Driver $driver): Response
    {
        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Driver updated successfully!');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'driver_delete', methods: ['POST'])]
    public function delete(Request $request, Driver $driver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$driver->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($driver);
            $this->entityManager->flush();

            $this->addFlash('info', 'Driver deleted successfully.');
        }else {
            $this->addFlash('danger', 'Could not delete driver');
        }

        return $this->redirectToRoute('driver_index');
    }
}
