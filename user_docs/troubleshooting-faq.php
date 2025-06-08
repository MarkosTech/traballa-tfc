<?php
/**
 * User Documentation - Troubleshooting & FAQ
 */
?>

<div class="documentation-section" id="troubleshooting-faq">
    <h2 class="section-title">
        <i class="fas fa-question-circle me-2"></i>Solución de problemas y preguntas frecuentes
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-life-ring me-2"></i>Obtén ayuda cuando la necesites</h5>
                <p class="mb-0">Encuentra soluciones a problemas comunes, obtén respuestas a preguntas frecuentes y aprende cómo sacar el máximo provecho de las funciones de Traballa.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Problemas de seguimiento de tiempo</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="timeTrackingFAQ">
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#time1">
                                    Olvidé registrar mi salida ayer. ¿Cómo puedo arreglarlo?
                                </button>
                            </h6>
                            <div id="time1" class="accordion-collapse collapse show" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <p><strong>Solución:</strong></p>
                                    <ol>
                                        <li>Ve a la página de <strong>Horas de trabajo</strong></li>
                                        <li>Encuentra la sesión incompleta (aparecerá como "En progreso")</li>
                                        <li>Haz clic en el botón <strong>Editar</strong> para esa entrada</li>
                                        <li>Agrega la hora correcta de salida</li>
                                        <li>Guarda los cambios</li>
                                    </ol>
                                    <p><small><i class="fas fa-info-circle me-1"></i>El sistema calculará automáticamente el total de horas trabajadas.</small></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#time2">
                                    No puedo registrar entrada - no aparecen proyectos
                                </button>
                            </h6>
                            <div id="time2" class="accordion-collapse collapse" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <p><strong>Posibles causas y soluciones:</strong></p>
                                    <ul>
                                        <li><strong>No asignado a proyectos:</strong> Contacta a tu gerente para ser agregado a proyectos activos</li>
                                        <li><strong>Organización incorrecta:</strong> Cambia a la organización correcta en la barra lateral</li>
                                        <li><strong>Sin proyectos activos:</strong> Todos los proyectos pueden estar completados o en pausa</li>
                                        <li><strong>Problema de permisos:</strong> Verifica que tienes permisos de seguimiento de tiempo</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#time3">
                                    Mis entradas de tiempo muestran horas incorrectas
                                </button>
                            </h6>
                            <div id="time3" class="accordion-collapse collapse" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <p><strong>Verifica estos elementos:</strong></p>
                                    <ul>
                                        <li><strong>Zona horaria:</strong> Asegúrate de que la zona horaria de tu navegador sea correcta</li>
                                        <li><strong>Precisión del reloj:</strong> Verifica que el reloj de tu dispositivo sea preciso</li>
                                        <li><strong>Tiempo de descanso:</strong> Los descansos se restan automáticamente del total de horas</li>
                                        <li><strong>Sesiones incompletas:</strong> Las sesiones no terminadas no cuentan en los totales</li>
                                    </ul>
                                    <p><strong>Para arreglar:</strong> Edita la entrada de tiempo con las horas correctas.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-columns me-2"></i>Problemas de tableros kanban</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="kanbanFAQ">
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kanban1">
                                    Arrastrar y soltar no funciona en mi dispositivo móvil
                                </button>
                            </h6>
                            <div id="kanban1" class="accordion-collapse collapse" data-bs-parent="#kanbanFAQ">
                                <div class="accordion-body">
                                    <p><strong>Soluciones móviles:</strong></p>
                                    <ul>
                                        <li><strong>Usa mantener presionado y tocar:</strong> Presiona y mantén presionado en las tareas antes de arrastrar</li>
                                        <li><strong>Prueba los botones de estado:</strong> Usa los íconos de estado (reproducir, pausar, verificar) en su lugar</li>
                                        <li><strong>Editar tareas:</strong> Usa el menú de tareas para cambiar columnas</li>
                                        <li><strong>Problemas del navegador:</strong> Prueba un navegador móvil diferente</li>
                                        <li><strong>Actualizar navegador:</strong> Asegúrate de usar la versión más reciente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kanban2">
                                    No puedo ver las actualizaciones de tareas de mi equipo en tiempo real
                                </button>
                            </h6>
                            <div id="kanban2" class="accordion-collapse collapse" data-bs-parent="#kanbanFAQ">
                                <div class="accordion-body">
                                    <p><strong>Pasos de solución de problemas:</strong></p>
                                    <ul>
                                        <li><strong>Verificar conexión:</strong> Asegúrate de tener una conexión a internet estable</li>
                                        <li><strong>Actualizar página:</strong> Recarga la página del navegador</li>
                                        <li><strong>Esperar sincronización:</strong> Las actualizaciones se sincronizan cada 15 segundos</li>
                                        <li><strong>Pestaña activa:</strong> Las actualizaciones solo se sincronizan en pestañas activas del navegador</li>
                                        <li><strong>Compatibilidad del navegador:</strong> Usa un navegador moderno con JavaScript habilitado</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kanban3">
                                    Eliminé accidentalmente una columna/tarea
                                </button>
                            </h6>
                            <div id="kanban3" class="accordion-collapse collapse" data-bs-parent="#kanbanFAQ">
                                <div class="accordion-body">
                                    <p><strong>Opciones de recuperación:</strong></p>
                                    <ul>
                                        <li><strong>Tareas eliminadas:</strong> Verifica la sección de tareas completadas del proyecto</li>
                                        <li><strong>Columnas eliminadas:</strong> Las tareas se mueven a la primera columna disponible</li>
                                        <li><strong>Contactar administrador:</strong> Los administradores del sistema pueden ser capaces de restaurar datos</li>
                                        <li><strong>Cambios recientes:</strong> Busca las tareas en otras columnas o pestañas</li>
                                    </ul>
                                    <p><small><i class="fas fa-exclamation-triangle me-1"></i>Prevención: Ten cuidado con las acciones de eliminación ya que suelen ser inmediatas.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Problemas de inicio de sesión y cuenta</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="loginFAQ">
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login1">
                                    Olvidé mi contraseña
                                </button>
                            </h6>
                            <div id="login1" class="accordion-collapse collapse" data-bs-parent="#loginFAQ">
                                <div class="accordion-body">
                                    <p><strong>Proceso de restablecimiento:</strong></p>
                                    <ol>
                                        <li>Ve a la página de inicio de sesión</li>
                                        <li>Haz clic en <strong>"¿Olvidaste tu contraseña?"</strong></li>
                                        <li>Ingresa tu dirección de correo electrónico</li>
                                        <li>Revisa tu correo para las instrucciones de restablecimiento</li>
                                        <li>Sigue el enlace en el correo electrónico</li>
                                        <li>Crea una nueva contraseña</li>
                                    </ol>
                                    <p><small><i class="fas fa-clock me-1"></i>Los enlaces de restablecimiento expiran después de 24 horas por seguridad.</small></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login2">
                                    Obtengo error de "Credenciales inválidas"
                                </button>
                            </h6>
                            <div id="login2" class="accordion-collapse collapse" data-bs-parent="#loginFAQ">
                                <div class="accordion-body">
                                    <p><strong>Verifica estos elementos:</strong></p>
                                    <ul>
                                        <li><strong>Ortografía del correo:</strong> Verifica que tu dirección de correo sea correcta</li>
                                        <li><strong>Mayúsculas y minúsculas:</strong> Las contraseñas distinguen entre mayúsculas y minúsculas</li>
                                        <li><strong>Bloq Mayús:</strong> Asegúrate de que Bloq Mayús esté desactivado</li>
                                        <li><strong>Copiar/pegar:</strong> Evita copiar contraseñas (pueden incluir espacios extra)</li>
                                        <li><strong>Estado de cuenta:</strong> Tu cuenta puede estar desactivada</li>
                                    </ul>
                                    <p>Si los problemas persisten, contacta a tu administrador.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login3">
                                    No puedo registrar una nueva cuenta
                                </button>
                            </h6>
                            <div id="login3" class="accordion-collapse collapse" data-bs-parent="#loginFAQ">
                                <div class="accordion-body">
                                    <p><strong>Problemas comunes de registro:</strong></p>
                                    <ul>
                                        <li><strong>Correo ya existe:</strong> Puede que ya exista una cuenta con ese correo</li>
                                        <li><strong>Requisitos de contraseña:</strong> debe tener al menos 6 caracteres</li>
                                        <li><strong>Consentimientos requeridos:</strong> Debes aceptar la política de privacidad y términos</li>
                                        <li><strong>Restricciones organizacionales:</strong> Algunas organizaciones requieren aprobación del administrador</li>
                                        <li><strong>Validación de formulario:</strong> Asegúrate de que todos los campos requeridos estén completos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Problemas de móvil y navegador</h5>
                </div>
                <div class="card-body">
                    <h6>Compatibilidad de navegadores</h6>
                    <ul>
                        <li><strong>Navegadores soportados:</strong>
                            <ul>
                                <li>Chrome (recomendado)</li>
                                <li>Firefox</li>
                                <li>Safari</li>
                                <li>Edge</li>
                            </ul>
                        </li>
                        <li><strong>JavaScript requerido:</strong> Debe estar habilitado</li>
                        <li><strong>Cookies:</strong> Debe permitir cookies de sesión</li>
                        <li><strong>Bloqueadores de ventanas emergentes:</strong> Pueden interferir con algunas funciones</li>
                    </ul>
                    
                    <h6>Problemas móviles</h6>
                    <ul>
                        <li><strong>Agregar a pantalla de inicio:</strong> Para experiencia tipo aplicación</li>
                        <li><strong>Nivel de zoom:</strong> Reinicia el zoom del navegador al 100%</li>
                        <li><strong>Rotación:</strong> Algunas funciones funcionan mejor en paisaje</li>
                        <li><strong>Capacidad de respuesta táctil:</strong> Prueba diferentes gestos táctiles</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Problemas de reportes y datos</h5>
                </div>
                <div class="card-body">
                    <h6>Problemas de reportes</h6>
                    <ul>
                        <li><strong>No se muestran datos:</strong>
                            <ul>
                                <li>Verifica la selección de rango de fechas</li>
                                <li>Verifica los filtros de proyectos</li>
                                <li>Asegúrate de que existan sesiones de trabajo completadas</li>
                            </ul>
                        </li>
                        <li><strong>Problemas de exportación:</strong>
                            <ul>
                                <li>Verifica la configuración de descargas del navegador</li>
                                <li>Deshabilita bloqueadores de ventanas emergentes</li>
                                <li>Prueba un navegador diferente</li>
                            </ul>
                        </li>
                        <li><strong>Gráfico no carga:</strong>
                            <ul>
                                <li>Habilita JavaScript</li>
                                <li>Actualiza la página</li>
                                <li>Verifica la conexión a internet</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question me-2"></i>Preguntas frecuentes</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Preguntas generales</h6>
                            <div class="accordion" id="generalFAQ">
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                            ¿Puedo trabajar en múltiples proyectos simultáneamente?
                                        </button>
                                    </h6>
                                    <div id="general1" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                                        <div class="accordion-body">
                                            <small>Solo puedes registrar entrada en un proyecto a la vez. Para cambiar proyectos, registra salida del proyecto actual y registra entrada en el nuevo. Usa notas de proyecto para rastrear cambios de tareas dentro de una sesión.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                            ¿Qué tan preciso es el seguimiento de tiempo?
                                        </button>
                                    </h6>
                                    <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                                        <div class="accordion-body">
                                            <small>El seguimiento de tiempo es preciso al segundo. El sistema usa tiempo del servidor para prevenir manipulación y maneja automáticamente conversiones de zona horaria. El tiempo de descanso se resta automáticamente de las sesiones de trabajo.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                            ¿Puedo usar Traballa sin conexión?
                                        </button>
                                    </h6>
                                    <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                                        <div class="accordion-body">
                                            <small>Funcionalidad offline limitada está disponible. Puedes ver datos almacenados en caché y continuar sesiones de trabajo existentes, pero nuevas acciones requieren conexión a internet. Los datos se sincronizan automáticamente cuando se restaura la conexión.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Privacidad y seguridad</h6>
                            <div class="accordion" id="privacyFAQ">
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy1">
                                            ¿Quién puede ver mis horas de trabajo?
                                        </button>
                                    </h6>
                                    <div id="privacy1" class="accordion-collapse collapse" data-bs-parent="#privacyFAQ">
                                        <div class="accordion-body">
                                            <small>Tus horas de trabajo son visibles para ti, gerentes de proyectos de proyectos en los que trabajas, y administradores organizacionales. Los miembros del equipo solo pueden ver datos agregados de proyectos, no entradas de tiempo individuales.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy2">
                                            ¿Están seguros mis datos?
                                        </button>
                                    </h6>
                                    <div id="privacy2" class="accordion-collapse collapse" data-bs-parent="#privacyFAQ">
                                        <div class="accordion-body">
                                            <small>Sí. Todos los datos están encriptados en tránsito y en reposo. Usamos prácticas de seguridad estándar de la industria, respaldos regulares, y cumplimos con los requisitos de GDPR. Tu contraseña está hash de manera segura y no puede ser vista por nadie.</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy3">
                                            ¿Puedo eliminar mi cuenta y datos?
                                        </button>
                                    </h6>
                                    <div id="privacy3" class="accordion-collapse collapse" data-bs-parent="#privacyFAQ">
                                        <div class="accordion-body">
                                            <small>Sí. Puedes exportar todos tus datos y luego solicitar eliminación de cuenta desde la página de configuración. Hay un período de gracia de 30 días para recuperación. Después de eso, tus datos personales se eliminan permanentemente.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-life-ring me-2"></i>Obtener ayuda adicional</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-envelope me-2 text-primary"></i>Contactar soporte</h6>
                            <ul>
                                <li><strong>Administrador del sistema:</strong> Contacta al administrador de tu organización</li>
                                <li><strong>Gerente de proyecto:</strong> Contacta a gerentes de proyecto para problemas específicos de proyectos</li>
                                <li><strong>Problemas técnicos:</strong> Reporta errores a través de tu administrador</li>
                                <li><strong>Solicitudes de funciones:</strong> Sugiere mejoras a través de canales de retroalimentación</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-book me-2 text-success"></i>Documentación</h6>
                            <ul>
                                <li><strong>Documentación de usuario:</strong> Esta guía completa</li>
                                <li><strong>Tutoriales en video:</strong> Guías visuales paso a paso</li>
                                <li><strong>Referencia rápida:</strong> Atajos de teclado y consejos</li>
                                <li><strong>Mejores prácticas:</strong> Recomendaciones de optimización</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-users me-2 text-warning"></i>Comunidad</h6>
                            <ul>
                                <li><strong>Ayuda del equipo:</strong> Pregunta a colegas que usan Traballa</li>
                                <li><strong>Entrenamiento organizacional:</strong> Solicita sesiones de entrenamiento en equipo</li>
                                <li><strong>Foros de usuarios:</strong> Tableros de discusión de la comunidad</li>
                                <li><strong>Compartir conocimiento:</strong> Wikis internos de la organización</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-info-circle me-2"></i>Antes de contactar soporte</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Prueba pasos básicos:</strong> Actualiza página, limpia caché del navegador
                            </div>
                            <div class="col-md-3">
                                <strong>Revisa esta guía:</strong> Revisa secciones relevantes de documentación
                            </div>
                            <div class="col-md-3">
                                <strong>Nota detalles:</strong> Navegador, dispositivo, mensajes de error específicos
                            </div>
                            <div class="col-md-3">
                                <strong>Prueba navegador diferente:</strong> Descarta problemas específicos del navegador
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h6><i class="fas fa-lightbulb me-2"></i>Lista de verificación rápida de solución de problemas</h6>
                <div class="row">
                    <div class="col-md-3">
                        <strong>✓ Actualizar la página</strong><br>
                        <small>Resuelve la mayoría de problemas temporales</small>
                    </div>
                    <div class="col-md-3">
                        <strong>✓ Verificar conexión a internet</strong><br>
                        <small>Asegurar conectividad estable</small>
                    </div>
                    <div class="col-md-3">
                        <strong>✓ Limpiar caché del navegador</strong><br>
                        <small>Elimina archivos obsoletos</small>
                    </div>
                    <div class="col-md-3">
                        <strong>✓ Probar navegador diferente</strong><br>
                        <small>Descarta problemas del navegador</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
