<div id="despliegue" class="section">
    <h2>6. Despliegue del Sistema</h2>
    <div class="section-description">
        <p>Esta sección describe las diferentes opciones y procedimientos para el despliegue del sistema Traballa en entornos de producción, incluyendo configuraciones para servidores dedicados y hosting compartido.</p>
    </div>
    
    <div id="opciones-despliegue">
        <h3>6.1 Opciones de Despliegue</h3>
        <p>El sistema Traballa puede desplegarse en diferentes tipos de entornos según las necesidades del usuario:</p>
        
        <div class="deployment-option">
            <h4>6.1.1 Servidor Dedicado/VPS con AlmaLinux 9</h4>
            <p>Opción recomendada para organizaciones que requieren control total sobre el servidor y personalización avanzada.</p>
            <ul>
                <li><strong>Sistema Operativo:</strong> AlmaLinux 9 (distribución empresarial basada en RHEL)</li>
                <li><strong>Servidores Web Soportados:</strong>
                    <ul>
                        <li><strong>Apache HTTP Server</strong> - Servidor web estable y ampliamente compatible</li>
                        <li><strong>OpenLiteSpeed</strong> - Alternativa de alto rendimiento con interfaz web</li>
                        <li><strong>NGINX</strong> - Servidor web de alto rendimiento para cargas elevadas</li>
                    </ul>
                </li>
                <li><strong>Ventajas:</strong> Control total, personalización completa, mejor rendimiento</li>
                <li><strong>Requisitos técnicos:</strong> Conocimientos de administración de sistemas Linux</li>
            </ul>
        </div>
        
        <div class="deployment-option">
            <h4>6.1.2 Hosting Compartido con cPanel y LiteSpeed</h4>
            <p>Opción ideal para usuarios individuales o pequeñas empresas que prefieren una solución gestionada.</p>
            <ul>
                <li><strong>Proveedor:</strong> Hosting compartido propiedad del estudiante</li>
                <li><strong>Panel de Control:</strong> cPanel para gestión simplificada</li>
                <li><strong>Servidor Web:</strong> LiteSpeed (optimizado para PHP)</li>
                <li><strong>Ventajas:</strong> Configuración simplificada, mantenimiento gestionado, costo reducido</li>
                <li><strong>Limitaciones:</strong> Menos control sobre configuraciones del servidor</li>
            </ul>
        </div>
    </div>
    
    <div id="requisitos-sistema">
        <h3>6.2 Requisitos del Sistema</h3>
        <div class="requirements-table">
            <table>
                <thead>
                    <tr>
                        <th>Componente</th>
                        <th>Mínimo</th>
                        <th>Recomendado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PHP</td>
                        <td>8.0</td>
                        <td>8.2 o superior</td>
                    </tr>
                    <tr>
                        <td>MySQL/MariaDB</td>
                        <td>8.0 / 10.5</td>
                        <td>8.0+ / 10.11+</td>
                    </tr>
                    <tr>
                        <td>RAM</td>
                        <td>512 MB</td>
                        <td>2 GB o más</td>
                    </tr>
                    <tr>
                        <td>Espacio en Disco</td>
                        <td>1 GB</td>
                        <td>5 GB o más</td>
                    </tr>
                    <tr>
                        <td>Extensiones PHP</td>
                        <td colspan="2">mysqli, pdo, json, curl, mbstring, openssl</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="despliegue-almalinux">
        <h3>6.3 Despliegue en AlmaLinux 9</h3>
        
        <h4>6.3.1 Instalación con Apache</h4>
        <div class="code-block">
            <pre><code># Actualizar el sistema
sudo dnf update -y

# Instalar Apache, PHP y MySQL
sudo dnf install httpd php php-mysqli php-json php-curl php-mbstring php-openssl mariadb-server -y

# Habilitar servicios
sudo systemctl enable --now httpd mariadb

# Configurar firewall
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload</code></pre>
        </div>
        
        <h4>6.3.2 Alternativa con OpenLiteSpeed</h4>
        <div class="code-block">
            <pre><code># Agregar repositorio OpenLiteSpeed
sudo dnf install epel-release -y
sudo rpm -ivh http://rpms.litespeedtech.com/centos/litespeed-repo-1.3-1.el9.noarch.rpm

# Instalar OpenLiteSpeed y PHP
sudo dnf install openlitespeed lsphp82 lsphp82-mysql lsphp82-json lsphp82-curl -y

# Iniciar OpenLiteSpeed
sudo systemctl enable --now lsws

# Configurar admin de OpenLiteSpeed
sudo /usr/local/lsws/admin/misc/admpass.sh</code></pre>
        </div>
        
        <h4>6.3.3 Alternativa con NGINX</h4>
        <div class="code-block">
            <pre><code># Instalar NGINX y PHP-FPM
sudo dnf install nginx php-fpm php-mysqli php-json php-curl php-mbstring -y

# Configurar PHP-FPM
sudo systemctl enable --now php-fpm nginx

