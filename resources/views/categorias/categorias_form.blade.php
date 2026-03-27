<x-app-layout>
  <x-slot name="header">
    {{ $categoria->exists ? 'Editar Categoría' : 'Nueva Categoría' }}
  </x-slot>

  <div class="container py-4">
    {{-- Mostrar errores de validación general --}}
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>Por favor corrija los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="bi bi-tags"></i> 
          {{ $categoria->exists ? 'Editar' : 'Crear' }} Categoría para {{ $empresa->nombre }}
        </h5>
      </div>
      <div class="card-body">
        {{-- Información sobre productos usando esta categoría --}}
        @if($categoria->exists && $productosCount > 0)
          <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle"></i> 
            Esta categoría tiene <strong>{{ $productosCount }}</strong> producto(s) asociados.
            <br>
            <small>No podrá eliminar esta categoría mientras tenga productos asociados.</small>
          </div>
        @endif

        <form method="POST" action="{{ route('categorias.guardar') }}" id="categoriaForm" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" value="{{ old('id',$categoria->id) }}">
          
          <div class="row">
            {{-- Nombre --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input name="nombre" type="text"
                     class="form-control @error('nombre') is-invalid @enderror"
                     value="{{ old('nombre',$categoria->nombre) }}"
                     placeholder="Ej: Ropa, Calzado, Accesorios"
                     required>
              @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Este nombre será visible en su tienda</small>
            </div>

            {{-- Slug --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Slug (URL amigable)</label>
              <div class="input-group">
                <span class="input-group-text text-muted">{{ $empresa->slug }}/categoria/</span>
                <input name="slug" type="text"
                       class="form-control @error('slug') is-invalid @enderror"
                       value="{{ old('slug',$categoria->slug) }}"
                       placeholder="se-genera-automaticamente">
              </div>
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Dejar vacío para generar automáticamente</small>
            </div>

            {{-- Descripción --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        placeholder="Descripción breve de la categoría (opcional)">{{ old('descripcion',$categoria->descripcion) }}</textarea>
              @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Esta descripción puede mostrarse en su tienda</small>
            </div>

            {{-- Imagen --}}
            <div class="col-md-8 mb-3">
              <label class="form-label">Imagen de la Categoría</label>
              
              {{-- Preview de imagen actual --}}
              @if($categoria->imagen)
                <div class="mb-2" id="imagen-actual">
                  <img src="{{ asset($categoria->imagen) }}" 
                       alt="{{ $categoria->nombre }}" 
                       class="img-thumbnail"
                       style="max-height: 200px; max-width: 300px;">
                  <div class="mt-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen" value="1">
                      <label class="form-check-label text-danger" for="eliminar_imagen">
                        <i class="bi bi-trash"></i> Eliminar imagen actual
                      </label>
                    </div>
                  </div>
                </div>
              @endif

              {{-- Input para nueva imagen --}}
              <div id="nueva-imagen-container">
                <input type="file" name="imagen" id="imagen"
                       class="form-control @error('imagen') is-invalid @enderror"
                       accept="image/*">
                @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB</small>
                
                {{-- Preview de nueva imagen --}}
                <div id="preview-container" class="mt-2" style="display: none;">
                  <img id="preview-imagen" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                </div>
              </div>
            </div>

            {{-- Orden --}}
            <div class="col-md-4 mb-3">
              <label class="form-label">Orden de visualización <span class="text-danger">*</span></label>
              <input name="orden" type="number" min="0"
                     class="form-control @error('orden') is-invalid @enderror"
                     value="{{ old('orden', $categoria->orden ?? ($maxOrden + 10)) }}"
                     required>
              @error('orden') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Menor número = Mayor prioridad en la lista</small>
              
              {{-- Vista previa del orden --}}
              <div class="alert alert-light mt-2">
                <small>
                  <i class="bi bi-arrow-up-down"></i> 
                  Las categorías se mostrarán ordenadas por este número de menor a mayor.
                  Puede usar incrementos de 10 (10, 20, 30...) para facilitar futuros reordenamientos.
                </small>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> {{ $categoria->exists ? 'Actualizar' : 'Guardar' }} Categoría
            </button>
            <a href="{{ route('categorias') }}" class="btn btn-outline-secondary">
              <i class="bi bi-x-circle"></i> Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- Información adicional --}}
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="card-title"><i class="bi bi-lightbulb"></i> Consejos</h6>
            <ul class="mb-0">
              <li>Use nombres descriptivos y claros</li>
              <li>La imagen ayuda a identificar rápidamente la categoría</li>
              <li>El orden determina cómo aparecen en los menús</li>
              <li>Puede cambiar el estado (activo/inactivo) desde el listado</li>
              <li>Las categorías inactivas no se muestran en la tienda</li>
            </ul>
          </div>
        </div>
      </div>
      
      @if($categoria->exists)
      <div class="col-md-6">
        <div class="card bg-warning bg-opacity-10">
          <div class="card-body">
            <h6 class="card-title"><i class="bi bi-exclamation-triangle"></i> Información importante</h6>
            <ul class="mb-0">
              <li>Los cambios se reflejarán inmediatamente en su tienda</li>
              <li>Si cambia el slug, los enlaces antiguos dejarán de funcionar</li>
              <li>No puede eliminar categorías con productos asociados</li>
              <li>Al eliminar la imagen, se borrará permanentemente</li>
            </ul>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  @push('scripts')
    <script>
      // Auto-generar slug al cambiar el nombre
      document.querySelector('input[name=nombre]').addEventListener('input', function(){
        const slugInput = document.querySelector('input[name=slug]');
        
        // Solo generar si el campo slug está vacío o es igual al slug anterior generado
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
          let slug = this.value.toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '') // Remover acentos
                        .replace(/[^a-z0-9\s-]/g, '') // Solo letras, números, espacios y guiones
                        .replace(/\s+/g, '-') // Espacios a guiones
                        .replace(/-+/g, '-') // Múltiples guiones a uno solo
                        .replace(/^-+|-+$/g, ''); // Remover guiones al inicio y final
          
          slugInput.value = slug;
          slugInput.dataset.autoGenerated = 'true';
        }
      });
      
      // Marcar el slug como manual si el usuario lo edita
      document.querySelector('input[name=slug]').addEventListener('input', function(){
        if (this.value) {
          this.dataset.autoGenerated = 'false';
        }
      });
      
      // Preview de imagen
      document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const previewImagen = document.getElementById('preview-imagen');
        
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImagen.src = e.target.result;
            previewContainer.style.display = 'block';
          };
          reader.readAsDataURL(file);
        } else {
          previewContainer.style.display = 'none';
        }
      });
      
      // Manejar checkbox de eliminar imagen
      const eliminarImagenCheckbox = document.getElementById('eliminar_imagen');
      if (eliminarImagenCheckbox) {
        eliminarImagenCheckbox.addEventListener('change', function() {
          const imagenActual = document.getElementById('imagen-actual');
          const nuevaImagenContainer = document.getElementById('nueva-imagen-container');
          
          if (this.checked) {
            imagenActual.style.opacity = '0.5';
            nuevaImagenContainer.style.display = 'none';
          } else {
            imagenActual.style.opacity = '1';
            nuevaImagenContainer.style.display = 'block';
          }
        });
      }
      
      // Validación del formulario
      document.getElementById('categoriaForm').addEventListener('submit', function(e) {
        const nombre = document.querySelector('input[name=nombre]').value.trim();
        const orden = document.querySelector('input[name=orden]').value;
        
        if (!nombre) {
          e.preventDefault();
          alert('El nombre de la categoría es obligatorio');
          return false;
        }
        
        if (!orden || orden < 0) {
          e.preventDefault();
          alert('El orden debe ser un número mayor o igual a 0');
          return false;
        }
      });
    </script>
  @endpush
</x-app-layout>