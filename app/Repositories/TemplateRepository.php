<?php

namespace App\Repositories;

use App\Models\Empresa;
use App\Models\TemplateTienda;
use Illuminate\Support\Collection;

/**
 * TemplateRepository
 *
 * Repositorio para gestionar consultas relacionadas con templates de tienda.
 * Encapsula la lógica de acceso a datos de TemplateTienda.
 *
 * @package App\Repositories
 */
class TemplateRepository
{
    /**
     * Busca el template asignado a una empresa
     *
     * Retorna el template activo asociado a la empresa.
     * Si la empresa no tiene template asignado, retorna null.
     *
     * @param Empresa $empresa La empresa cuyo template se busca
     * @return TemplateTienda|null El template encontrado o null
     */
    public function findByEmpresa(Empresa $empresa): ?TemplateTienda
    {
        return $empresa->templateTienda()
            ->where('activo', true)
            ->first();
    }

    /**
     * Obtiene todos los templates activos
     *
     * Retorna una colección de todos los templates que están
     * marcados como activos, ordenados por campo 'orden'.
     *
     * @return Collection<TemplateTienda> Colección de templates activos
     */
    public function getAllActivos(): Collection
    {
        return TemplateTienda::where('activo', true)
            ->orderBy('orden', 'asc')
            ->get();
    }

    /**
     * Obtiene el template marcado como default
     *
     * Retorna el template que está configurado como predeterminado
     * y que además está activo.
     *
     * @return TemplateTienda|null El template default o null si no existe
     */
    public function getDefault(): ?TemplateTienda
    {
        return TemplateTienda::where('es_default', true)
            ->where('activo', true)
            ->first();
    }

    /**
     * Busca un template por su código único
     *
     * El código es un identificador único para cada template.
     *
     * @param string $codigo El código del template a buscar
     * @return TemplateTienda|null El template encontrado o null
     */
    public function findByCodigo(string $codigo): ?TemplateTienda
    {
        return TemplateTienda::where('codigo', $codigo)->first();
    }

    /**
     * Obtiene templates para mostrar en selectores/dropdowns
     *
     * Retorna solo los campos necesarios para mostrar en un selector:
     * id, codigo, nombre, descripcion, preview_image.
     * Solo incluye templates activos, ordenados por campo 'orden'.
     *
     * @return Collection<TemplateTienda> Colección de templates con campos limitados
     */
    public function getForSelector(): Collection
    {
        return TemplateTienda::select([
                'id',
                'codigo',
                'nombre',
                'descripcion',
                'preview_image'
            ])
            ->where('activo', true)
            ->orderBy('orden', 'asc')
            ->get();
    }

    /**
     * Obtiene todos los templates incluyendo inactivos
     *
     * Útil para paneles de administración donde se necesita
     * ver todos los templates registrados en el sistema.
     *
     * @return Collection<TemplateTienda> Colección de todos los templates
     */
    public function getAll(): Collection
    {
        return TemplateTienda::orderBy('orden', 'asc')->get();
    }

    /**
     * Verifica si un código de template ya existe
     *
     * @param string $codigo El código a verificar
     * @param int|null $excludeId ID a excluir de la búsqueda (útil para updates)
     * @return bool True si el código existe, false en caso contrario
     */
    public function codigoExists(string $codigo, ?int $excludeId = null): bool
    {
        $query = TemplateTienda::where('codigo', $codigo);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Obtiene el template con el orden más alto
     *
     * Útil para determinar el siguiente número de orden
     * al crear un nuevo template.
     *
     * @return int El orden máximo actual
     */
    public function getMaxOrden(): int
    {
        return TemplateTienda::max('orden') ?? 0;
    }
}
