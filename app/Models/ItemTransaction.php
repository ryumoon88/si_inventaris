<?php

namespace App\Models;

use App\Loggers\ApprovalLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ItemTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $observables = ['approved', 'rejected', 'pending'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            if ($query->issuer_id == null) {
                $query->issuer_id = Auth::user()->id;
            }
        });

        static::created(function ($query) {
            $query->withoutEvents(function () use ($query) {
                if ($query->status == 'Approved') {
                    $item = Item::find($query->item_id);
                    $item->quantity_in_stock = $item->quantity_in_stock + $query->amount;
                    $item->save();
                }
            });
        });
    }

    public function approve()
    {
        $this->withoutEvents(function () {
            $this->status = 'Approved';
            $this->item->quantity_in_stock = $this->item->quantity_in_stock + $this->amount;
            $this->item->save();
            $this->save();
        });
        $this->fireModelEvent('approved', false);
    }

    public function reject()
    {
        $this->withoutEvents(function () {
            if ($this->status == 'Approved') {
                $this->item->quantity_in_stock -= $this->amount;
                $this->item->save();
            }
            $this->status = 'Rejected';
            $this->save();
        });
        $this->fireModelEvent('rejected', false);
    }

    public function pending()
    {
        $this->withoutEvents(function () {
            if ($this->status == 'Approved') {
                $this->item->quantity_in_stock -= $this->amount;
                $this->item->save();
            }
            $this->status = 'Pending';
            $this->save();
        });
        $this->fireModelEvent('pending', false);
    }

    // Relations

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function last_status_updater(): ?Activity
    {
        return Activity::where('subject_type', 'App\Models\ItemTransaction')->where('subject_id', $this->id)
            ->where('event', 'Approved')->orWhere('event', 'Rejected')->orderBy('created_at', 'desc')->first();
    }
}