# Configurar NGINX para PHP
sudo vim /etc/nginx/conf.d/traballa.conf</code></pre>
        </div>
    </div>
    
    <div id="despliegue-cpanel">
        <h3>6.4 Despliegue en Hosting Compartido cPanel</h3>
        <p>Para el despliegue en hosting compartido con cPanel, sigue estos pasos simplificados:</p>
        
        <ol>
            <li><strong>Acceso al cPanel:</strong> Ingresa a tu panel de control cPanel</li>
            <li><strong>Gestor de Archivos:</strong> Navega al directorio public_html</li>
            <li><strong>Subir Archivos:</strong> Sube el código de Traballa o clona desde Git</li>
            <li><strong>Base de Datos:</strong> Crea una base de datos MySQL desde cPanel</li>
            <li><strong>Configuración:</strong> Ajusta el archivo config.php con los datos de conexión</li>
            <li><strong>Permisos:</strong> Verifica que los directorios tengan permisos adecuados</li>
        </ol>
        
        <div class="note">
            <p><strong>Nota:</strong> En hosting compartido, LiteSpeed está preconfigurado y optimizado automáticamente para PHP, proporcionando mejor rendimiento que Apache tradicional.</p>
        </div>
    </div>
    
    <div id="configuracion-ssl">
        <h3>6.5 Configuración SSL/HTTPS</h3>
        <p>Se recomienda encarecidamente habilitar HTTPS para proteger las comunicaciones:</p>
        
        <h4>6.5.1 SSL en Servidor Dedicado</h4>
        <div class="code-block">
            <pre><code># Instalar Certbot para Let's Encrypt
sudo dnf install certbot python3-certbot-apache -y

# Obtener certificado SSL
sudo certbot --apache -d traballa.me -d www.traballa.me

# Configurar renovación automática
sudo crontab -e
# Agregar: 0 2 * * * /usr/bin/certbot renew --quiet</code></pre>
        </div>
        
        <h4>6.5.2 SSL en cPanel</h4>
        <p>En cPanel, el SSL generalmente se puede habilitar desde:</p>
        <ul>
            <li>Sección "Seguridad" → "SSL/TLS"</li>
            <li>Let's Encrypt (gratuito) o certificado comercial</li>
            <li>Habilitar "Forzar HTTPS" para redirigir automáticamente</li>
        </ul>
    </div>
    
    <div id="optimizacion-rendimiento">
        <h3>6.6 Optimización de Rendimiento</h3>
        
        <h4>6.6.1 Configuración PHP</h4>
        <div class="code-block">
            <pre><code># Ajustes recomendados en php.ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 20M
post_max_size = 20M
opcache.enable = 1
opcache.memory_consumption = 128</code></pre>
        </div>
        
        <h4>6.6.2 Configuración de Base de Datos</h4>
        <div class="code-block">
            <pre><code># Optimizaciones MySQL/MariaDB
innodb_buffer_pool_size = 128M
query_cache_size = 64M
query_cache_type = 1
max_connections = 100</code></pre>
        </div>
    </div>
    
    <div id="seguridad">
        <h3>6.7 Consideraciones de Seguridad</h3>
        <ul>
            <li><strong>Actualizaciones:</strong> Mantener PHP, servidor web y base de datos actualizados</li>
            <li><strong>Firewall:</strong> Configurar firewall para permitir solo puertos necesarios (80, 443, 22)</li>
            <li><strong>Acceso SSH:</strong> Usar claves SSH en lugar de contraseñas</li>
            <li><strong>Permisos:</strong> Configurar permisos de archivos apropiados (644 para archivos, 755 para directorios)</li>
            <li><strong>Backup:</strong> Implementar sistema de copias de seguridad automáticas</li>
            <li><strong>Monitoreo:</strong> Configurar monitoreo de logs y alertas de seguridad</li>
        </ul>
    </div>
    
    <div id="referencia-manual-instalacion">
        <h3>6.8 Referencia al Manual de Instalación</h3>
        <div class="manual-reference">
            <p>Para instrucciones detalladas paso a paso del proceso de instalación, consulte la sección <a href="#manual-instalacion"><strong>5.1 Manual de Instalación</strong></a>, que proporciona:</p>
            <ul>
                <li>Comandos específicos para cada sistema operativo</li>
                <li>Scripts de configuración automatizada</li>
                <li>Solución de problemas comunes</li>
                <li>Verificación de la instalación</li>
                <li>Procedimientos de actualización</li>
            </ul>
            <p>El manual de instalación complementa esta sección de despliegue con información técnica específica y ejemplos prácticos.</p>
        </div>
    </div>
    
    <div id="instancia-demo">
        <h3>6.9 Instancia de Demostración</h3>
        <div class="demo-info">
            <p>Una instancia completamente funcional de Traballa está disponible en:</p>
            <p class="demo-url"><strong>🌐 <a href="https://traballa.me" target="_blank">https://traballa.me</a></strong></p>
            <p>Esta instancia permite probar todas las funcionalidades del sistema antes de proceder con el despliegue propio.</p>
        </div>
    </div>
</div>

<div style="page-break-after: always;"></div>