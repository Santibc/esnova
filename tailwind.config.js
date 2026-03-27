/** @type {import('tailwindcss').Config} */
export default {
  content: [
    // Rutas para tus vistas de Blade
    "./resources/**/*.blade.php",
    // Rutas para tus archivos JavaScript y Vue (si los usas)
    "./resources/**/*.js",
    "./resources/**/*.vue",
    // Rutas para las vistas de paginación de Laravel
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],

  theme: {
    extend: {
      // Si quieres usar Figtree como fuente sans-serif por defecto y mantener las de Tailwind
      fontFamily: {
        sans: ['Figtree', 'sans-serif'], // 'sans-serif' como fallback general
      },
          extend: {
      colors: {
        border: 'hsl(var(--border))',
        input: 'hsl(var(--input))',
        ring: 'hsl(var(--ring))',
        background: 'hsl(var(--background))',
        foreground: 'hsl(var(--foreground))',
        primary: { /* ... */ },
        secondary: {
          DEFAULT: 'hsl(var(--secondary))',
          foreground: 'hsl(var(--secondary-foreground))',
        },
        muted: {
          DEFAULT: 'hsl(var(--muted))',
          foreground: 'hsl(var(--muted-foreground))',
        },
        accent: {
          DEFAULT: 'hsl(var(--accent))',
          foreground: 'hsl(var(--accent-foreground))',
        },
        // ... otros colores
      },
    },
    },
  },

  plugins: [
    // Incluye el plugin de formularios de Tailwind CSS
    require('@tailwindcss/forms'),
    // Agrega otros plugins aquí si los necesitas
  ],
};