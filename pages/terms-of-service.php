<?php
/**
 * Traballa - Terms of service
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

require_once '../config/database.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Traballa Tracker</title>
    
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

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #ffffff 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .terms-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 2rem;
        }

        .terms-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .back-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .contact-info {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 0.75rem;
            border-left: 4px solid var(--primary-color);
        }

        .last-updated {
            background: rgba(102, 126, 234, 0.05);
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        /* Language toggle styles */
        .language-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50px;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .language-toggle button {
            background: transparent;
            border: 2px solid transparent;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
            min-width: 50px;
        }

        .language-toggle button.active {
            background: white;
            color: var(--primary-color);
            border-color: white;
        }

        .language-toggle button:hover:not(.active) {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Language content visibility */
        [data-lang="en"] {
            display: block;
        }

        [data-lang="es"] {
            display: none;
        }

        html[lang="es"] [data-lang="es"] {
            display: block;
        }

        html[lang="es"] [data-lang="en"] {
            display: none;
        }

        html[lang="en"] [data-lang="es"] {
            display: none;
        }

        html[lang="en"] [data-lang="en"] {
            display: block;
        }

        @media (max-width: 768px) {
            .terms-header {
                padding: 2rem 0 1rem;
            }
            
            .terms-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Language Toggle -->
    <div class="language-toggle">
        <button onclick="setLanguage('en')" id="btn-en" class="active">EN</button>
        <button onclick="setLanguage('es')" id="btn-es">ES</button>
    </div>

    <!-- English Header -->
    <div class="terms-header" data-lang="en">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-file-contract me-3"></i>Terms of Service
            </h1>
            <p class="lead mb-0">Understanding your rights and responsibilities</p>
        </div>
    </div>

    <!-- Spanish Header -->
    <div class="terms-header" data-lang="es">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-file-contract me-3"></i>Términos de Servicio
            </h1>
            <p class="lead mb-0">Entendiendo tus derechos y responsabilidades</p>
        </div>
    </div>

    <div class="container">
        <!-- English Content -->
        <div class="terms-container" data-lang="en">
            <div class="last-updated">
                <i class="fas fa-calendar-alt me-2"></i>
                <strong>Last Updated:</strong> <?php echo date('F j, Y'); ?>
            </div>

            <div class="highlight-box">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Important notice
                </h5>
                <p class="mb-0">
                    By using the Traballa Tracker application, you agree to comply with and be bound by these terms of service. 
                    Please read them carefully before using our services.
                </p>
            </div>

            <h3 class="section-title">1. Acceptance of terms</h3>
            <p>
                By accessing and using the Traballa Tracker ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. 
                If you do not agree to abide by the above, please do not use this service.
            </p>

            <h3 class="section-title">2. Description of service</h3>
            <p>
                Traballa Tracker is a web-based application designed to help users track their working hours, manage projects, 
                and generate reports. The service includes features such as:
            </p>
            <ul>
                <li>Time tracking and logging</li>
                <li>Project and organization management</li>
                <li>Report generation and analytics</li>
                <li>User collaboration tools</li>
                <li>Data export capabilities</li>
            </ul>

            <h3 class="section-title">3. User accounts and responsibilities</h3>
            <p>
                To use certain features of the Service, you must register for an account. You are responsible for:
            </p>
            <ul>
                <li>Maintaining the security of your account credentials</li>
                <li>All activities that occur under your account</li>
                <li>Providing accurate and current information</li>
                <li>Notifying us of any unauthorized use of your account</li>
            </ul>

            <h3 class="section-title">4. Acceptable use policy</h3>
            <p>You agree not to use the Service to:</p>
            <ul>
                <li>Violate any applicable laws or regulations</li>
                <li>Infringe on the rights of others</li>
                <li>Upload malicious code or conduct security attacks</li>
                <li>Attempt to gain unauthorized access to other accounts</li>
                <li>Use the service for commercial purposes without permission</li>
            </ul>

            <h3 class="section-title">5. Data and privacy</h3>
            <p>
                Your privacy is important to us. Our collection and use of personal information is governed by our 
                <a href="privacy-policy" class="text-primary">Privacy Policy</a>, which is incorporated into these Terms by reference.
            </p>
            <div class="highlight-box">
                <h6 class="text-primary">Key privacy points:</h6>
                <ul class="mb-0">
                    <li>We collect only necessary data for service functionality</li>
                    <li>Your data is stored securely and encrypted</li>
                    <li>We do not sell your personal information to third parties</li>
                    <li>You have the right to export or delete your data</li>
                </ul>
            </div>

            <h3 class="section-title">6. Intellectual property</h3>
            <p>
                The Traballa Tracker application is released under the MIT License. This means:
            </p>
            <ul>
                <li>The source code is freely available on <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">GitHub</a></li>
                <li>You may use, modify, and distribute the software subject to the MIT License terms</li>
                <li>All original content and trademarks remain the property of their respective owners</li>
            </ul>

            <h3 class="section-title">7. Service availability</h3>
            <p>
                While we strive to maintain high availability, we cannot guarantee that the Service will be available 100% of the time. 
                We reserve the right to:
            </p>
            <ul>
                <li>Perform scheduled maintenance</li>
                <li>Make emergency updates or repairs</li>
                <li>Suspend service for security reasons</li>
            </ul>

            <h3 class="section-title">8. Limitation of liability</h3>
            <p>
                The Service is provided "as is" without warranties of any kind. We shall not be liable for any damages 
                arising from the use or inability to use the Service, including but not limited to:
            </p>
            <ul>
                <li>Data loss or corruption</li>
                <li>Service interruptions</li>
                <li>Security breaches beyond our control</li>
                <li>Third-party integrations or services</li>
            </ul>

            <h3 class="section-title">9. Termination</h3>
            <p>
                Either party may terminate this agreement at any time. Upon termination:
            </p>
            <ul>
                <li>Your access to the Service will be discontinued</li>
                <li>You may request export of your data within 30 days</li>
                <li>We reserve the right to delete your data after reasonable notice</li>
            </ul>

            <h3 class="section-title">10. Changes to terms</h3>
            <p>
                We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. 
                Continued use of the Service after changes constitutes acceptance of the new terms.
            </p>

            <h3 class="section-title">11. Governing law</h3>
            <p>
                These terms shall be governed by and construed in accordance with applicable laws. Any disputes will be resolved 
                through appropriate legal channels.
            </p>

            <div class="contact-info">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-envelope me-2"></i>Contact information
                </h5>
                <p class="mb-2">
                    If you have any questions about these terms of service, please contact us:
                </p>
                <ul class="mb-0">
                    <li><strong>Project:</strong> Traballa Tracker</li>
                    <li><strong>Developer:</strong> Marcos Núñez Fernández</li>
                    <li><strong>Repository:</strong> <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">GitHub</a></li>
                    <li><strong>License:</strong> <a href="https://opensource.org/licenses/MIT" target="_blank" class="text-primary">MIT License</a></li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="login" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Spanish Content -->
        <div class="terms-container" data-lang="es">
            <div class="last-updated">
                <i class="fas fa-calendar-alt me-2"></i>
                <strong>Última actualización:</strong> <?php echo date('j F Y'); ?>
            </div>

            <div class="highlight-box">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Aviso importante
                </h5>
                <p class="mb-0">
                    Al usar la aplicación Traballa Tracker, aceptas cumplir con estos Términos de Servicio. 
                    Por favor, léelos cuidadosamente antes de usar nuestros servicios.
                </p>
            </div>

            <h3 class="section-title">1. Aceptación de términos</h3>
            <p>
                Al acceder y usar Traballa Tracker ("el Servicio"), aceptas estar vinculado por los términos y condiciones de este acuerdo. 
                Si no estás de acuerdo con lo anterior, por favor no uses este servicio.
            </p>

            <h3 class="section-title">2. Descripción del servicio</h3>
            <p>
                Traballa Tracker es una aplicación web diseñada para ayudar a los usuarios a rastrear sus horas de trabajo, gestionar proyectos 
                y generar informes. El servicio incluye características como:
            </p>
            <ul>
                <li>Seguimiento y registro de tiempo</li>
                <li>Gestión de proyectos y organizaciones</li>
                <li>Generación de informes y análisis</li>
                <li>Herramientas de colaboración de usuarios</li>
                <li>Capacidades de exportación de datos</li>
            </ul>

            <h3 class="section-title">3. Cuentas de usuario y responsabilidades</h3>
            <p>
                Para usar ciertas características del Servicio, debes registrar una cuenta. Eres responsable de:
            </p>
            <ul>
                <li>Mantener la seguridad de las credenciales de tu cuenta</li>
                <li>Todas las actividades que ocurran bajo tu cuenta</li>
                <li>Proporcionar información precisa y actualizada</li>
                <li>Notificarnos de cualquier uso no autorizado de tu cuenta</li>
            </ul>

            <h3 class="section-title">4. Política de uso aceptable</h3>
            <p>Aceptas no usar el Servicio para:</p>
            <ul>
                <li>Violar cualquier ley o regulación aplicable</li>
                <li>Infringir los derechos de otros</li>
                <li>Subir código malicioso o realizar ataques de seguridad</li>
                <li>Intentar obtener acceso no autorizado a otras cuentas</li>
                <li>Usar el servicio para propósitos comerciales sin permiso</li>
            </ul>

            <h3 class="section-title">5. Datos y privacidad</h3>
            <p>
                Tu privacidad es importante para nosotros. Nuestra recopilación y uso de información personal se rige por nuestra 
                <a href="privacy-policy" class="text-primary">Política de Privacidad</a>, que se incorpora a estos Términos por referencia.
            </p>
            <div class="highlight-box">
                <h6 class="text-primary">Puntos clave de privacidad:</h6>
                <ul class="mb-0">
                    <li>Solo recopilamos datos necesarios para la funcionalidad del servicio</li>
                    <li>Tus datos se almacenan de forma segura y cifrada</li>
                    <li>No vendemos tu información personal a terceros</li>
                    <li>Tienes derecho a exportar o eliminar tus datos</li>
                </ul>
            </div>

            <h3 class="section-title">6. Propiedad intelectual</h3>
            <p>
                La aplicación Traballa Tracker se publica bajo la Licencia MIT. Esto significa:
            </p>
            <ul>
                <li>El código fuente está disponible libremente en <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">GitHub</a></li>
                <li>Puedes usar, modificar y distribuir el software sujeto a los términos de la Licencia MIT</li>
                <li>Todo el contenido original y marcas comerciales siguen siendo propiedad de sus respectivos dueños</li>
            </ul>

            <h3 class="section-title">7. Disponibilidad del servicio</h3>
            <p>
                Aunque nos esforzamos por mantener alta disponibilidad, no podemos garantizar que el Servicio esté disponible el 100% del tiempo. 
                Nos reservamos el derecho a:
            </p>
            <ul>
                <li>Realizar mantenimiento programado</li>
                <li>Hacer actualizaciones o reparaciones de emergencia</li>
                <li>Suspender el servicio por razones de seguridad</li>
            </ul>

            <h3 class="section-title">8. Limitación de responsabilidad</h3>
            <p>
                El Servicio se proporciona "tal como está" sin garantías de ningún tipo. No seremos responsables por daños 
                que surjan del uso o la incapacidad de usar el Servicio, incluyendo pero no limitado a:
            </p>
            <ul>
                <li>Pérdida o corrupción de datos</li>
                <li>Interrupciones del servicio</li>
                <li>Violaciones de seguridad fuera de nuestro control</li>
                <li>Integraciones o servicios de terceros</li>
            </ul>

            <h3 class="section-title">9. Terminación</h3>
            <p>
                Cualquiera de las partes puede terminar este acuerdo en cualquier momento. Al terminar:
            </p>
            <ul>
                <li>Tu acceso al Servicio será discontinuado</li>
                <li>Puedes solicitar la exportación de tus datos dentro de 30 días</li>
                <li>Nos reservamos el derecho a eliminar tus datos después de un aviso razonable</li>
            </ul>

            <h3 class="section-title">10. Cambios en los términos</h3>
            <p>
                Nos reservamos el derecho a modificar estos términos en cualquier momento. Los cambios serán efectivos inmediatamente al publicarse. 
                El uso continuado del Servicio después de los cambios constituye aceptación de los nuevos términos.
            </p>

            <h3 class="section-title">11. Ley aplicable</h3>
            <p>
                Estos términos se regirán e interpretarán de acuerdo con las leyes aplicables. Cualquier disputa se resolverá 
                a través de los canales legales apropiados.
            </p>

            <div class="contact-info">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-envelope me-2"></i>Información de contacto
                </h5>
                <p class="mb-2">
                    Si tienes alguna pregunta sobre estos Términos de Servicio, por favor contáctanos:
                </p>
                <ul class="mb-0">
                    <li><strong>Proyecto:</strong> Traballa Tracker</li>
                    <li><strong>Desarrollador:</strong> Marcos Núñez Fernández</li>
                    <li><strong>Repositorio:</strong> <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">GitHub</a></li>
                    <li><strong>Licencia:</strong> <a href="https://opensource.org/licenses/MIT" target="_blank" class="text-primary">Licencia MIT</a></li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="login" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Language switching functionality
        function setLanguage(lang) {
            const htmlElement = document.getElementById('html-root');
            const enBtn = document.getElementById('btn-en');
            const esBtn = document.getElementById('btn-es');
            
            // Update HTML lang attribute
            htmlElement.setAttribute('lang', lang);
            
            // Update button states
            if (lang === 'en') {
                enBtn.classList.add('active');
                esBtn.classList.remove('active');
                // Update page title
                document.title = 'Terms of Service - Traballa Tracker';
            } else {
                esBtn.classList.add('active');
                enBtn.classList.remove('active');
                // Update page title
                document.title = 'Términos de Servicio - Traballa Tracker';
            }
            
            // Store preference in localStorage
            localStorage.setItem('preferredLanguage', lang);
        }
        
        // Load preferred language on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('preferredLanguage');
            const browserLang = navigator.language.startsWith('es') ? 'es' : 'en';
            const defaultLang = savedLang || browserLang;
            
            setLanguage(defaultLang);
        });
    </script>
</body>
</html>
