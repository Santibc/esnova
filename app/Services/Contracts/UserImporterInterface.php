<?php
namespace App\Services\Contracts;

interface UserImporterInterface
{
    /**
     * Obtiene los datos brutos del usuario de la fuente externa.
     *
     * @return array Un array de datos brutos del usuario, típicamente una colección de objetos de usuario.
     */
    public function getUsersData(): array;
}