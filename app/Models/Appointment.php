<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'doubt_id',
        'slot_id',
        'appointment_date',
        'start_time',
        'end_time',
        'duration',
        'status',
        'admin_notes',
        'meet_link',
        'meet_event_id',
        'meet_status',
        'meet_metadata',
        'meet_generated_at',
        'google_meet_link',
        'google_meet_id',
        'meeting_status',
        'meeting_generated_at',
        'google_calendar_event_id',
        'calendar_status',
        'calendar_created_at',
        'calendar_updated_at',
        'calendar_event_id',
        'calendar_sync_status',
        'calendar_last_synced_at',
        'email_notification_status',
        'email_notification_sent_at',
        'rescheduled_at',
    ];

    protected $casts = [
        'meet_metadata' => 'array',
        'meet_generated_at' => 'datetime',
        'meeting_generated_at' => 'datetime',
        'calendar_created_at' => 'datetime',
        'calendar_updated_at' => 'datetime',
        'calendar_last_synced_at' => 'datetime',
        'email_notification_sent_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'appointment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function doubt()
    {
        return $this->belongsTo(Doubt::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
