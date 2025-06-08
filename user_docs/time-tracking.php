<?php
/**
 * Documentación de usuario - Seguimiento de tiempo
 */
?>

<div class="documentation-section" id="time-tracking">
    <h2 class="section-title">
        <i class="fas fa-clock me-2"></i>Seguimiento de tiempo
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary">
                <h5><i class="fas fa-stopwatch me-2"></i>Gestión eficiente del tiempo</h5>
                <p class="mb-0">Registra tus horas de trabajo con precisión usando cronómetros de un clic, gestión de descansos y registro detallado de sesiones de trabajo. Perfecto para facturación, nómina y análisis de productividad.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-play me-2"></i>Iniciar sesiones de trabajo</h5>
                </div>
                <div class="card-body">
                    <h6>Proceso de entrada</h6>
                    <ol>
                        <li><strong>Seleccionar proyecto:</strong> Elige el proyecto en el que trabajarás desde el menú desplegable</li>
                        <li><strong>Añadir notas (opcional):</strong> Incluye cualquier nota relevante sobre la sesión de trabajo</li>
                        <li><strong>Registrar entrada:</strong> Haz clic en el botón "Registrar entrada" para comenzar el seguimiento</li>
                        <li><strong>Mostrar cronómetro:</strong> Observa el cronómetro en funcionamiento en tu panel de control</li>
                    </ol>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-info-circle me-1"></i>Debes estar asignado a al menos un proyecto activo para registrar entrada. Contacta a tu gerente si no hay proyectos disponibles.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-stop me-2"></i>Finalizar sesiones de trabajo</h5>
                </div>
                <div class="card-body">
                    <h6>Proceso de salida</h6>
                    <ol>
                        <li><strong>Revisar tiempo:</strong> Verifica la duración actual de tu trabajo</li>
                        <li><strong>Añadir notas finales:</strong> Actualiza o añade notas sobre el trabajo completado</li>
                        <li><strong>Registrar salida:</strong> Haz clic en el botón "Registrar salida"</li>
                        <li><strong>Revisar resumen:</strong> Visualiza el resumen de la sesión de trabajo completada</li>
                    </ol>
                    
                    <div class="alert alert-warning alert-sm mt-3">
                        <small><i class="fas fa-clock me-1"></i>Siempre recuerda registrar la salida cuando termines de trabajar. Las sesiones incompletas pueden afectar tus informes de tiempo.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-coffee me-2"></i>Gestión de descansos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Tomar descansos</h6>
                            <ol>
                                <li><strong>Botón de descanso:</strong> Haz clic en el botón "Tomar descanso" mientras estás registrado</li>
                                <li><strong>Tipo de descanso:</strong> Selecciona entre:
                                    <ul>
                                        <li>Descanso para almorzar (duración más larga)</li>
                                        <li>Descanso corto (café, baño, etc.)</li>
                                        <li>Otro (especificar en notas)</li>
                                    </ul>
                                </li>
                                <li><strong>Añadir notas:</strong> Descripción opcional del descanso</li>
                                <li><strong>Iniciar descanso:</strong> Comenzar el cronómetro de descanso</li>
                            </ol>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Finalizar descansos</h6>
                            <ol>
                                <li><strong>Cronómetro de descanso:</strong> Monitorea la duración de tu descanso</li>
                                <li><strong>Volver al trabajo:</strong> Haz clic en "Finalizar descanso" cuando estés listo</li>
                                <li><strong>El cronómetro de trabajo continúa:</strong> Tu cronómetro principal de trabajo se reanuda</li>
                                <li><strong>Historial de descansos:</strong> Ve todos los descansos en tu sesión de trabajo</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="alert alert-success mt-3">
                        <h6><i class="fas fa-chart-line me-2"></i>Análisis de descansos</h6>
                        <p class="mb-0">Rastrea tus patrones de descanso para optimizar la productividad. Los datos de descansos se incluyen en los informes y ayudan a mantener el equilibrio trabajo-vida.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Gestión de horas de trabajo</h5>
                </div>
                <div class="card-body">
                    <p>Accede a la página de <strong>Horas de trabajo</strong> para gestión integral del tiempo:</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-list me-2 text-primary"></i>Ver entradas de tiempo</h6>
                            <ul>
                                <li>Sesiones de trabajo diarias</li>
                                <li>Desglose por proyecto</li>
                                <li>Total de horas trabajadas</li>
                                <li>Indicadores de estado</li>
                                <li>Filtrado por rango de fechas</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-edit me-2 text-warning"></i>Editar entradas de tiempo</h6>
                            <ul>
                                <li>Ajustar horarios de entrada/salida</li>
                                <li>Actualizar notas de trabajo</li>
                                <li>Corregir asignaciones de proyecto</li>
                                <li>Arreglar sesiones incompletas</li>
                                <li>Eliminar entradas erróneas</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-filter me-2 text-success"></i>Filtrado y búsqueda</h6>
                            <ul>
                                <li>Selección de rango de fechas</li>
                                <li>Filtrado específico por proyecto</li>
                                <li>Filtrado por organización</li>
                                <li>Vistas basadas en estado</li>
                                <li>Capacidades de exportación</li>
                            </ul>
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
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Consejos para seguimiento de tiempo</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Consistencia:</strong> Registra entrada y salida en horarios regulares</li>
                        <li><strong>Notas detalladas:</strong> Añade descripciones específicas del trabajo</li>
                        <li><strong>Seguimiento de descansos:</strong> Registra todos los descansos para informes precisos</li>
                        <li><strong>Selección de proyecto:</strong> Asegúrate de la asignación correcta del proyecto</li>
                        <li><strong>Revisión diaria:</strong> Verifica tus entradas de tiempo al final de cada día</li>
                        <li><strong>Planificación semanal:</strong> Usa los datos de tiempo para mejor planificación del trabajo</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Problemas comunes y soluciones</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="timeTrackingFAQ">
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Olvidé registrar la salida
                                </button>
                            </h6>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <small>Ve a la página de horas de trabajo, encuentra la sesión incompleta y usa el botón Editar para añadir la hora correcta de salida.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Proyecto incorrecto seleccionado
                                </button>
                            </h6>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <small>Edita la entrada de tiempo y cambia la asignación del proyecto. Si no tienes acceso al proyecto correcto, contacta a tu gerente.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Sin proyectos disponibles
                                </button>
                            </h6>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#timeTrackingFAQ">
                                <div class="accordion-body">
                                    <small>Necesitas estar asignado a un proyecto activo para registrar entrada. Contacta al gerente de tu organización o administrador para ser añadido a proyectos.</small>
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
            <div class="alert alert-info">
                <h6><i class="fas fa-mobile-alt me-2"></i>Seguimiento de tiempo móvil</h6>
                <p class="mb-0">Todas las funciones de seguimiento de tiempo funcionan perfectamente en dispositivos móviles. Añade Traballa a la pantalla de inicio de tu teléfono para acceso rápido de entrada/salida durante tu jornada laboral.</p>
            </div>
        </div>
    </div>
</div>
