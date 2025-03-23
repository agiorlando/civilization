<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate only demo project tables: leaders, civilizations, historical_info';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Resetting demo project database tables...');

        // Disable foreign key checks to allow truncation.
        Schema::disableForeignKeyConstraints();

        // Define the tables to clear.
        $tablesToTruncate = ['leaders', 'civilizations', 'historical_info'];

        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->info("Truncated table: {$table}");
            } else {
                $this->warn("Table not found: {$table}");
            }
        }

        // Re-enable foreign key checks.
        Schema::enableForeignKeyConstraints();

        $this->info('Database reset complete.');
        return 0;
    }
}
