<x-app-layout>
    <x-slot name="header">
        {{ $user->exists ? 'Editar Usuario' : 'Nuevo Usuario' }}
    </x-slot>

    <div class="container py-4">
        <div class="card shadow">

            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.guardar') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ old('id', $user->id) }}">

                    <div class="row">

                        {{-- Nombre --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">
                                Nombre <span class="text-danger">*</span>
                            </label>
                            <input name="name" type="text"
                                   class="form-control"
                                   value="{{ old('name', $user->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input name="email" type="email"
                                   class="form-control"
                                   value="{{ old('email', $user->email) }}"
                                  >
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">
                                Contraseña 
                                @if(!$user->exists)
                                    <span class="text-danger">*</span>
                                @else
                                    <small class="text-muted">(opcional)</small>
                                @endif
                            </label>
                            <input name="password" type="password"
                                   class="form-control">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                                                <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">
                                Rol <span class="text-danger">*</span>
                            </label>
                            <select name="role" class="form-control">
                                <option value=""> Seleccionar rol </option>
                                @foreach($roles as $roleName)
                                    <option value="{{ $roleName }}"
                                        {{ old('role', $user->getRoleNames()->first()) === $roleName ? 'selected' : '' }}>
                                        {{ ucfirst($roleName) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                        <a href="{{ route('usuarios') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
