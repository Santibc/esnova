<?php
require_once 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Empresa;

$empresa = Empresa::find(1);
echo "Antes: plan_membresia_id = {$empresa->plan_membresia_id}\n";

$resultado = $empresa->update(['plan_membresia_id' => 2]);
echo "Update resultado: " . ($resultado ? 'exitoso' : 'fallido') . "\n";

$empresa->refresh();
echo "Después: plan_membresia_id = {$empresa->plan_membresia_id}\n";