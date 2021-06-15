<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Report\Event;

use Tlc\ManualBundle\Report\AbstractPdf;
use Tlc\ManualBundle\Report\AbstractReport;

final class EventPdfReport extends AbstractPdf
{

    public function __construct(AbstractReport $eventReport)
    {
        $this->setReport($eventReport);
        parent::__constructor('L');
    }

    protected function getPointFontHeader(): int
    {
        return 8;
    }

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getHeightCell():int
    {
        return 6;
    }
}
