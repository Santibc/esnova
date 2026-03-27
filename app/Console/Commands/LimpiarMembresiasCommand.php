<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\Membresia;
use App\Models\PlanMembresia;
use Illuminate\Support\Facades\DB;

class LimpiarMembresiasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membresias:limpiar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar membresías duplicadas y asegurar que cada empresa tenga solo una membresía activa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de membresías...');
        
        DB::beginTransaction();
        
        try {
            $empresas = Empresa::all();
            $empresasArregladas = 0;
            
            foreach ($empresas as $empresa) {
                $membresiasActivas = $empresa->membresias()
                    ->where('estado', 'activa')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                if ($membresiasActivas->count() > 1) {
                    $this->warn("Empresa {$empresa->nombre} tiene {$membresiasActivas->count()} membresías activas");
                    
                    // Mantener solo la más reciente (primera en el ordenamiento desc)
                    $membresiaAMantener = $membresiasActivas->first();
                    
                    // Si la más reciente es gratuita pero hay una pagada, mantener la pagada
                    $membresiasPagadas = $membresiasActivas->filter(function($m) {
                        return $m->precio_pagado > 0;
                    });
                    
                    if ($membresiasPagadas->isNotEmpty() && $membresiaAMantener->precio_pagado == 0) {
                        $membresiaAMantener = $membresiasPagadas->first();
                        $this->info("  - Manteniendo membresía pagada: {$membresiaAMantener->plan->nombre}");
                    }
                    
                    // Cancelar las otras
                    foreach ($membresiasActivas as $membresia) {
                        if ($membresia->id !== $membresiaAMantener->id) {
                            $membresia->update(['estado' => 'cancelada']);
                            $this->info("  - Cancelada: {$membresia->plan->nombre}");
                        }
                    }
                    
                    // Actualizar empresa con los datos de la membresía mantenida
                    $plan = $membresiaAMantener->plan;
                    $empresa->update([
                        'plan_membresia_id' => $plan->id,
                        'limite_productos' => $plan->limite_productos,
                        'porcentaje_comision' => $plan->porcentaje_comision,
                        'comision_fija' => $plan->comision_fija,
                        'cargo_fijo_comision' => $plan->comision_fija
                    ]);
                    
                    $empresasArregladas++;
                }
            }
            
            // Verificar empresas sin membresías activas
            $empresasSinMembresia = Empresa::whereDoesntHave('membresias', function($q) {
                $q->where('estado', 'activa');
            })->get();
            
            if ($empresasSinMembresia->isNotEmpty()) {
                $planGratuito = PlanMembresia::where('precio', 0)->first();
                
                if ($planGratuito) {
                    foreach ($empresasSinMembresia as $empresa) {
                        $this->warn("Empresa {$empresa->nombre} sin membresía activa, creando gratuita...");
                        
                        Membresia::create([
                            'empresa_id' => $empresa->id,
                            'plan_membresia_id' => $planGratuito->id,
                            'estado' => 'activa',
                            'fecha_inicio' => now(),
                            'fecha_fin' => null,
                            'precio_pagado' => 0
                        ]);
                        
                        $empresa->update([
                            'plan_membresia_id' => $planGratuito->id,
                            'limite_productos' => $planGratuito->limite_productos,
                            'porcentaje_comision' => $planGratuito->porcentaje_comision,
                            'comision_fija' => $planGratuito->comision_fija,
                            'cargo_fijo_comision' => $planGratuito->comision_fija
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            $this->info("Limpieza completada:");
            $this->info("- Empresas con membresías duplicadas arregladas: {$empresasArregladas}");
            $this->info("- Empresas sin membresía que recibieron plan gratuito: {$empresasSinMembresia->count()}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la limpieza: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
