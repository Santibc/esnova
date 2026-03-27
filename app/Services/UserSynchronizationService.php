<?php
// app/Services/UserSynchronizationService.php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserImporterInterface;
use Illuminate\Support\Facades\Log; // Importar Log para el registro de errores

class UserSynchronizationService
{
    private UserImporterInterface $userImporter;
    private UserCreationService $userCreationService;

    public function __construct(UserImporterInterface $userImporter, UserCreationService $userCreationService)
    {
        $this->userImporter = $userImporter;
        $this->userCreationService = $userCreationService;
    }

    /**
     * Sincroniza los usuarios de la fuente externa configurada en la base de datos local.
     *
     * @return array Un informe que contiene el recuento de usuarios importados y omitidos, y cualquier error.
     */
    public function synchronizeUsers(): array
    {
        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];

        $externalUsersData = $this->userImporter->getUsersData();

        foreach ($externalUsersData as $membership) {
            $userData = $membership['user'] ?? null;

            // Validar si los datos del usuario y el correo electrónico están presentes en la respuesta externa
            if (!$userData || !isset($userData['email'])) {
                $errors[] = 'Saltando una entrada debido a la falta de datos de usuario o correo electrónico.';
                Log::warning('Sincronización de usuarios: Faltan datos de usuario o correo electrónico en una entrada externa.', ['entry' => $membership]);
                continue;
            }

            $email = $userData['email'];

            // Verificar si el usuario ya existe en el sistema local por correo electrónico
            if (User::where('email', $email)->exists()) {
                $skippedCount++;
                Log::info("El usuario con el correo electrónico '{$email}' ya existe, se omite la importación.");
                continue; // El usuario ya existe, omitir importación
            }

            try {
                // Preparar datos para UserCreationService, mapeando los campos de Calendly a los campos del modelo User
                $userAttributes = [
                    'name' => $userData['name'] ?? $email, // Usar el email como nombre de respaldo si el nombre falta
                    'email' => $email,
                    'uuid' => $userData['uri'] ? basename($userData['uri']) : null, // Extraer UUID de la URI de usuario de Calendly
                    'avatar_url' => $userData['avatar_url'] ?? null,
                    'locale' => $userData['locale'] ?? null,
                    'time_notation' => $userData['time_notation'] ?? null,
                    'timezone' => $userData['timezone'] ?? null,
                    'slug' => $userData['slug'] ?? null,
                    'scheduling_url' => $userData['scheduling_url'] ?? null,
                    'calendly_uri' => $userData['uri'] ?? null, // Almacenar la URI completa del usuario de Calendly
                ];

                $this->userCreationService->create($userAttributes);
                $importedCount++;
                Log::info("Usuario '{$email}' importado exitosamente.");
            } catch (\Exception $e) {
                $errorMessage = "Error al importar el usuario {$email}: " . $e->getMessage();
                $errors[] = $errorMessage;
                Log::error($errorMessage, ['exception' => $e, 'user_data' => $userData]);
            }
        }

        return [
            'imported' => $importedCount,
            'skipped' => $skippedCount,
            'errors' => $errors,
        ];
    }
}