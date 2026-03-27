<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;

class SincronizarMembresias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membresias:sincronizar {--force : Forzar actualización de todos los campos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar el plan_membresia_id de las empresas con sus membresías activas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de membresías...');
        
        $empresas = Empresa::all();
        $sincronizadas = 0;
        
        foreach ($empresas as $empresa) {
            $this->info("Procesando empresa: {$empresa->nombre} (ID: {$empresa->id})");
            
            // Mostrar estado actual
            $this->line("- Plan actual en empresa: " . ($empresa->planMembresia ? $empresa->planMembresia->nombre : 'NULL'));
            
            $membresiaActiva = $empresa->membresiaActiva;
            if ($membresiaActiva) {
                $this->line("- Membresía activa encontrada: {$membresiaActiva->plan->nombre} (Estado: {$membresiaActiva->estado})");
                $this->line("- ID plan empresa actual: {$empresa->plan_membresia_id}");
                $this->line("- ID plan membresía activa: {$membresiaActiva->plan_membresia_id}");
                $this->line("- ¿Son diferentes?: " . ($empresa->plan_membresia_id !== $membresiaActiva->plan_membresia_id ? 'SÍ' : 'NO'));
            } else {
                $this->line("- No hay membresía activa");
            }
            
            // Ejecutar verificación
            $resultado = $empresa->verificarYActualizarMembresia();
            
            // Si está el flag --force, sincronizar todos los campos aunque los IDs coincidan
            if (!$resultado && $this->option('force') && $membresiaActiva) {
                $this->line("- Forzando actualización de todos los campos...");
                $resultado = $empresa->update([
                    'plan_membresia_id' => $membresiaActiva->plan_membresia_id,
                    'limite_productos' => $membresiaActiva->plan->limite_productos,
                    'porcentaje_comision' => $membresiaActiva->plan->porcentaje_comision,
                    'comision_fija' => $membresiaActiva->plan->comision_fija,
                    'cargo_fijo_comision' => $membresiaActiva->plan->comision_fija
                ]);
                
                if ($resultado) {
                    $sincronizadas++;
                    $this->info("  ✓ Empresa sincronizada (forzada)");
                }
            } elseif ($resultado) {
                $sincronizadas++;
                $this->info("  ✓ Empresa sincronizada");
            } else {
                $this->line("  - No requiere sincronización");
            }
            
            if ($resultado) {
                // Recargar empresa y sus relaciones para ver cambios
                $empresa->refresh();
                $empresa->load('planMembresia'); // Forzar recarga de la relación
                $this->line("- Plan después de sincronización: " . ($empresa->planMembresia ? $empresa->planMembresia->nombre : 'NULL'));
                $this->line("- Límite productos: " . $empresa->limite_productos);
            }
            
            $this->line('');
        }
        
        $this->info("Proceso completado. {$sincronizadas} empresas sincronizadas.");
        
        return Command::SUCCESS;
    }
}
