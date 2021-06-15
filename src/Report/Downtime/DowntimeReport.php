<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Report\Downtime;

use Tlc\ManualBundle\Dataset\PdfDataset;
use Tlc\ManualBundle\Dataset\SummaryPdfDataset;
use Tlc\ManualBundle\Entity\BaseEntity;
use Tlc\ManualBundle\Entity\Column;
use Tlc\ManualBundle\Entity\SummaryStat;
use Tlc\ManualBundle\Entity\Unload;
use Tlc\ManualBundle\Report\AbstractReport;
use Tlc\ManualBundle\Repository\BreakSheduleRepository;
use Tlc\ManualBundle\Repository\DowntimeRepository;
use DateInterval;
use DatePeriod;
use DateTime;

final class DowntimeReport extends AbstractReport
{
    public function __construct(
        DatePeriod $period,
        private DowntimeRepository $downtimeRepository,
        private BreakSheduleRepository $breakSheduleRepository,
        array $peoples = [],
        array $sqlWhere = []
    ) {

        parent::__construct($period, $peoples, $sqlWhere);
    }

    public function getNameReport(): string
    {
        return "по простоям";
    }

    /**
     * @return SummaryStat[]
     */
    public function getSummaryStats(): array
    {
        return [];
    }

    /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        return [];
    }

    protected function updateDataset(): bool
    {
        $downtimes = $this->downtimeRepository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$downtimes)
            die('В данный период нет простоев');

        $mainDatatetColumns = [
            new Column(title: 'Расположение', precentWidth: 12, group: false, align: 'C',  total: false),
            new Column(title: 'Место', precentWidth: 20, group: false, align: 'C',  total: false),
            new Column(title: 'Группа', precentWidth: 11, group: false, align: 'C',  total: false),
            new Column(title: 'Причина', precentWidth: 20, group: false, align: 'C',  total: false),
            new Column(title: 'Начало', precentWidth: 15, group: false, align: 'C',  total: false),
            new Column(title: 'Окончание', precentWidth: 15, group: false, align: 'C',  total: false),
            $columnDurationInMain = new Column(title: 'Длит-ност', precentWidth: 10, group: false, align: 'C',  total: true),
        ];

        $groupDatasetColumns = [
            new Column(title: 'Группа', precentWidth: 40, group: false, align: 'R', total: false),
            $columndDurationInGroup = new Column(title: 'Длительность', precentWidth: 30, group: false, align: 'C', total: true),
            new Column(title: '% от длит. простоев', precentWidth: 30, group: false, align: 'C', total: true),
        ];
        $techDatasetColumns = [
            new Column(title: 'Место', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Причина', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Начало', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Окончание', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Длит-ность', precentWidth: 20, group: false, align: 'C', total: true),
        ];

        $groupSummaryPdfDataset = new SummaryPdfDataset(
            nameSummary: 'Группы простоев',
            columns: $groupDatasetColumns,
            textTotal: 'Итого',
        );
        $techSummaryPdfDataset = new SummaryPdfDataset(
            nameSummary: 'Технологические простои',
            columns: $techDatasetColumns,
            textTotal: 'Общая продолжительность',
            textSubTotal: 'Длительность простоя за день'
        );
        $mainDataset = new PdfDataset(
            columns: $mainDatatetColumns,
            textTotal: 'Общая продолжительность',
            textSubTotal: 'Длительность простоя за день'
        );

        $nowDatetime = new Datetime('00:00');
        $buff['day'] = '';
        foreach ($downtimes as $key => $downtime) {

            $cause = $downtime->getCause();
            $place = $downtime->getPlace();
            $locationName = $place?->getLocation()->getName() ?? '';
            $groupName = $cause?->getGroups()->getName() ?? '';
            $startTime = $downtime->getDrec();
            $endTime = $downtime->getFinish();
            $duration  = $downtime->getDurationInterval() ?? 'Продолжается';

            if ($buff['day'] != $startTime->format('d') && $key != 0) {
                $mainDataset->addSubTotal();
                $techSummaryPdfDataset->addSubTotal();
            }

            $buff['day'] = $startTime->format('d');
            if ($this->breakSheduleRepository->isDowntimeBreak($downtime)) {
                $techSummaryPdfDataset->addRow([
                    $place,
                    $cause,
                    $startTime->format(self::FORMAT_DATE_TIME),
                    $endTime ? $endTime->format(self::FORMAT_DATE_TIME) : 'Продолжается',
                    $duration
                ]);
            } else {
                $mainDataset->addRow([
                    $locationName,
                    $place,
                    $groupName,
                    $cause,
                    $startTime->format(self::FORMAT_DATE_TIME),
                    $endTime ? $endTime->format(self::FORMAT_DATE_TIME) : 'Продолжается',
                    $duration
                ]);
                $duration  = $endTime ? $endTime->diff($startTime, true) : new DateInterval('P1D');
                $buffSummaryStatGroup[$groupName][$columndDurationInGroup->getTitle()] = $buffSummaryStatGroup[$groupName][$columndDurationInGroup->getTitle()] ??  new Datetime('00:00');
                $buffSummaryStatGroup[$groupName][$columndDurationInGroup->getTitle()]->add($duration);
            }
        }

        $totalDowntimeSecond = $nowDatetime->diff($mainDataset->getTotalResultInColumn($columnDurationInMain));
        foreach ($buffSummaryStatGroup as $nameGroup => $group) {
            $duration = $nowDatetime->diff($group[$columndDurationInGroup->getTitle()]);
            $precentTotalDowntime = round(BaseEntity::dateIntervalToSeconds($duration) / BaseEntity::dateIntervalToSeconds($totalDowntimeSecond) * 100);
            $groupSummaryPdfDataset->addRow(
                [
                    $nameGroup,
                    $duration,
                    (int)abs($precentTotalDowntime)
                ]
            );
        }

        $mainDataset->addSubTotal();
        $mainDataset->addTotal();
        $techSummaryPdfDataset->addSubTotal();
        $techSummaryPdfDataset->addTotal();
        $groupSummaryPdfDataset->addTotal();
        $this->addDataset($mainDataset);
        $this->addDataset($groupSummaryPdfDataset);
        $this->addDataset($techSummaryPdfDataset);
        return true;
    }
}
