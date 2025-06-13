<?php
/**
 * Traballa - Cookies policy
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */
?>

<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de cookies - Traballa Tracker</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 20px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .section {
            margin-bottom: 35px;
        }

        .section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border-color);
        }

        .section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 20px 0 10px 0;
        }

        .section p {
            margin-bottom: 15px;
            color: var(--text-color);
            text-align: justify;
        }

        .section ul, .section ol {
            margin: 15px 0;
            padding-left: 20px;
        }

        .section li {
            margin-bottom: 8px;
            color: var(--text-color);
        }

        .cookies-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cookies-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .cookies-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .cookies-table tr:last-child td {
            border-bottom: none;
        }

        .cookies-table tr:nth-child(even) {
            background-color: var(--bg-light);
        }

        .cookie-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .cookie-essential {
            background-color: #e6fffa;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .cookie-analytics {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .cookie-functional {
            background-color: #fefce8;
            color: #a16207;
            border: 1px solid #eab308;
        }

        .highlight {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .info-box {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid var(--primary-color);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .last-updated {
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .consent-controls {
            background: var(--bg-light);
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
        }

        .consent-controls h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .btn-cookie {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .btn-cookie:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .content-card {
                padding: 20px;
                margin: 10px 0;
            }
            
            .header h1 {
                font-size: 2rem;
            }

            .cookies-table {
                font-size: 0.9rem;
            }

            .cookies-table th,
            .cookies-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content-card">
            <a href="../" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver al inicio
            </a>

            <div class="header">
                <h1>Política de cookies</h1>
                <p>Información sobre el uso de cookies en Traballa</p>
            </div>

            <div class="section">
                <h2>1. ¿Qué son las cookies?</h2>
                <p>Las cookies son pequeños archivos de texto que se almacenan en su dispositivo (ordenador, tableta o móvil) cuando visita un sitio web. Las cookies permiten que el sitio web recuerde sus acciones y preferencias (como el idioma, tamaño de fuente y otras preferencias de visualización) durante un período de tiempo, para que no tenga que volver a configurarlas cada vez que regrese al sitio o navegue de una página a otra.</p>
            </div>

            <div class="section">
                <h2>2. ¿Cómo utilizamos las cookies?</h2>
                <p>En Traballa utilizamos cookies para:</p>
                <ul>
                    <li><strong>Funcionalidad esencial:</strong> Mantener la sesión del usuario y proporcionar funcionalidades básicas del sistema</li>
                    <li><strong>Preferencias del usuario:</strong> Recordar configuraciones como idioma, tema y preferencias de visualización</li>
                    <li><strong>Rendimiento y análisis:</strong> Entender cómo los usuarios interactúan con la aplicación para mejorar la experiencia</li>
                    <li><strong>Seguridad:</strong> Proteger contra ataques de seguridad y verificar la autenticidad de las solicitudes</li>
                </ul>
            </div>

            <div class="section">
                <h2>3. Tipos de cookies que utilizamos</h2>
                
                <table class="cookies-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Propósito</th>
                            <th>Duración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>PHPSESSID</strong></td>
                            <td><span class="cookie-type cookie-essential">Esencial</span></td>
                            <td>Identificador de sesión de PHP para mantener el estado de autenticación del usuario</td>
                            <td>Sesión</td>
                        </tr>
                        <tr>
                            <td><strong>user_preferences</strong></td>
                            <td><span class="cookie-type cookie-functional">Funcional</span></td>
                            <td>Almacena las preferencias del usuario como tema, idioma y configuraciones</td>
                            <td>30 días</td>
                        </tr>
                        <tr>
                            <td><strong>remember_token</strong></td>
                            <td><span class="cookie-type cookie-functional">Funcional</span></td>
                            <td>Permite la función "Recordarme" para mantener la sesión activa</td>
                            <td>30 días</td>
                        </tr>
                        <tr>
                            <td><strong>csrf_token</strong></td>
                            <td><span class="cookie-type cookie-essential">Esencial</span></td>
                            <td>Token de seguridad para prevenir ataques CSRF</td>
                            <td>Sesión</td>
                        </tr>
                        <tr>
                            <td><strong>cookies_accepted</strong></td>
                            <td><span class="cookie-type cookie-functional">Funcional</span></td>
                            <td>Recuerda si el usuario ha aceptado el uso de cookies</td>
                            <td>365 días</td>
                        </tr>
                        <tr>
                            <td><strong>analytics_data</strong></td>
                            <td><span class="cookie-type cookie-analytics">Analítica</span></td>
                            <td>Recopila información estadística sobre el uso de la aplicación</td>
                            <td>90 días</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>4. Categorías de cookies</h2>
                
                <h3>4.1 Cookies esenciales</h3>
                <div class="info-box">
                    <p><strong>Estas cookies son necesarias para el funcionamiento básico del sitio web y no se pueden desactivar.</strong></p>
                    <p>Incluyen cookies de sesión que permiten identificar al usuario durante su visita, mantener la seguridad y proporcionar funcionalidades básicas como el inicio de sesión.</p>
                </div>

                <h3>4.2 Cookies funcionales</h3>
                <div class="info-box">
                    <p><strong>Estas cookies mejoran la funcionalidad y personalización del sitio web.</strong></p>
                    <p>Permiten recordar las elecciones que hace el usuario (como idioma o región) y proporcionan características mejoradas y más personales.</p>
                </div>

                <h3>4.3 Cookies de rendimiento/analítica</h3>
                <div class="info-box">
                    <p><strong>Estas cookies nos ayudan a entender cómo interactúan los visitantes con el sitio web.</strong></p>
                    <p>Recopilan información de forma anónima sobre las páginas visitadas, tiempo en el sitio y cualquier mensaje de error que puedan encontrar.</p>
                </div>
            </div>

            <div class="section">
                <h2>5. Cookies de terceros</h2>
                <p>Algunos de nuestros servicios pueden utilizar cookies de terceros para proporcionar funcionalidades adicionales:</p>
                <ul>
                    <li><strong>Google Fonts:</strong> Para cargar fuentes web optimizadas</li>
                    <li><strong>Bootstrap CDN:</strong> Para estilos y componentes de interfaz</li>
                    <li><strong>Font Awesome:</strong> Para iconos del sistema</li>
                </ul>
                <p>Estas cookies están sujetas a las políticas de privacidad de sus respectivos proveedores.</p>
            </div>

            <div class="section">
                <h2>6. Gestión de cookies</h2>
                <h3>6.1 Control desde el navegador</h3>
                <p>Puede controlar y/o eliminar las cookies como desee. Puede eliminar todas las cookies que ya están en su ordenador y puede configurar la mayoría de navegadores para evitar que se coloquen. Sin embargo, si hace esto, es posible que tenga que ajustar manualmente algunas preferencias cada vez que visite un sitio y que algunos servicios y funcionalidades no funcionen.</p>

                <h3>6.2 Instrucciones por navegador</h3>
                <ul>
                    <li><strong>Chrome:</strong> Configuración > Privacidad y seguridad > Cookies y otros datos de sitios</li>
                    <li><strong>Firefox:</strong> Opciones > Privacidad y seguridad > Cookies y datos del sitio</li>
                    <li><strong>Safari:</strong> Preferencias > Privacidad > Gestionar datos de sitios web</li>
                    <li><strong>Edge:</strong> Configuración > Cookies y permisos del sitio > Cookies y datos del sitio</li>
                </ul>

                <h3>6.3 Consecuencias de deshabilitar cookies</h3>
                <p>Si deshabilita las cookies, algunas funcionalidades del sitio web pueden verse afectadas:</p>
                <ul>
                    <li>No podrá mantener su sesión iniciada</li>
                    <li>Sus preferencias no se recordarán</li>
                    <li>Algunas características personalizadas no funcionarán</li>
                    <li>La experiencia de usuario puede verse degradada</li>
                </ul>
            </div>

            <div class="section">
                <h2>7. Consentimiento y configuración</h2>
                <p>Al utilizar nuestro sitio web, usted acepta el uso de cookies de acuerdo con esta política. Puede retirar su consentimiento en cualquier momento mediante la configuración de su navegador o contactando con nosotros.</p>
                
                <div class="consent-controls">
                    <h3>Control de cookies</h3>
                    <p>Gestione sus preferencias de cookies:</p>
                    <a href="#" class="btn-cookie" onclick="acceptAllCookies()">
                        <i class="fas fa-check me-2"></i>Aceptar todas
                    </a>
                    <a href="#" class="btn-cookie" onclick="acceptEssentialOnly()">
                        <i class="fas fa-cog me-2"></i>Solo esenciales
                    </a>
                    <a href="#" class="btn-cookie" onclick="showCookieSettings()">
                        <i class="fas fa-sliders-h me-2"></i>Personalizar
                    </a>
                </div>
            </div>

            <div class="section">
                <h2>8. Actualizaciones de la política</h2>
                <p>Esta política de cookies puede ser actualizada periódicamente para reflejar cambios en nuestras prácticas o por otros motivos operativos, legales o reglamentarios. Le recomendamos que revise esta política regularmente para mantenerse informado sobre nuestro uso de cookies.</p>
            </div>

            <div class="section">
                <h2>9. Contacto</h2>
                <p>Si tiene preguntas sobre esta política de cookies o sobre el uso de cookies en nuestro sitio web, puede contactarnos a través de los medios indicados en nuestro <a href="/legal-advice">Aviso Legal</a>.</p>
            </div>

            <div class="highlight">
                <h3><i class="fas fa-shield-alt me-2"></i>Compromiso con la privacidad</h3>
                <p class="mb-0">En Traballa nos tomamos muy en serio la privacidad de nuestros usuarios. Utilizamos las cookies de manera responsable y transparente, siempre respetando la legislación vigente sobre protección de datos y privacidad online.</p>
            </div>

            <div class="last-updated">
                <p><strong>Última actualización:</strong> 13 de junio de 2025</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Cookie management functions
        function acceptAllCookies() {
            document.cookie = "cookies_accepted=all; max-age=" + (365*24*60*60) + "; path=/";
            document.cookie = "analytics_enabled=true; max-age=" + (365*24*60*60) + "; path=/";
            alert('Todas las cookies han sido aceptadas.');
        }

        function acceptEssentialOnly() {
            document.cookie = "cookies_accepted=essential; max-age=" + (365*24*60*60) + "; path=/";
            document.cookie = "analytics_enabled=false; max-age=" + (365*24*60*60) + "; path=/";
            alert('Solo se han aceptado las cookies esenciales.');
        }

        function showCookieSettings() {
            alert('Funcionalidad de configuración personalizada estará disponible próximamente.');
        }

        // Check if user has already made a choice
        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

        // Simple cookie banner (if not already accepted)
        window.addEventListener('load', function() {
            if (!getCookie('cookies_accepted')) {
                // Show cookie banner if needed
                console.log('Cookie consent not yet provided');
            }
        });
    </script>
</body>
</html>
