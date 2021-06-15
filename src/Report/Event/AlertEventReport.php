<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Report\Event;

final class AlertEventReport extends AbstractEventReport
{
    public function getNameReport(): string
    {
        return "по авариям и сообщениям";
    }

    protected function getSourceId(): array
    {
        return ['p', 's', 'o', 'm'];
    }

    protected function getTypeId(): array
    {
        return ['e', 'm'];
    }
}
