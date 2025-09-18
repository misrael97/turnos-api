<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // No se crea tabla real, los reportes son consultas virtuales
    }

    public function down(): void
    {
        // No se elimina nada
    }
};
