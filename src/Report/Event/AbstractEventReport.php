<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Report\Event;

use Tlc\ManualBundle\Dataset\PdfDataset;
use Tlc\ManualBundle\Entity\Column;
use Tlc\ManualBundle\Entity\Shift;
use Tlc\ManualBundle\Report\AbstractReport;
use Tlc\ManualBundle\Repository\EventRepository;
use DatePeriod;

abstract class AbstractEventReport extends AbstractReport
{
    /**
     *
     * @param DatePeriod $period
     * @param EventRepository $eventRepository
     * @param People[] $people
     */
    public function __construct(
        DatePeriod $period,
        private EventRepository $eventRepository,
        array $people = [],
        array $sqlWhere = []
    ) {
        parent::__construct($period, $people, $sqlWhere);
    }

    abstract protected function getSourceId(): array;
    abstract protected function getTypeId(): array;
    abstract public function getNameReport(): string;

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
        $events = $this->eventRepository->findByTypeAndSourceFromPeriod($this->getPeriod(), $this->getTypeId(), $this->getSourceId(), $this->getSqlWhere());
        if(!$events)
            die('За данный период нет событий');

        $mainDataSetColumns = [
            new Column(title: 'Событие', precentWidth: 70, group: true, align: 'L', total: false),
            new Column(title: 'Источник', precentWidth: 10, group: true, align: 'C', total: false),
            new Column(title: 'Тип', precentWidth: 10, group: true, align: 'C', total: false),
            new Column(title: 'Время', precentWidth: 15, group: true, align: 'C', total: false),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns
        );

        foreach ($events as $event) {

            $text = $event->getText();
            $source = $event->getSource();
            $type = $event->getType();

            $time = $event->getDrec()->format(parent::FORMAT_DATE_TIME);

            $mainDataset->addRow([
                $text,
                $source,
                $type,
                $time,
            ]);
        }

        $this->addDataset($mainDataset);

        return true;
    }
}
