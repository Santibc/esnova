<?php

namespace App\Http\Controllers;

use App\Models\PlanMembresia;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Mostrar página de bienvenida con planes de membresía
     */
    public function index()
    {
        // Obtener planes activos ordenados
        $planes = PlanMembresia::activos()->get();

        // Obtener contenido de la página welcome
        $page = Page::where('name', 'welcome')->where('is_active', true)->with('seo')->first();

        // Si no existe, crear contenido por defecto
        if (!$page) {
            $page = (object) [
                'content' => [
                    'hero_title' => '¡Hola Colombia!<br>Tu Negocio, <span>Nuestra causa.</span>',
                    'hero_subtitle' => 'Te abrimos la puerta a un ecosistema de <strong>crecimiento sin límites.</strong>',
                    'hero_benefits' => '🛒 Tienda Online al Instante|🚚 Pasarela de pago y Logística integrada|🎉 Festivales Exclusivos para nuestros miembros',
                    'hero_btn_primary' => 'Así lo hacemos posible',
                    'hero_btn_secondary' => 'Regístrate ahora',
                    'offer_subtitle' => '¡ACTIVA TU MARCA EN LO DIGITAL Y PRESENCIAL!',
                    'offer_title' => '¿Emprendes o lideras una fundación?',
                    'offer_description' => 'Con nosotros no solo accedes a una plataforma… ¡Abres la puerta a un <strong>ecosistema completo</strong> que se enfoca en atraer visitantes y potenciales clientes para ti.',
                    'card1_title' => 'Tu Tienda Online ¡Lista para Vender!',
                    'card1_description' => 'Lánzala en minutos y gestiona pagos seguros, envíos y estadísticas para el control total de tus ventas. 🧾',
                    'card1_icon' => 'bi-laptop',
                    'card2_title' => 'Tu Marca Siempre Visible.',
                    'card2_description' => '¡No solo vendes, tu marca conecta! ❤️ Creamos contenido audiovisual en nuestras redes que cuenta la historia de algunos de nuestros miembros, impulsando su reconocimiento. <strong>Somos tu aliado para hacerte conocer.</strong>',
                    'card2_icon' => 'fa-solid fa-handshake',
                    'card3_title' => '¡Brilla en nuestros eventos exclusivos!',
                    'card3_description' => 'Lleva tu marca al siguiente nivel en festivales comerciales. Tu tienda digital y física se fusionan para que solo te preocupes por vender, nosotros nos encargamos del resto y, <strong>¡nosotros ponemos la infraestructura!</strong> 🏠',
                    'card3_icon' => 'bi-star-fill',
                    'stats_title' => '¡Únete a la <strong>primera membresía</strong> en Colombia para Emprendedores y <strong>Fundaciones</strong>!',
                    'stats_subtitle' => 'Juntos, ya impactamos a más de:',
                    'stats_count' => 134,
                    'stats_label' => 'VISITANTES TOTALES',
                    'features_subtitle' => 'UN ECOSISTEMA COMPLETO Y LISTO PARA TI',
                    'features_title' => 'Sencillo, Rápido y Poderoso',
                    'features_intro' => 'Tu tienda,<br />Tus eventos, tu momento<br />Inscríbete en la lista de espera 🚨',
                    'step1_title' => 'Regístrate',
                    'step1_description' => 'Da el primer paso para impulsar tu marca. Crea tu cuenta <strong>sin costo</strong> y configúrala en minutos.',
                    'step1_icon' => 'bi-person-plus',
                    'step1_color' => 'pink',
                    'step2_title' => 'Activa',
                    'step2_description' => 'Elige tu <strong>membresía</strong> activála ✅ y accede a múltiples beneficios. <strong>¡Así podrás enfocarte solo en vender!</strong>',
                    'step2_icon' => 'bi-check-lg',
                    'step2_color' => 'blue',
                    'step3_title' => 'Agéndate',
                    'step3_description' => '<strong>Alquila tu espacio</strong> en nuestros festivales de comercio. ¡Tú solo vende, nosotros hacemos el resto!',
                    'step3_icon' => 'bi-calendar',
                    'step3_color' => 'pink',
                    'bt_subtitle' => 'Nacimos para transformar el emprendimiento y el impacto social en Colombia.',
                    'bt_title' => '"Emprender no debe ser imposible, debe ser accesible."',
                    'bt_description' => '<strong>Better Together:</strong> <em>Tu ecosistema único</em> y accesible <em>en Colombia.</em> Con nuestra <strong>plataforma y eventos exclusivos</strong>, te damos las herramientas para que solo te enfoques en tu negocio y vender.<br>¡Únete y transforma tu estrategia!',
                    'bt_footer' => 'Somos una inversión para tu marca',
                    'cta_title' => '¿Listo para llevar tu negocio al siguiente Nivel?',
                    'cta_description' => 'Forma parte de nuestra comunidad. <span class="highlight3">Regístrate en la lista de espera hoy y prepárate para crecer</span> con nosotros.',
                    'cta_button_text' => 'Enviar',
                    'social_text' => 'Síguenos en nuestras redes sociales',
                    'facebook_url' => '#',
                    'tiktok_url' => '#',
                    'instagram_url' => '#',
                    'linkedin_url' => '#',
                    'whatsapp_url' => 'https://wa.me/#',
                    'whatsapp_text' => 'Contáctanos vía Whatsapp',
                    'footer_rights' => '© Esnova.COM.CO - TODOS LOS DERECHOS RESERVADOS',
                    'footer_slogan' => 'TECNOLOGÍA ÚTIL, CERCANA Y SIN COMPLICACIONES.'
                ],
                'seo' => null
            ];
        }

        return view('welcome', compact('planes', 'page'));
    }

    /**
     * Procesar el formulario de contacto de la lista de espera
     */
    public function enviarFormularioContacto(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:emprendimiento,fundacion',
            'online' => 'required|in:si,no',
            'festival' => 'required|in:si,no',
            'redes_sociales' => 'nullable|string|max:255',
            'red_social' => 'nullable|in:facebook,instagram,tiktok',
            'participar_eventos' => 'required|in:no_interesado,si_claro,depende_evento',
            'email' => 'required|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'mensaje_adicional' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre es requerido',
            'tipo.required' => 'Debes seleccionar si eres emprendimiento o fundación',
            'tipo.in' => 'Tipo de negocio inválido',
            'online.required' => 'Debes indicar si ya vendes en línea',
            'online.in' => 'Opción inválida para ventas en línea',
            'festival.required' => 'Debes indicar si has invertido en festivales',
            'festival.in' => 'Opción inválida para festivales',
            'participar_eventos.required' => 'Debes indicar si te gustaría participar en eventos',
            'participar_eventos.in' => 'Opción inválida para participación en eventos',
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe tener un formato válido',
        ]);

        try {
            // Obtener todos los usuarios con rol admin
            $admins = User::role('admin')->get();
            
            // Si no hay usuarios con rol admin, usar el scope de administradores
            if ($admins->isEmpty()) {
                $admins = User::administradores()->get();
            }

            // Datos del formulario para el email
            $datosFormulario = [
                'nombre' => $request->nombre,
                'tipo' => $request->tipo == 'emprendimiento' ? 'Emprendimiento' : 'Fundación',
                'online' => $request->online == 'si' ? 'Sí' : 'No',
                'festival' => $request->festival == 'si' ? 'Sí' : 'No',
                'redes_sociales' => $request->redes_sociales,
                'red_social' => $request->red_social,
                'participar_eventos' => $this->formatearParticipacionEventos($request->participar_eventos),
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'mensaje_adicional' => $request->mensaje_adicional,
                'fecha_envio' => now()->format('d/m/Y H:i:s')
            ];

            // Enviar email a cada administrador
            foreach ($admins as $admin) {
                Mail::send('emails.formulario-contacto', $datosFormulario, function ($message) use ($admin, $datosFormulario) {
                    $message->to($admin->email, $admin->name)
                            ->subject('Nueva solicitud de lista de espera - ' . $datosFormulario['nombre'])
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            return back()->with('success', '¡Gracias por tu interés! Hemos recibido tu solicitud y nos pondremos en contacto contigo pronto.');

        } catch (\Exception $e) {
            \Log::error('Error enviando formulario de contacto: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al enviar tu solicitud. Por favor intenta nuevamente.')->withInput();
        }
    }

    /**
     * Formatear texto de participación en eventos
     */
    private function formatearParticipacionEventos($valor)
    {
        switch ($valor) {
            case 'no_interesado':
                return 'No está interesado';
            case 'si_claro':
                return 'Sí, claro';
            case 'depende_evento':
                return 'Depende del evento';
            default:
                return $valor;
        }
    }
}
