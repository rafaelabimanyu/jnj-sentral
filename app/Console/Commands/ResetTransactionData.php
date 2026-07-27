<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetTransactionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jnj:reset-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus (truncate) semua data uji coba di tabel incomes, expenses, dan audit_logs. Data users dan clients tetap dipertahankan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('Perhatian: Anda akan MENGHAPUS SEMUA DATA TRANSAKSI (Incomes, Expenses, dan Audit Logs).');
        
        if ($this->confirm('Apakah Anda yakin ingin melanjutkan reset data transaksi?', true)) {
            $this->info('Memulai pembersihan data...');

            // Nonaktifkan pemeriksaan foreign key sementara untuk memungkinkan truncate
            Schema::disableForeignKeyConstraints();

            try {
                if (Schema::hasTable('expenses')) {
                    DB::table('expenses')->truncate();
                    $this->line('- Tabel expenses berhasil dikosongkan.');
                }
                
                if (Schema::hasTable('incomes')) {
                    DB::table('incomes')->truncate();
                    $this->line('- Tabel incomes berhasil dikosongkan.');
                }

                if (Schema::hasTable('audit_logs')) {
                    DB::table('audit_logs')->truncate();
                    $this->line('- Tabel audit_logs berhasil dikosongkan.');
                }

                $this->info('Semua data transaksi uji coba telah berhasil dibersihkan! Aplikasi siap untuk Production.');
            } catch (\Exception $e) {
                $this->error('Terjadi kesalahan: ' . $e->getMessage());
            } finally {
                // Aktifkan kembali pemeriksaan foreign key
                Schema::enableForeignKeyConstraints();
            }
        } else {
            $this->info('Operasi dibatalkan.');
        }
    }
}
