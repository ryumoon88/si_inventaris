<?php

namespace App\Filament\Resources\ItemTransactionResource\Pages;

use App\Filament\Resources\ItemTransactionResource;
use App\Models\ItemTransaction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewItemTransaction extends ViewRecord
{
    protected static string $resource = ItemTransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make(),
                Actions\Action::make('approve')
                    ->action(function (Actions\Action $action) {
                        $record = $this->getRecord();
                        $record->approve();
                        $this->refreshFormData(['status', 'updated_at', 'updater']);
                        $action->success();
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('Are you sure you would like to approve this transaction?')
                    ->modalButton('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(function () {
                        $record = $this->getRecord();
                        return Gate::allows('approve', $record) || Gate::allows('forceApprove', $record);
                    })
                    ->successNotificationTitle('Transaction Approved'),

                Actions\Action::make('reject')
                    ->action(function (Actions\Action $action) {
                        $record = $this->getRecord();
                        $record->reject();
                        $this->refreshFormData(['status', 'updated_at', 'updater', 'approve_detail']);
                        $action->success();
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('Are you sure you would like to reject this transaction?')
                    ->modalButton('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(function () {
                        $record = $this->getRecord();
                        return Gate::allows('reject', $record) || Gate::allows('forceReject', $record);
                    })
                    ->successNotificationTitle('Transaction Rejected'),
                Actions\Action::make('pending')
                    ->action(function (Actions\Action $action) {
                        $record = $this->getRecord();
                        $record->pending();
                        $this->refreshFormData(['status', 'updated_at', 'updater']);
                        $action->success();
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('Are you sure you would like to pending this transaction?')
                    ->modalButton('Pending')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(function () {
                        $record = $this->getRecord();
                        return Gate::allows('pending', $record) || Gate::allows('forcePending', $record);
                    })
                    ->successNotificationTitle('Transaction Pending')
            ]),

        ];
    }
}