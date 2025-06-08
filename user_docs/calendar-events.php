<?php
/**
 * User Documentation - Calendar & Events
 */
?>

<div class="documentation-section" id="calendar-events">
    <h2 class="section-title">
        <i class="fas fa-calendar-alt me-2"></i>Calendario y eventos
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-calendar-check me-2"></i>Programar y organizar</h5>
                <p class="mb-0">Gestiona eventos, fechas límite y reuniones con un sistema de calendario integrado que se conecta con tus proyectos y seguimiento de trabajo para una gestión integral del tiempo.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Vista del calendario</h5>
                </div>
                <div class="card-body">
                    <h6>Navegación e interfaz</h6>
                    <ul>
                        <li><strong>Vista mensual:</strong> Ve el diseño del mes completo con eventos</li>
                        <li><strong>Indicador de hoy:</strong> La fecha actual está destacada</li>
                        <li><strong>Visualización de eventos:</strong> Eventos codificados por colores se muestran en fechas apropiadas</li>
                        <li><strong>Controles de navegación:</strong> Muévete entre meses fácilmente</li>
                        <li><strong>Acciones rápidas:</strong> Haz clic en fechas para crear eventos</li>
                    </ul>
                    
                    <div class="alert alert-success alert-sm mt-3">
                        <small><i class="fas fa-mobile-alt me-1"></i>El calendario es totalmente responsivo y funciona muy bien en dispositivos móviles.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Crear eventos</h5>
                </div>
                <div class="card-body">
                    <h6>Proceso de creación de eventos</h6>
                    <ol>
                        <li><strong>Hacer clic en fecha:</strong> Haz clic en cualquier fecha del calendario</li>
                        <li><strong>Detalles del evento:</strong>
                            <ul>
                                <li>Título del evento (obligatorio)</li>
                                <li>Descripción (opcional)</li>
                                <li>Fecha y hora de inicio</li>
                                <li>Fecha y hora de finalización</li>
                                <li>Tipo de evento</li>
                            </ul>
                        </li>
                        <li><strong>Guardar evento:</strong> Envía para agregar al calendario</li>
                    </ol>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-clock me-1"></i>Los eventos tienen por defecto una duración de 1 hora comenzando en la fecha seleccionada.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tipos de eventos y categorías</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-user me-2 text-primary"></i>Eventos personales</h6>
                            <ul>
                                <li><strong>Reuniones personales</strong></li>
                                <li><strong>Citas</strong></li>
                                <li><strong>Fechas límite personales</strong></li>
                                <li><strong>Sesiones de capacitación</strong></li>
                                <li><strong>Tiempo libre</strong></li>
                            </ul>
                            <p><small>Solo visible para ti a menos que se comparta.</small></p>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-project-diagram me-2 text-success"></i>Eventos de proyecto</h6>
                            <ul>
                                <li><strong>Reuniones de proyecto</strong></li>
                                <li><strong>Fechas límite de hitos</strong></li>
                                <li><strong>Revisiones de sprint</strong></li>
                                <li><strong>Presentaciones a clientes</strong></li>
                                <li><strong>Lanzamientos de proyecto</strong></li>
                            </ul>
                            <p><small>Visible para todos los miembros del equipo del proyecto.</small></p>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-building me-2 text-warning"></i>Eventos de organización</h6>
                            <ul>
                                <li><strong>Reuniones de empresa</strong></li>
                                <li><strong>Eventos generales</strong></li>
                                <li><strong>Actividades de equipo</strong></li>
                                <li><strong>Fechas límite de organización</strong></li>
                                <li><strong>Programación de vacaciones</strong></li>
                            </ul>
                            <p><small>Visible para todos los miembros de la organización.</small></p>
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
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Gestionar eventos</h5>
                </div>
                <div class="card-body">
                    <h6>Acciones de eventos</h6>
                    <ul>
                        <li><strong>Ver detalles:</strong> Haz clic en eventos para ver información completa</li>
                        <li><strong>Editar eventos:</strong> Modifica fechas, horas y descripciones</li>
                        <li><strong>Eliminar eventos:</strong> Quita eventos que ya no necesitas</li>
                        <li><strong>Duplicar eventos:</strong> Copia eventos recurrentes rápidamente</li>
                        <li><strong>Compartir eventos:</strong> Invita a otros a eventos de proyecto/organización</li>
                    </ul>
                    
                    <div class="alert alert-warning alert-sm mt-3">
                        <small><i class="fas fa-lock me-1"></i>Solo puedes editar eventos que creaste o para los que tienes permisos apropiados.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Recordatorios de eventos</h5>
                </div>
                <div class="card-body">
                    <h6>Mantente al día</h6>
                    <ul>
                        <li><strong>Vista previa del panel:</strong> Los próximos eventos se muestran en el widget del panel</li>
                        <li><strong>Indicadores visuales:</strong> Eventos codificados por colores según el tipo</li>
                        <li><strong>Eventos de hoy:</strong> Destacado claro de eventos del día actual</li>
                        <li><strong>Seguimiento de fechas límite:</strong> Fechas límite de proyectos mostradas prominentemente</li>
                        <li><strong>Acceso rápido:</strong> Salta al calendario desde el panel</li>
                    </ul>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-calendar-day me-1"></i>Los próximos 7 días de eventos aparecen en tu panel para referencia rápida.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-link me-2"></i>Integración del calendario</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-project-diagram me-2 text-primary"></i>Integración de proyectos</h6>
                            <ul>
                                <li><strong>Fechas límite de proyectos:</strong> Muestra automáticamente las fechas de finalización de proyectos</li>
                                <li><strong>Seguimiento de hitos:</strong> Hitos importantes del proyecto</li>
                                <li><strong>Eventos del equipo:</strong> Reuniones y eventos específicos del proyecto</li>
                                <li><strong>Planificación de sprints:</strong> Eventos del ciclo de desarrollo</li>
                                <li><strong>Reuniones con clientes:</strong> Eventos de partes interesadas externas</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6><i class="fas fa-clock me-2 text-success"></i>Horario de trabajo</h6>
                            <ul>
                                <li><strong>Horas de trabajo:</strong> Representación visual del tiempo registrado</li>
                                <li><strong>Planificación de horarios:</strong> Planifica sesiones de trabajo futuras</li>
                                <li><strong>Bloques de tiempo:</strong> Tiempo dedicado de enfoque</li>
                                <li><strong>Programación de reuniones:</strong> Coordina la disponibilidad del equipo</li>
                                <li><strong>Planificación de descansos:</strong> Programa descansos regulares</li>
                            </ul>
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
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Mejores prácticas del calendario</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-calendar-plus me-2 text-primary"></i>Planificación de eventos</h6>
                            <ul>
                                <li><strong>Títulos descriptivos:</strong> Usa nombres de eventos claros y específicos</li>
                                <li><strong>Descripciones detalladas:</strong> Incluye agenda, ubicación o enlaces</li>
                                <li><strong>Duración apropiada:</strong> Establece bloques de tiempo realistas</li>
                                <li><strong>Planificación anticipada:</strong> Agrega eventos con mucha antelación</li>
                                <li><strong>Revisión regular:</strong> Revisa los próximos eventos semanalmente</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-users me-2 text-success"></i>Coordinación del equipo</h6>
                            <ul>
                                <li><strong>Eventos compartidos:</strong> Usa eventos de proyecto/organización para visibilidad del equipo</li>
                                <li><strong>Planificación de reuniones:</strong> Coordina horarios del equipo</li>
                                <li><strong>Gestión de fechas límite:</strong> Comparte fechas límite importantes</li>
                                <li><strong>Conciencia de zona horaria:</strong> Considera las zonas horarias del equipo</li>
                                <li><strong>Sincronización regular:</strong> Revisiones semanales del calendario del equipo</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-chart-line me-2 text-warning"></i>Consejos de productividad</h6>
                            <ul>
                                <li><strong>Bloques de tiempo:</strong> Programa tiempo de trabajo enfocado</li>
                                <li><strong>Tiempo de reserva:</strong> Deja espacios entre reuniones</li>
                                <li><strong>Eventos prioritarios:</strong> Marca eventos críticos claramente</li>
                                <li><strong>Revisiones regulares:</strong> Planificación semanal del calendario</li>
                                <li><strong>Equilibrio trabajo-vida:</strong> Incluye tiempo personal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary">
                <h6><i class="fas fa-calendar-week me-2"></i>Integración del calendario en el panel</h6>
                <p class="mb-2">Tu panel muestra los próximos eventos de los próximos 7 días, proporcionando visibilidad rápida de tu horario sin navegar al calendario completo.</p>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Vista rápida:</strong> Ve eventos de un vistazo
                    </div>
                    <div class="col-md-3">
                        <strong>Detalles del evento:</strong> Pasa el cursor para más información
                    </div>
                    <div class="col-md-3">
                        <strong>Acceso directo:</strong> Haz clic para ver el calendario completo
                    </div>
                    <div class="col-md-3">
                        <strong>Gestión del tiempo:</strong> Planifica tu trabajo alrededor de eventos
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
