<?php
/**
 * Traballa - Legal advice
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
    <title>Aviso legal - Traballa Tracker</title>
    
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
            max-width: 800px;
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

        .highlight {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .contact-info {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
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
                <h1>Aviso legal</h1>
                <p>Información legal sobre el uso de Traballa</p>
            </div>

            <div class="section">
                <h2>1. Información general</h2>
                <p>En cumplimiento de lo establecido en la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa de los siguientes datos:</p>
                
                <div class="contact-info">
                    <h3>Datos del responsable:</h3>
                    <ul>
                        <li><strong>Titular:</strong> Marcos Núñez Fernández</li>
                        <li><strong>Proyecto:</strong> Traballa - Sistema de gestión de tiempo y productividad</li>
                        <li><strong>Naturaleza:</strong> Proyecto Fin de Ciclo - Desarrollo de Aplicaciones Web (DAW)</li>
                        <li><strong>Centro Educativo:</strong> CIFP A Carballeira - Marcos Valcarcel</li>
                        <li><strong>Sitio Web:</strong> <a href="https://traballa.me">https://traballa.me</a></li>
                        <li><strong>Repositorio:</strong> <a href="https://github.com/markostech/traballa-tfc">https://github.com/markostech/traballa-tfc</a></li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h2>2. Objeto y finalidad</h2>
                <p>Este sitio web tiene como finalidad principal:</p>
                <ul>
                    <li>Presentar el proyecto fin de ciclo "Traballa"</li>
                    <li>Demostrar las funcionalidades del sistema desarrollado</li>
                    <li>Proporcionar documentación técnica y de usuario</li>
                    <li>Servir como plataforma de evaluación académica</li>
                    <li>Facilitar el acceso a una instancia de demostración del sistema</li>
                </ul>
            </div>

            <div class="section">
                <h2>3. Condiciones de uso</h2>
                <h3>3.1 Acceso y navegación</h3>
                <p>El acceso a este sitio web es gratuito y no requiere registro previo para consultar la información general. Sin embargo, ciertas funcionalidades avanzadas pueden requerir registro de usuario.</p>

                <h3>3.2 Uso permitido</h3>
                <p>El usuario se compromete a utilizar el sitio web y sus contenidos de forma lícita, sin contravenir la legislación vigente ni lesionar los derechos e intereses de terceros.</p>

                <h3>3.3 Prohibiciones</h3>
                <p>Queda expresamente prohibido:</p>
                <ul>
                    <li>Realizar actividades ilícitas o contrarias a la buena fe</li>
                    <li>Difundir contenidos delictivos, violentos, pornográficos, racistas, xenófobos, ofensivos, de apología del terrorismo o que atenten contra los derechos humanos</li>
                    <li>Provocar daños en los sistemas físicos y lógicos del sitio web</li>
                    <li>Introducir o difundir virus informáticos o cualesquiera otros sistemas físicos o lógicos que sean susceptibles de provocar daños</li>
                    <li>Intentar acceder y, en su caso, utilizar las cuentas de correo electrónico de otros usuarios</li>
                </ul>
            </div>

            <div class="section">
                <h2>4. Propiedad intelectual e industrial</h2>
                <h3>4.1 Derechos de autor</h3>
                <p>Todos los contenidos de este sitio web, incluyendo a título enunciativo pero no limitativo, textos, fotografías, gráficos, imágenes, iconos, tecnología, software, así como su diseño gráfico y códigos fuente, constituyen una obra cuya propiedad pertenece a Marcos Núñez Fernández, sin que puedan entenderse cedidos al usuario ninguno de los derechos de explotación sobre los mismos más allá de lo estrictamente necesario para el correcto uso del sitio web.</p>

                <h3>4.2 Licencia de software</h3>
                <p>El código fuente de la aplicación Traballa se distribuye bajo licencia MIT, que permite el uso, copia, modificación y distribución del software, sujeto a las condiciones especificadas en dicha licencia.</p>

                <h3>4.3 Marcas y logotipos</h3>
                <p>Las marcas, nombres comerciales o signos distintivos son titularidad del desarrollador, quedando prohibida su utilización sin autorización previa y por escrito.</p>
            </div>

            <div class="section">
                <h2>5. Exclusión de garantías y responsabilidad</h2>
                <h3>5.1 Disponibilidad del servicio</h3>
                <p>No se garantiza la continuidad y disponibilidad de los contenidos, programas, informaciones o servicios del sitio web, ni que se encuentren libres de error, aunque se pondrán todos los medios para evitar estos inconvenientes.</p>

                <h3>5.2 Contenidos</h3>
                <p>No se garantiza la utilidad de los contenidos para actividades específicas ni su infalibilidad y, especialmente, aunque no de modo exclusivo, que los usuarios puedan efectivamente utilizar los contenidos.</p>

                <h3>5.3 Limitación de responsabilidad</h3>
                <p>En ningún caso se será responsable de cualesquiera daños y perjuicios que puedan derivarse de:</p>
                <ul>
                    <li>La falta de disponibilidad o continuidad del funcionamiento del sitio web</li>
                    <li>La defraudación de la utilidad que los usuarios hubieren podido atribuir al sitio web</li>
                    <li>La infidelidad, exactitud, exhaustividad y/o actualidad de los contenidos</li>
                    <li>La existencia de virus o demás componentes dañinos</li>
                </ul>
            </div>

            <div class="section">
                <h2>6. Enlaces externos</h2>
                <p>Los enlaces que pueden aparecer en el sitio web que dirijan a otros sitios web tienen finalidad exclusivamente informativa, sin que se ejerza control alguno sobre dichos sitios. No se asume responsabilidad alguna por los contenidos de dichos enlaces o de cualquier enlace incluido en los sitios enlazados.</p>
            </div>

            <div class="section">
                <h2>7. Protección de datos personales</h2>
                <p>Para información detallada sobre el tratamiento de datos personales, consulte nuestra <a href="/privacy-policy">Política de Privacidad</a>.</p>
                <p>El sitio web cumple con la normativa vigente en materia de protección de datos personales, especialmente el Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD).</p>
            </div>

            <div class="section">
                <h2>8. Política de cookies</h2>
                <p>Este sitio web utiliza cookies para mejorar la experiencia del usuario. Para información detallada sobre el uso de cookies, consulte nuestra <a href="/cookies-policy">Política de Cookies</a>.</p>
            </div>

            <div class="section">
                <h2>9. Modificaciones</h2>
                <p>El titular se reserva el derecho de efectuar sin previo aviso las modificaciones que considere oportunas en el sitio web, pudiendo cambiar, suprimir o añadir tanto los contenidos y servicios que se presten a través de la misma como la forma en la que éstos aparezcan presentados o localizados.</p>
            </div>

            <div class="section">
                <h2>10. Legislación aplicable y jurisdicción</h2>
                <p>Las presentes condiciones se rigen por la legislación española. Para la resolución de cualquier controversia que pudiera derivarse del acceso o uso de este sitio web, las partes se someten expresamente a la jurisdicción de los Juzgados y Tribunales españoles.</p>
            </div>

            <div class="section">
                <h2>11. Contacto</h2>
                <p>Para cualquier consulta relacionada con este aviso legal o el funcionamiento del sitio web, puede ponerse en contacto a través de los medios indicados en la sección de información general de este documento.</p>
            </div>

            <div class="highlight">
                <h3><i class="fas fa-info-circle me-2"></i>Nota importante</h3>
                <p class="mb-0">Este sitio web tiene fines educativos y de demostración como parte de un proyecto fin de ciclo. La información y servicios proporcionados están destinados principalmente para evaluación académica y demostración de competencias técnicas.</p>
            </div>

            <div class="last-updated">
                <p><strong>Última actualización:</strong> 13 de junio de 2025</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
