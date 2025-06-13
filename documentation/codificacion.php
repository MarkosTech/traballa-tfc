<div id="codificacion" class="section">
    <h2>4. Codificación y pruebas</h2>
    <div class="section-description">
        <p>Esta sección detalla las tecnologías utilizadas, la estructura del código y el plan de pruebas implementado durante el desarrollo.</p>
    </div>
    
    <div id="tecnologias">
        <h3>4.1 Tecnologías utilizadas</h3>
        <p>El desarrollo de Traballa ha implicado el uso de las siguientes tecnologías:</p>
        <ul>
            <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript (ES6+), Bootstrap 5</li>
            <li><strong>Backend:</strong> PHP 8, MySQL 8</li>
            <li><strong>Librerías y Frameworks:</strong> jQuery, Chart.js (para gráficos), FullCalendar (para el calendario), PHPMailer (para envío de emails)</li>
            <li><strong>Herramientas de Desarrollo:</strong> Git para control de versiones, Composer para gestión de dependencias PHP</li>
            <li><strong>Entorno de Despliegue:</strong> Servidor Apache, HTTPS con certificados SSL</li>
        </ul>
    </div>
    
    <div id="estructura-codigo">
        <h3>4.2 Estructura del código</h3>
        <p>El código fuente de Traballa está organizado siguiendo una estructura modular y mantenible:</p>
        <ul>
            <li><strong>config/:</strong> Archivos de configuración del sistema</li>
            <li><strong>includes/:</strong> Clases y funciones compartidas (modelos, utilidades)</li>
            <li><strong>pages/:</strong> Controladores y vistas principales</li>
            <li><strong>webroot/:</strong> Punto de entrada público y activos web (CSS, JS, imágenes)</li>
            <li><strong>ajax/:</strong> Endpoints para peticiones asíncronas</li>
            <li><strong>vendor/:</strong> Dependencias de terceros gestionadas por Composer</li>
        </ul>
        <p>El código sigue los estándares PSR para PHP y utiliza patrones de diseño como Singleton y Factory, junto con una separación básica de responsabilidades para mejorar la mantenibilidad y extensibilidad, sin seguir estrictamente el patrón MVC.</p>
    </div>
    
    <div id="plan-pruebas">
        <h3>4.3 Plan de pruebas</h3>
        <p>El plan de pruebas de Traballa incluye:</p>
        <ul>
            <li><strong>Pruebas Unitarias:</strong> Para componentes críticos como la lógica de cálculo de tiempos y la generación de informes.</li>
            <li><strong>Pruebas de Integración:</strong> Para verificar la correcta interacción entre módulos (por ejemplo, entre el registro de tiempo y el sistema de informes).</li>
            <li><strong>Pruebas de Usabilidad:</strong> Realizadas con usuarios potenciales para evaluar la experiencia de usuario y detectar problemas de diseño.</li>
            <li><strong>Pruebas de Rendimiento:</strong> Para garantizar tiempos de respuesta aceptables bajo diferentes niveles de carga.</li>
            <li><strong>Pruebas de Seguridad:</strong> Incluyendo análisis de vulnerabilidades comunes como inyección SQL, XSS y CSRF.</li>
        </ul>
        <p>Los resultados de las pruebas han sido documentados y los problemas identificados han sido corregidos antes de la entrega final del proyecto.</p>
    </div>
</div>
