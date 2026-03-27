<!-- Editor específico para la página Welcome -->
<div class="welcome-content-editor">
    <!-- Sección Hero -->
    <div class="editor-section">
        <h6><i class="bi bi-star-fill me-2"></i>Sección Hero Principal</h6>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Título Principal</label>
                <input type="text" class="form-control mb-3" name="content[hero_title]"
                       value="{{ old('content.hero_title', $page->content['hero_title'] ?? '¡Hola Colombia! Tu Negocio, Nuestra causa.') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtítulo</label>
                <input type="text" class="form-control mb-3" name="content[hero_subtitle]"
                       value="{{ old('content.hero_subtitle', $page->content['hero_subtitle'] ?? 'Te abrimos la puerta a un ecosistema de crecimiento sin límites.') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Beneficios (separados por |)</label>
            <textarea class="form-control" name="content[hero_benefits]" rows="3">{{ old('content.hero_benefits', $page->content['hero_benefits'] ?? '🛒 Tienda Online al Instante|🚚 Pasarela de pago y Logística integrada|🎉 Festivales Exclusivos para nuestros miembros') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Texto Botón Principal</label>
                <input type="text" class="form-control" name="content[hero_btn_primary]"
                       value="{{ old('content.hero_btn_primary', $page->content['hero_btn_primary'] ?? 'Así lo hacemos posible') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Texto Botón Secundario</label>
                <input type="text" class="form-control" name="content[hero_btn_secondary]"
                       value="{{ old('content.hero_btn_secondary', $page->content['hero_btn_secondary'] ?? 'Regístrate ahora') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Logo Principal</label>
                <input type="file" class="form-control" name="logo_principal" accept="image/*">
                @if($page->content['logo_principal'] ?? false)
                    <div class="mt-2">
                        <img src="{{ asset($page->content['logo_principal']) }}" alt="Logo actual" style="max-height: 60px;">
                        <small class="d-block text-muted">Logo actual</small>
                    </div>
                @endif
                <small class="form-text text-muted">Recomendado: 200x80 px (PNG con fondo transparente)</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Favicon (Icono pestaña)</label>
                <input type="file" class="form-control" name="favicon" accept="image/*">
                @if($page->content['favicon'] ?? false)
                    <div class="mt-2">
                        <img src="{{ asset($page->content['favicon']) }}" alt="Favicon actual" style="max-height: 32px;">
                        <small class="d-block text-muted">Favicon actual</small>
                    </div>
                @endif
                <small class="form-text text-muted">Recomendado: 32x32 px (ICO o PNG)</small>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Oferta -->
    <div class="editor-section">
        <h6><i class="bi bi-gift-fill me-2"></i>Sección de Oferta</h6>

        <div class="mb-3">
            <label class="form-label">Subtítulo</label>
            <input type="text" class="form-control" name="content[offer_subtitle]"
                   value="{{ old('content.offer_subtitle', $page->content['offer_subtitle'] ?? '¡ACTIVA TU MARCA EN LO DIGITAL Y PRESENCIAL!') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Título Principal</label>
            <input type="text" class="form-control" name="content[offer_title]"
                   value="{{ old('content.offer_title', $page->content['offer_title'] ?? '¿Emprendes o lideras una fundación?') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="content[offer_description]" rows="3">{{ old('content.offer_description', $page->content['offer_description'] ?? 'Con nosotros no solo accedes a una plataforma… ¡Abres la puerta a un ecosistema completo que se enfoca en atraer visitantes y potenciales clientes para ti.') }}</textarea>
        </div>

        <!-- Tarjetas de servicios -->
        <h6 class="mt-4">Tarjetas de Servicios</h6>

        <!-- Tarjeta 1 -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Tarjeta 1: Tienda Online</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[card1_title]"
                           value="{{ old('content.card1_title', $page->content['card1_title'] ?? 'Tu Tienda Online ¡Lista para Vender!') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[card1_description]" rows="2">{{ old('content.card1_description', $page->content['card1_description'] ?? 'Lánzala en minutos y gestiona pagos seguros, envíos y estadísticas para el control total de tus ventas. 🧾') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[card1_icon]"
                           value="{{ old('content.card1_icon', $page->content['card1_icon'] ?? 'bi-laptop') }}">
                </div>
            </div>
        </div>

        <!-- Tarjeta 2 -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Tarjeta 2: Marca Visible</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[card2_title]"
                           value="{{ old('content.card2_title', $page->content['card2_title'] ?? 'Tu Marca Siempre Visible.') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[card2_description]" rows="2">{{ old('content.card2_description', $page->content['card2_description'] ?? '¡No solo vendes, tu marca conecta! ❤️ Creamos contenido audiovisual en nuestras redes que cuenta la historia de algunos de nuestros miembros, impulsando su reconocimiento. Somos tu aliado para hacerte conocer.') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[card2_icon]"
                           value="{{ old('content.card2_icon', $page->content['card2_icon'] ?? 'fa-solid fa-handshake') }}">
                </div>
            </div>
        </div>

        <!-- Tarjeta 3 -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Tarjeta 3: Eventos</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[card3_title]"
                           value="{{ old('content.card3_title', $page->content['card3_title'] ?? '¡Brilla en nuestros eventos exclusivos!') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[card3_description]" rows="2">{{ old('content.card3_description', $page->content['card3_description'] ?? 'Lleva tu marca al siguiente nivel en festivales comerciales. Tu tienda digital y física se fusionan para que solo te preocupes por vender, nosotros nos encargamos del resto y, ¡nosotros ponemos la infraestructura! 🏠') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[card3_icon]"
                           value="{{ old('content.card3_icon', $page->content['card3_icon'] ?? 'bi-star-fill') }}">
                </div>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Estadísticas -->
    <div class="editor-section">
        <h6><i class="bi bi-graph-up me-2"></i>Sección de Estadísticas</h6>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Título Principal</label>
                <input type="text" class="form-control" name="content[stats_title]"
                       value="{{ old('content.stats_title', $page->content['stats_title'] ?? '¡Únete a la primera membresía en Colombia para Emprendedores y Fundaciones!') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtítulo</label>
                <input type="text" class="form-control" name="content[stats_subtitle]"
                       value="{{ old('content.stats_subtitle', $page->content['stats_subtitle'] ?? 'Juntos, ya impactamos a más de:') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Número del contador</label>
                <input type="number" class="form-control" name="content[stats_count]"
                       value="{{ old('content.stats_count', $page->content['stats_count'] ?? '134') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Texto del contador</label>
                <input type="text" class="form-control" name="content[stats_label]"
                       value="{{ old('content.stats_label', $page->content['stats_label'] ?? 'VISITANTES TOTALES') }}">
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Características -->
    <div class="editor-section">
        <h6><i class="bi bi-check-circle-fill me-2"></i>Sección "Sencillo, Rápido y Poderoso"</h6>

        <div class="mb-3">
            <label class="form-label">Subtítulo</label>
            <input type="text" class="form-control" name="content[features_subtitle]"
                   value="{{ old('content.features_subtitle', $page->content['features_subtitle'] ?? 'UN ECOSISTEMA COMPLETO Y LISTO PARA TI') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="content[features_title]"
                   value="{{ old('content.features_title', $page->content['features_title'] ?? 'Sencillo, Rápido y Poderoso') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Introducción</label>
            <textarea class="form-control" name="content[features_intro]" rows="3">{{ old('content.features_intro', $page->content['features_intro'] ?? 'Tu tienda, Tus eventos, tu momento Inscríbete en la lista de espera 🚨') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen de la sección</label>
            <input type="file" class="form-control" name="imagen_seccion_principal" accept="image/*">
            @if($page->content['imagen_seccion_principal'] ?? false)
                <div class="mt-2">
                    <img src="{{ asset($page->content['imagen_seccion_principal']) }}" alt="Imagen sección principal actual" style="max-height: 100px;">
                    <small class="d-block text-muted">Imagen actual</small>
                </div>
            @endif
            <small class="form-text text-muted">Recomendado: 600x400 px</small>
        </div>

        <!-- Pasos del proceso -->
        <h6 class="mt-4">Pasos del Proceso</h6>

        <!-- Paso 1: Regístrate -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Paso 1: Regístrate</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[step1_title]"
                           value="{{ old('content.step1_title', $page->content['step1_title'] ?? 'Regístrate') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[step1_description]" rows="2">{{ old('content.step1_description', $page->content['step1_description'] ?? 'Da el primer paso para impulsar tu marca. Crea tu cuenta sin costo y configúrala en minutos.') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[step1_icon]"
                           value="{{ old('content.step1_icon', $page->content['step1_icon'] ?? 'bi-person-plus') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Color del icono</label>
                    <select class="form-select" name="content[step1_color]">
                        <option value="pink" {{ old('content.step1_color', $page->content['step1_color'] ?? 'pink') === 'pink' ? 'selected' : '' }}>Rosa</option>
                        <option value="blue" {{ old('content.step1_color', $page->content['step1_color'] ?? '') === 'blue' ? 'selected' : '' }}>Azul</option>
                        <option value="green" {{ old('content.step1_color', $page->content['step1_color'] ?? '') === 'green' ? 'selected' : '' }}>Verde</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Paso 2: Activa -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Paso 2: Activa</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[step2_title]"
                           value="{{ old('content.step2_title', $page->content['step2_title'] ?? 'Activa') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[step2_description]" rows="2">{{ old('content.step2_description', $page->content['step2_description'] ?? 'Elige tu membresía activála ✅ y accede a múltiples beneficios. ¡Así podrás enfocarte solo en vender!') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[step2_icon]"
                           value="{{ old('content.step2_icon', $page->content['step2_icon'] ?? 'bi-check-lg') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Color del icono</label>
                    <select class="form-select" name="content[step2_color]">
                        <option value="pink" {{ old('content.step2_color', $page->content['step2_color'] ?? '') === 'pink' ? 'selected' : '' }}>Rosa</option>
                        <option value="blue" {{ old('content.step2_color', $page->content['step2_color'] ?? 'blue') === 'blue' ? 'selected' : '' }}>Azul</option>
                        <option value="green" {{ old('content.step2_color', $page->content['step2_color'] ?? '') === 'green' ? 'selected' : '' }}>Verde</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Paso 3: Agéndate -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <strong>Paso 3: Agéndate</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="content[step3_title]"
                           value="{{ old('content.step3_title', $page->content['step3_title'] ?? 'Agéndate') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="content[step3_description]" rows="2">{{ old('content.step3_description', $page->content['step3_description'] ?? 'Alquila tu espacio en nuestros festivales de comercio. ¡Tú solo vende, nosotros hacemos el resto!') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" class="form-control" name="content[step3_icon]"
                           value="{{ old('content.step3_icon', $page->content['step3_icon'] ?? 'bi-calendar') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Color del icono</label>
                    <select class="form-select" name="content[step3_color]">
                        <option value="pink" {{ old('content.step3_color', $page->content['step3_color'] ?? 'pink') === 'pink' ? 'selected' : '' }}>Rosa</option>
                        <option value="blue" {{ old('content.step3_color', $page->content['step3_color'] ?? '') === 'blue' ? 'selected' : '' }}>Azul</option>
                        <option value="green" {{ old('content.step3_color', $page->content['step3_color'] ?? '') === 'green' ? 'selected' : '' }}>Verde</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Better Together -->
    <div class="editor-section">
        <h6><i class="bi bi-heart-fill me-2"></i>Sección Better Together</h6>

        <div class="mb-3">
            <label class="form-label">Subtítulo</label>
            <input type="text" class="form-control" name="content[bt_subtitle]"
                   value="{{ old('content.bt_subtitle', $page->content['bt_subtitle'] ?? 'Nacimos para transformar el emprendimiento y el impacto social en Colombia.') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Título Principal</label>
            <input type="text" class="form-control" name="content[bt_title]"
                   value="{{ old('content.bt_title', $page->content['bt_title'] ?? 'Emprender no debe ser imposible, debe ser accesible.') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Texto Descriptivo</label>
            <textarea class="form-control" name="content[bt_description]" rows="4">{{ old('content.bt_description', $page->content['bt_description'] ?? 'Better Together: Tu ecosistema único y accesible en Colombia. Con nuestra plataforma y eventos exclusivos, te damos las herramientas para que solo te enfoques en tu negocio y vender. ¡Únete y transforma tu estrategia!') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Texto Footer</label>
            <input type="text" class="form-control" name="content[bt_footer]"
                   value="{{ old('content.bt_footer', $page->content['bt_footer'] ?? 'Somos una inversión para tu marca') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen Better Together</label>
            <input type="file" class="form-control" name="imagen_better_together" accept="image/*">
            @if($page->content['imagen_better_together'] ?? false)
                <div class="mt-2">
                    <img src="{{ asset($page->content['imagen_better_together']) }}" alt="Imagen Better Together actual" style="max-height: 100px;">
                    <small class="d-block text-muted">Imagen actual</small>
                </div>
            @endif
            <small class="form-text text-muted">Recomendado: 500x350 px</small>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Acceso Exclusivo -->
    <div class="editor-section">
        <h6><i class="bi bi-lock-fill me-2"></i>Sección "Acceso Exclusivo"</h6>

        <div class="mb-3">
            <label class="form-label">Subtítulo Superior</label>
            <input type="text" class="form-control" name="content[access_subtitle]"
                   value="{{ old('content.access_subtitle', $page->content['access_subtitle'] ?? 'Acceso exclusivo por inscripción anticipada') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Título Principal</label>
            <input type="text" class="form-control" name="content[access_title]"
                   value="{{ old('content.access_title', $page->content['access_title'] ?? 'Todo lo que necesitas para crecer') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="content[access_description]" rows="4">{{ old('content.access_description', $page->content['access_description'] ?? 'El acceso a Better Together es limitado en esta fase inicial.<br><strong>¡Solo quienes se registren en la lista de espera</strong> podrán ser parte de este grupo!<br><strong>No te quedes por fuera y asegura tu lugar ahora.</strong>') }}</textarea>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Call to Action Final -->
    <div class="editor-section">
        <h6><i class="bi bi-megaphone-fill me-2"></i>Call to Action Final</h6>

        <div class="mb-3">
            <label class="form-label">Título Principal</label>
            <input type="text" class="form-control" name="content[cta_title]"
                   value="{{ old('content.cta_title', $page->content['cta_title'] ?? '¿Listo para llevar tu negocio al siguiente Nivel?') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="content[cta_description]" rows="2">{{ old('content.cta_description', $page->content['cta_description'] ?? 'Forma parte de nuestra comunidad. Regístrate en la lista de espera hoy y prepárate para crecer con nosotros.') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Texto del botón del formulario</label>
            <input type="text" class="form-control" name="content[cta_button_text]"
                   value="{{ old('content.cta_button_text', $page->content['cta_button_text'] ?? 'Enviar') }}">
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Footer Social -->
    <div class="editor-section">
        <h6><i class="bi bi-share-fill me-2"></i>Redes Sociales Footer</h6>

        <div class="mb-3">
            <label class="form-label">Texto de seguimiento</label>
            <input type="text" class="form-control" name="content[social_text]"
                   value="{{ old('content.social_text', $page->content['social_text'] ?? 'Síguenos en nuestras redes sociales') }}">
        </div>

        <div class="row">
            <div class="col-md-3">
                <label class="form-label">URL Facebook</label>
                <input type="url" class="form-control" name="content[facebook_url]"
                       value="{{ old('content.facebook_url', $page->content['facebook_url'] ?? '#') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">URL TikTok</label>
                <input type="url" class="form-control" name="content[tiktok_url]"
                       value="{{ old('content.tiktok_url', $page->content['tiktok_url'] ?? '#') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">URL Instagram</label>
                <input type="url" class="form-control" name="content[instagram_url]"
                       value="{{ old('content.instagram_url', $page->content['instagram_url'] ?? '#') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">URL LinkedIn</label>
                <input type="url" class="form-control" name="content[linkedin_url]"
                       value="{{ old('content.linkedin_url', $page->content['linkedin_url'] ?? '#') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">URL WhatsApp</label>
                <input type="url" class="form-control" name="content[whatsapp_url]"
                       value="{{ old('content.whatsapp_url', $page->content['whatsapp_url'] ?? 'https://wa.me/#') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Texto botón WhatsApp</label>
                <input type="text" class="form-control" name="content[whatsapp_text]"
                       value="{{ old('content.whatsapp_text', $page->content['whatsapp_text'] ?? 'Contáctanos vía Whatsapp') }}">
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Beneficios -->
    <div class="editor-section">
        <h6><i class="bi bi-gift-fill me-2"></i>Sección "Beneficios" (4 puntos)</h6>

        <!-- Beneficio 1 -->
        <div class="mb-3">
            <label class="form-label">Beneficio 1 - Título</label>
            <input type="text" class="form-control" name="content[benefit1_title]"
                   value="{{ old('content.benefit1_title', $page->content['benefit1_title'] ?? 'Lanza tu e-commerce en minutos') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Beneficio 1 - Descripción</label>
            <textarea class="form-control" rows="2" name="content[benefit1_description]">{{ old('content.benefit1_description', $page->content['benefit1_description'] ?? 'gestiona tus pedidos, inventario y potencia tus ingresos') }}</textarea>
        </div>

        <!-- Beneficio 2 -->
        <div class="mb-3">
            <label class="form-label">Beneficio 2 - Título</label>
            <input type="text" class="form-control" name="content[benefit2_title]"
                   value="{{ old('content.benefit2_title', $page->content['benefit2_title'] ?? 'Pagos rápidos y directos') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Beneficio 2 - Descripción</label>
            <textarea class="form-control" rows="2" name="content[benefit2_description]">{{ old('content.benefit2_description', $page->content['benefit2_description'] ?? 'Pagos ágiles y seguros usando nuestra plataforma en lo digital y en festivales.') }}</textarea>
        </div>

        <!-- Beneficio 3 -->
        <div class="mb-3">
            <label class="form-label">Beneficio 3 - Título</label>
            <input type="text" class="form-control" name="content[benefit3_title]"
                   value="{{ old('content.benefit3_title', $page->content['benefit3_title'] ?? 'Logística Optimizada para ti') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Beneficio 3 - Descripción</label>
            <textarea class="form-control" rows="2" name="content[benefit3_description]">{{ old('content.benefit3_description', $page->content['benefit3_description'] ?? 'Cotiza y gestiona tus despachos con las transportadoras, ¡todo desde nuestra plataforma!') }}</textarea>
        </div>

        <!-- Beneficio 4 -->
        <div class="mb-3">
            <label class="form-label">Beneficio 4 - Título</label>
            <input type="text" class="form-control" name="content[benefit4_title]"
                   value="{{ old('content.benefit4_title', $page->content['benefit4_title'] ?? 'Presencia en eventos físicos') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Beneficio 4 - Descripción</label>
            <textarea class="form-control" rows="2" name="content[benefit4_description]">{{ old('content.benefit4_description', $page->content['benefit4_description'] ?? 'Participa en festivales de comercio con afluencia de público. Conecta con nuevos clientes e impulsa tus ventas.') }}</textarea>
        </div>

        <!-- Botón CTA Central -->
        <div class="mb-3">
            <label class="form-label">Texto del botón central</label>
            <input type="text" class="form-control" name="content[benefits_cta_text]"
                   value="{{ old('content.benefits_cta_text', $page->content['benefits_cta_text'] ?? '¡EMPIEZA AHORA!') }}">
        </div>

        <!-- Imagen Central de Beneficios -->
        <div class="mb-3">
            <label class="form-label">Imagen central de beneficios</label>
            <input type="file" class="form-control" name="imagen_beneficios" accept="image/*">
            @if($page->content['imagen_beneficios'] ?? false)
                <div class="mt-2">
                    <img src="{{ asset($page->content['imagen_beneficios']) }}" alt="Imagen beneficios actual" style="max-height: 100px;">
                    <small class="d-block text-muted">Imagen actual</small>
                </div>
            @endif
            <small class="form-text text-muted">Recomendado: 400x300 px</small>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Sección Footer -->
    <div class="editor-section">
        <h6><i class="bi bi-building me-2"></i>Footer</h6>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Texto de derechos</label>
                <input type="text" class="form-control" name="content[footer_rights]"
                       value="{{ old('content.footer_rights', $page->content['footer_rights'] ?? '© Esnova.COM.CO - TODOS LOS DERECHOS RESERVADOS') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Slogan</label>
                <input type="text" class="form-control" name="content[footer_slogan]"
                       value="{{ old('content.footer_slogan', $page->content['footer_slogan'] ?? 'TECNOLOGÍA ÚTIL, CERCANA Y SIN COMPLICACIONES.') }}">
            </div>
        </div>
    </div>
</div>