<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\PlanMembresia;
use App\Models\Empresa;

$planFundador = PlanMembresia::find(1);
$planEmprendedor = PlanMembresia::find(2);
$empresa1 = Empresa::find(1);

echo "Plan Fundador (ID: 1):\n";
echo "  - Nombre: {$planFundador->nombre}\n";
echo "  - Límite productos: {$planFundador->limite_productos}\n\n";

echo "Plan Emprendedor (ID: 2):\n";
echo "  - Nombre: {$planEmprendedor->nombre}\n";
echo "  - Límite productos: {$planEmprendedor->limite_productos}\n\n";

echo "Empresa 1 (Camisetas Jeehy):\n";
echo "  - plan_membresia_id: {$empresa1->plan_membresia_id}\n";
echo "  - limite_productos: {$empresa1->limite_productos}\n";
echo "  - Plan actual: {$empresa1->planMembresia->nombre}\n";