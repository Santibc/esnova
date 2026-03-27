<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;

class TestUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test updating empresa plan_membresia_id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $planFundador = \App\Models\PlanMembresia::find(1);
        $planEmprendedor = \App\Models\PlanMembresia::find(2);
        $empresa1 = Empresa::find(1);

        $this->info("Plan Fundador (ID: 1):");
        $this->line("  - Nombre: {$planFundador->nombre}");
        $this->line("  - Límite productos: {$planFundador->limite_productos}");

        $this->info("\nPlan Emprendedor (ID: 2):");
        $this->line("  - Nombre: {$planEmprendedor->nombre}");
        $this->line("  - Límite productos: {$planEmprendedor->limite_productos}");

        $this->info("\nEmpresa 1 (Camisetas Jeehy):");
        $this->line("  - plan_membresia_id: {$empresa1->plan_membresia_id}");
        $this->line("  - limite_productos: {$empresa1->limite_productos}");
        $this->line("  - Plan actual: {$empresa1->planMembresia->nombre}");
        
        $this->info("\nProductos actuales:");
        $productosActivos = $empresa1->productos()->where('activo', true)->count();
        $this->line("  - Productos activos: {$productosActivos}");
        $this->line("  - Límite actual: {$empresa1->limite_productos}");
        $this->line("  - Restantes: " . ($empresa1->limite_productos - $productosActivos));
        
        $this->info("\nCampos en tabla empresas:");
        foreach ($empresa1->getAttributes() as $campo => $valor) {
            $this->line("  - {$campo}: {$valor}");
        }
        
        return Command::SUCCESS;
    }
}
