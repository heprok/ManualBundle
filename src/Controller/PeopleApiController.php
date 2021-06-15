<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Controller;

use Tlc\ManualBundle\Entity\BaseEntity;
use Tlc\ManualBundle\Repository\BoardRepository;
use Tlc\ManualBundle\Repository\PeopleRepository;
use Tlc\ManualBundle\Repository\PocketDistanceRepository;
use Tlc\ManualBundle\Repository\ShiftSheduleRepository;
use Tlc\ManualBundle\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/peoples", name: "api_people_")]
class PeopleApiController extends AbstractController
{
    // private PeopleRepository $peopleRepository;
    // private ShiftRepository $unloadRepository;

    public function __construct(
        private PeopleRepository $peopleRepository,
        private BoardRepository $boardRepository,
        private ShiftRepository $shiftRepository
    ) {
    }

    #[Route('/{idPeople}/volumeForDuration', name: "volume_for_duration")]
    public function volumeForDuration(int $idPeople)
    {
        $request = Request::createFromGlobals();
        $startDate = new DateTime($request->query->get('start'));
        $endDate = new DateTime($request->query->get('end'));
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        
        $volume = $this->boardRepository->getVolumeOnOperatorForDuration($period, $idPeople);
        return $this->json(['volume' => $volume]);
    }

    #[Route('/{idPeople}/countForDuration', name: "count_for_duration")]
    public function countForDuration(int $idPeople)
    {
        $request = Request::createFromGlobals();
        $startDate = new DateTime($request->query->get('start'));
        $endDate = new DateTime($request->query->get('end'));
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        
        $volume = $this->boardRepository->getVolumeOnOperatorForDuration($period, $idPeople);
        return $this->json(['volume' => $volume]);
    }

    #[Route('/{idPeople}/infoForDuration', name: "info_for_duration")]
    public function infoForDuration(int $idPeople)
    {
        $request = Request::createFromGlobals();
        $startDate = new DateTime($request->query->get('start'));
        $endDate = new DateTime($request->query->get('end'));
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        
        $info = $this->boardRepository->getInfoOperatorForDuration($period, $idPeople);
        $info['hoursWork'] = $this->shiftRepository->getTimeWorkForOperator($period, $idPeople);
        $info['timeDowntime'] = $this->shiftRepository->getTimeDowntimeForOperator($period, $idPeople);
        $info['countShift'] = $this->shiftRepository->getCountShiftForOperator($period, $idPeople);
        $result = [
            'volume' => round((float)$info['volume'], BaseEntity::PRECISION_FOR_FLOAT),
            'count' => $info['count'],
            'timeWork' => $info['hoursWork'], 
            'timeDowntime' => $info['timeDowntime'],
            'countShift' => $info['countShift'],
        ];

        return $this->json($result);
    }
}
