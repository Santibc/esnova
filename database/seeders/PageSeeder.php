<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear página Welcome
        $welcomePage = Page::create([
            'name' => 'welcome',
            'title' => 'Esnova - Tu ecosistema de crecimiento',
            'slug' => 'inicio',
            'description' => 'Plataforma de membresías para emprendedores y fundaciones en Colombia. Tu tienda online, eventos exclusivos y crecimiento sin límites.',
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
            'is_active' => true
        ]);

        // Crear SEO para la página Welcome
        Seo::create([
            'page_id' => $welcomePage->id,
            'meta_title' => 'Esnova - Plataforma de Membresías para Emprendedores y Fundaciones en Colombia',
            'meta_description' => 'Únete a la primera membresía en Colombia para emprendedores y fundaciones. Tienda online, eventos exclusivos, pagos seguros y crecimiento sin límites. ¡Regístrate gratis!',
            'meta_keywords' => 'emprendimiento colombia, fundaciones colombia, tienda online, eventos comerciales, membresía empresarial, Esnova, plataforma emprendedores',
            'canonical_url' => url('/'),
            'robots' => 'index,follow',

            // Open Graph
            'og_title' => 'Esnova - Tu ecosistema de crecimiento empresarial',
            'og_description' => 'Plataforma de membresías que conecta emprendedores y fundaciones con herramientas digitales, eventos exclusivos y oportunidades de crecimiento en Colombia.',
            'og_type' => 'website',
            'og_url' => url('/'),
            'og_site_name' => 'Esnova',

            // Twitter
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'Esnova - Plataforma para Emprendedores',
            'twitter_description' => 'Únete a la comunidad de emprendedores y fundaciones más grande de Colombia. Tienda online + eventos exclusivos.',

            // SEO adicional
            'focus_keyword' => 'emprendimiento colombia',
            'breadcrumb_title' => 'Inicio',
            'sitemap_include' => true,
            'sitemap_priority' => 1.0,
            'sitemap_changefreq' => 'weekly',
            'is_active' => true
        ]);
    }
}
