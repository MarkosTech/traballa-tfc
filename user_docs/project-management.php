<?php
/**
 * Documentación de usuario - Gestión de proyectos
 */
?>

<div class="documentation-section" id="project-management">
    <h2 class="section-title">
        <i class="fas fa-project-diagram me-2"></i>Gestión de proyectos
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h5><i class="fas fa-folder-open me-2"></i>Organiza tu trabajo</h5>
                <p class="mb-0">Crea y gestiona proyectos para organizar el trabajo, asignar miembros del equipo, seguir el progreso y mantener una supervisión clara del proyecto con informes detallados y análisis.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Crear proyectos</h5>
                </div>
                <div class="card-body">
                    <h6>Proceso de configuración de proyecto</h6>
                    <ol>
                        <li><strong>Acceder a proyectos:</strong> Navega a la página de proyectos</li>
                        <li><strong>Crear nuevo:</strong> Haz clic en el botón "Añadir proyecto"</li>
                        <li><strong>Detalles del proyecto:</strong>
                            <ul>
                                <li>Nombre del proyecto (requerido)</li>
                                <li>Descripción (resumen detallado)</li>
                                <li>Asignación de organización</li>
                                <li>Fechas de inicio y fin</li>
                                <li>Estado del proyecto</li>
                            </ul>
                        </li>
                        <li><strong>Guardar proyecto:</strong> Envía el formulario para crear</li>
                    </ol>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-user-shield me-1"></i>Necesitas permisos de gerente o administrador para crear proyectos.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Asignación de equipo</h5>
                </div>
                <div class="card-body">
                    <h6>Añadir miembros del equipo</h6>
                    <ol>
                        <li><strong>Detalles del proyecto:</strong> Ve a la página del proyecto específico</li>
                        <li><strong>Gestionar miembros:</strong> Usa la sección de gestión de equipo</li>
                        <li><strong>Añadir miembros:</strong> Selecciona usuarios de tu organización</li>
                        <li><strong>Asignar roles:</strong>
                            <ul>
                                <li>Gerente de proyecto (control total)</li>
                                <li>Miembro del equipo (colaborador)</li>
                                <li>Visualizador (acceso de solo lectura)</li>
                            </ul>
                        </li>
                        <li><strong>Guardar cambios:</strong> Confirma las asignaciones de miembros</li>
                    </ol>
                    
                    <div class="alert alert-warning alert-sm mt-3">
                        <small><i class="fas fa-clock me-1"></i>Solo los miembros del equipo asignados pueden registrar tiempo en el proyecto.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configuración y estado del proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-traffic-light me-2 text-success"></i>Estado del proyecto</h6>
                            <ul>
                                <li><strong>Activo:</strong> Proyectos de trabajo actuales</li>
                                <li><strong>En espera:</strong> Pausados temporalmente</li>
                                <li><strong>Completado:</strong> Proyectos terminados</li>
                                <li><strong>Cancelado:</strong> Proyectos discontinuados</li>
                            </ul>
                            <p><small>Solo los proyectos activos aparecen en los menús desplegables de seguimiento de tiempo.</small></p>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-calendar me-2 text-primary"></i>Gestión de cronograma</h6>
                            <ul>
                                <li><strong>Fecha de inicio:</strong> Comienzo del proyecto</li>
                                <li><strong>Fecha de fin:</strong> Finalización planificada</li>
                                <li><strong>Seguimiento de plazos:</strong> Indicadores visuales</li>
                                <li><strong>Monitoreo de progreso:</strong> Tiempo vs. planificado</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-building me-2 text-warning"></i>Contexto de organización</h6>
                            <ul>
                                <li><strong>Asignación de organización:</strong> Propiedad del proyecto</li>
                                <li><strong>Soporte multi-organización:</strong> Espacios de proyecto separados</li>
                                <li><strong>Control de acceso:</strong> Permisos basados en organización</li>
                                <li><strong>Informes:</strong> Análisis específicos de organización</li>
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
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Vista de detalles del proyecto</h5>
                </div>
                <div class="card-body">
                    <p>La página de detalles del proyecto proporciona supervisión integral del proyecto:</p>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <h6><i class="fas fa-chart-pie me-2 text-primary"></i>Resumen</h6>
                            <ul>
                                <li>Resumen del proyecto</li>
                                <li>Estadísticas clave</li>
                                <li>Indicadores de progreso</li>
                                <li>Vista general del estado</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-3">
                            <h6><i class="fas fa-users me-2 text-success"></i>Información del equipo</h6>
                            <ul>
                                <li>Lista de miembros del equipo</li>
                                <li>Asignaciones de roles</li>
                                <li>Información de contacto</li>
                                <li>Estado de actividad</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-3">
                            <h6><i class="fas fa-clock me-2 text-warning"></i>Análisis de tiempo</h6>
                            <ul>
                                <li>Total de horas trabajadas</li>
                                <li>Tiempo por miembro del equipo</li>
                                <li>Tendencias diarias/semanales</li>
                                <li>Métricas de productividad</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-3">
                            <h6><i class="fas fa-tasks me-2 text-info"></i>Acciones rápidas</h6>
                            <ul>
                                <li>Acceder al tablero Kanban</li>
                                <li>Ver informes del proyecto</li>
                                <li>Editar configuración del proyecto</li>
                                <li>Gestionar miembros del equipo</li>
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
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar proyectos</h5>
                </div>
                <div class="card-body">
                    <h6>Lo que puedes editar</h6>
                    <ul>
                        <li><strong>Información básica:</strong> Nombre, descripción, fechas</li>
                        <li><strong>Cambios de estado:</strong> Actualizar estado del ciclo de vida del proyecto</li>
                        <li><strong>Gestión de equipo:</strong> Añadir/eliminar miembros del equipo</li>
                        <li><strong>Organización:</strong> Transferir entre organizaciones (administrador)</li>
                        <li><strong>Configuración:</strong> Configuraciones específicas del proyecto</li>
                    </ul>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-shield-alt me-1"></i>Los permisos de edición dependen de tu rol en el proyecto y organización.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trash me-2"></i>Archivo de proyecto</h5>
                </div>
                <div class="card-body">
                    <h6>Gestión de fin de vida</h6>
                    <ul>
                        <li><strong>Finalización del proyecto:</strong> Marcar como completado cuando termine</li>
                        <li><strong>Retención de datos:</strong> Los datos históricos se preservan</li>
                        <li><strong>Acceso a archivo:</strong> Los proyectos completados siguen siendo visibles</li>
                        <li><strong>Entradas de tiempo:</strong> Las horas de trabajo pasadas se mantienen</li>
                        <li><strong>Informes:</strong> Los informes históricos siguen disponibles</li>
                    </ul>
                    
                    <div class="alert alert-warning alert-sm mt-3">
                        <small><i class="fas fa-archive me-1"></i>Los proyectos completados no se pueden usar para nuevo seguimiento de tiempo.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Mejores prácticas de gestión de proyectos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle me-2 text-success"></i>Mejores prácticas de configuración</h6>
                            <ul>
                                <li><strong>Nombres claros:</strong> Usa nombres de proyecto descriptivos y únicos</li>
                                <li><strong>Descripciones detalladas:</strong> Incluye alcance del proyecto y objetivos</li>
                                <li><strong>Cronogramas realistas:</strong> Establece fechas de inicio y fin alcanzables</li>
                                <li><strong>Tamaño de equipo correcto:</strong> Incluye todos los miembros del equipo necesarios</li>
                                <li><strong>Claridad de roles:</strong> Asigna roles apropiados a los miembros del equipo</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-line me-2 text-primary"></i>Gestión continua</h6>
                            <ul>
                                <li><strong>Revisiones regulares:</strong> Verifica el progreso del proyecto semanalmente</li>
                                <li><strong>Actualizaciones de estado:</strong> Mantén el estado del proyecto actualizado</li>
                                <li><strong>Comunicación del equipo:</strong> Usa los detalles del proyecto para coordinación del equipo</li>
                                <li><strong>Monitoreo de tiempo:</strong> Revisa los patrones de seguimiento de tiempo</li>
                                <li><strong>Gestión de alcance:</strong> Actualiza el alcance del proyecto según sea necesario</li>
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
                <h6><i class="fas fa-link me-2"></i>Integración con otras funciones</h6>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Seguimiento de tiempo:</strong> Todas las horas de trabajo están vinculadas a proyectos
                    </div>
                    <div class="col-md-3">
                        <strong>Tableros Kanban:</strong> Cada proyecto puede tener múltiples tableros de tareas
                    </div>
                    <div class="col-md-3">
                        <strong>Informes:</strong> Análisis e insights específicos del proyecto
                    </div>
                    <div class="col-md-3">
                        <strong>Calendario:</strong> Integración de eventos del proyecto y fechas límite
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
