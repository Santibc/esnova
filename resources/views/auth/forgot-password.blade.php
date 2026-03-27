<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Esnova</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Three.js para el fondo 3D -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a2e;
            overflow-x: hidden;
        }

        #bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .content-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .form-input {
            border: 1px solid #D1D5DB;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #FF00C1;
            box-shadow: 0 0 0 3px rgba(255, 0, 193, 0.2);
        }

        .cta-button {
            background-color: #FF00C1;
            color: white;
            font-weight: 700;
            border-radius: 0.75rem;
            padding: 0.75rem;
            width: 100%;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background-color 0.3s, transform 0.2s;
            border: none;
            cursor: pointer;
        }
        .cta-button:hover {
            background-color: #E600AE;
            transform: translateY(-2px);
        }

        .back-link {
            color: black;
            text-decoration: underline;
            font-size: 0.875rem;
            text-align: center;
            display: block;
            margin-top: 1rem;
        }
        .back-link:hover {
            opacity: 0.8;
        }

        .brand-logo-img {
            max-width: 350px;
            width: 100%;
            height: auto;
            filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.25));
        }

        .description-text {
            text-align: center;
            color: #4B5563;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <!-- Canvas para el fondo 3D -->
    <canvas id="bg-canvas"></canvas>

    <!-- Contenedor principal -->
    <div class="content-container">

        <img src="{{ asset('images/logo1.png') }}" alt="Logo Esnova" class="brand-logo-img mb-4">

        <div class="login-card">
            <div class="description-text">
                {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">{{ __('Correo Electrónico') }}</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tu@correo.com">
                    @if ($errors->has('email'))
                        <div class="mt-2 text-sm text-red-600">
                            @foreach ($errors->get('email') as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit" class="cta-button mb-4">
                    {{ __('Enviar Enlace de Recuperación') }}
                </button>

                <a class="back-link" href="{{ route('login') }}">
                    {{ __('Volver al inicio de sesión') }}
                </a>
            </form>
        </div>

    </div>

    <script>
        // Configuración básica Three.js
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({
            canvas: document.querySelector('#bg-canvas'),
            alpha: true
        });

        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.position.setZ(30);

        // Creación de las partículas de fondo
        const particleCount = 5000;
        const positions = new Float32Array(particleCount * 3);
        const colors = new Float32Array(particleCount * 3);

        const colorMagenta = new THREE.Color(0xff00c1);
        const colorBlue = new THREE.Color(0x0b00f9);

        for (let i = 0; i < particleCount; i++) {
            const x = (Math.random() - 0.5) * 100;
            const y = (Math.random() - 0.5) * 100;
            const z = (Math.random() - 0.5) * 100;
            positions[i * 3] = x;
            positions[i * 3 + 1] = y;
            positions[i * 3 + 2] = z;

            const mixedColor = colorMagenta.clone().lerp(colorBlue, Math.random());
            colors[i * 3] = mixedColor.r;
            colors[i * 3 + 1] = mixedColor.g;
            colors[i * 3 + 2] = mixedColor.b;
        }

        const particlesGeometry = new THREE.BufferGeometry();
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.1,
            vertexColors: true,
            blending: THREE.AdditiveBlending
        });

        const particleSystem = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particleSystem);

        // Creación de Emojis Flotantes
        const emojiGroup = new THREE.Group();
        const emojis = ['🚀', '💲', '🎪', '✈️', '📦', '🛵'];

        function createEmojiTexture(emoji) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = 128;
            canvas.height = 128;
            context.font = '96px Arial';
            context.textAlign = 'center';
            context.textBaseline = 'middle';
            context.fillText(emoji, 64, 64);
            return new THREE.CanvasTexture(canvas);
        }

        emojis.forEach(emoji => {
            const texture = createEmojiTexture(emoji);
            for (let i = 0; i < 4; i++) {
                const material = new THREE.SpriteMaterial({ map: texture, transparent: true });
                const sprite = new THREE.Sprite(material);

                sprite.position.x = (Math.random() - 0.5) * 60;
                sprite.position.y = (Math.random() - 0.5) * 60;
                sprite.position.z = (Math.random() - 0.5) * 60;

                const scale = Math.random() * 2 + 1;
                sprite.scale.set(scale, scale, scale);

                emojiGroup.add(sprite);
            }
        });
        scene.add(emojiGroup);

        // Interacción con el ratón
        const mouse = new THREE.Vector2();
        window.addEventListener('mousemove', (event) => {
            mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
            mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
        });

        // Bucle de animación
        function animate() {
            requestAnimationFrame(animate);

            particleSystem.rotation.y += 0.0005;
            emojiGroup.rotation.y += 0.0008;
            emojiGroup.rotation.x += 0.0002;

            camera.position.x += (mouse.x * 5 - camera.position.x) * 0.05;
            camera.position.y += (mouse.y * 5 - camera.position.y) * 0.05;
            camera.lookAt(scene.position);

            renderer.render(scene, camera);
        }

        // Manejo del redimensionamiento
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Iniciar animación
        window.onload = function() {
            animate();
        }
    </script>
</body>
</html>
