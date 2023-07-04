<?php

namespace App\Loggers;

use App\Models\ItemTransaction;
use Illuminate\Database\Eloquent\Model;

class ApprovalLogger extends AbstractModelLogger
{
    protected function getLogName(): string
    {
        return config('filament-logger.approvals.log_name');
    }

    public function approved(ItemTransaction $model)
    {
        $this->log($model, 'Approved');
    }

    public function rejected(ItemTransaction $model)
    {
        $this->log($model, 'Rejected');
    }

    public function pending(ItemTransaction $model)
    {
        $this->log($model, 'Pending');
    }

    public function created(Model $model)
    {
    }

    public function updated(Model $model)
    {
    }

    public function deleted(Model $model)
    {
    }
}