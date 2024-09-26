<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
        private DriverRepository $driverRepository,
        private TripRepository $tripRepository
    )
    {
    }

    #[Route('/', name: 'home')]
    #[Route('/trips', name: 'trip_index')]
    public function index(): Response
    {
        $trips = $this->entityManager->getRepository(Trip::class)->findAll();
        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    #[Route('/trips/book', name: 'trip_book', methods: ['GET'])]
    public function bookATrip(): Response
    {
        return $this->render('trip/book.html.twig', []);
    }


    #[Route('/trips/available-vehicles', name: 'available_vehicles', methods: ['GET'])]
    public function availableVehicles(Request $request): JsonResponse
    {
        $date = $this->getDateFromRequest($request);

        $availableVehicles = $this->vehicleRepository->findAvailableOnDate($date);

        $html = $this->renderView('trip/partials/_available_vehicles.html.twig', ['availableVehicles' => $availableVehicles]);

        return new JsonResponse(['html' => $html]);
    }

    #[Route('/trips/available-drivers', name: 'available_drivers', methods: ['GET'])]
    public function availableDrivers(Request $request): JsonResponse
    {
        $date = $this->getDateFromRequest($request);
        $vehicleId = $request->query->get('vehicle');

        $vehicle = $this->vehicleRepository->find($vehicleId);
        $drivers = $this->driverRepository->findAvailableOnDateAndLicense($date, $vehicle->getLicenseRequired());

        $html = $this->renderView('trip/partials/_available_drivers.html.twig', ['drivers' => $drivers]);

        return new JsonResponse(['html' => $html]);
    }

    private function getDateFromRequest(Request $request):?\DateTimeInterface
    {
        $dateString = $request->query->get('date');
        if (!$dateString) {
            $data = json_decode($request->getContent(), true);
            $dateString = $data['date'];
        }
        if (!$dateString) {
            return $this->json(['error' => 'Date not provided'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $date = \DateTime::createFromFormat('Y-m-d', $dateString); //new \DateTime($dateString);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
        }
        return $date;
    }

    #[Route('/trips/booking', name: 'trip_booking', methods: ['POST'])]
    public function bookTrip(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $date = $this->getDateFromRequest($request);
        $vehicleId = $data['vehicle'];
        $driverId = $data['driver'];

        $vehicle = $this->vehicleRepository->find($vehicleId);
        $driver = $this->driverRepository->find($driverId);
        if (!$vehicle || !$driver) {
            return new JsonResponse(['error' => 'Invalid vehicle or driver selected.'], Response::HTTP_BAD_REQUEST);
        }

        // Check if the driver has the required license
        if ($vehicle->getLicenseRequired() !== $driver->getLicense()) {
            return new JsonResponse(['error' => 'Driver license does not match vehicle requirements.'], Response::HTTP_BAD_REQUEST);
        }

        // Last check about the driver and vehicle dates
        $existingTrips = $this->tripRepository->findBy(['vehicle' => $vehicle, 'driver' => $driver, 'date' => $date]);
        if (!empty($existingTrips)) {
            return new JsonResponse(['error' => 'The vehicle or driver already has a trip on this date.'], Response::HTTP_CONFLICT);
        }

        // Create and store the new trip information
        $trip = new Trip();
        $trip->setDate($date);
        $trip->setVehicle($vehicle);
        $trip->setDriver($driver);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }

}
