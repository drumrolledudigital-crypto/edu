<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Fix refunds.payment_id: change from CASCADE to RESTRICT to prevent deleting payments that have refunds
        DB::statement('ALTER TABLE refunds DROP FOREIGN KEY refunds_payment_id_foreign');
        DB::statement('ALTER TABLE refunds ADD CONSTRAINT refunds_payment_id_foreign FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE RESTRICT');

        // Fix appointments.doubt_id: change from CASCADE to SET NULL to preserve paid appointments
        DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_doubt_id_foreign');
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT appointments_doubt_id_foreign FOREIGN KEY (doubt_id) REFERENCES doubts(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE refunds DROP FOREIGN KEY refunds_payment_id_foreign');
        DB::statement('ALTER TABLE refunds ADD CONSTRAINT refunds_payment_id_foreign FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE');

        DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_doubt_id_foreign');
        DB::statement('ALTER TABLE appointments ADD CONSTRAINT appointments_doubt_id_foreign FOREIGN KEY (doubt_id) REFERENCES doubts(id) ON DELETE CASCADE');
    }
};
