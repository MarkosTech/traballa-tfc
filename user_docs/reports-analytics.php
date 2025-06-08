<?php
/**
 * User Documentation - Reports & Analytics
 */
?>

<div class="documentation-section" id="reports-analytics">
    <h2 class="section-title">
        <i class="fas fa-chart-line me-2"></i>Informes y análisis
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h5><i class="fas fa-chart-bar me-2"></i>Insights y análisis</h5>
                <p class="mb-0">Genera informes completos sobre horas de trabajo, patrones de productividad, progreso de proyectos y rendimiento del equipo con rangos de fechas personalizables y capacidades de exportación.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Acceder a informes</h5>
                </div>
                <div class="card-body">
                    <h6>Navegación y configuración</h6>
                    <ol>
                        <li><strong>Página de informes:</strong> Navega a informes desde la barra lateral</li>
                        <li><strong>Rango de fechas:</strong> Selecciona fechas de inicio y fin para el análisis</li>
                        <li><strong>Filtros:</strong> Elige proyectos u organizaciones específicos</li>
                        <li><strong>Generar:</strong> Haz clic en "Generar informe" para cargar datos</li>
                        <li><strong>Ver resultados:</strong> Explora gráficos, tablas y resúmenes</li>
                    </ol>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-calendar me-1"></i>El rango de fechas predeterminado es el mes actual, pero puedes seleccionar cualquier período personalizado.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtrado de informes</h5>
                </div>
                <div class="card-body">
                    <h6>Opciones de personalización</h6>
                    <ul>
                        <li><strong>Rangos de fechas:</strong>
                            <ul>
                                <li>Fechas de inicio y fin personalizadas</li>
                                <li>Selecciones rápidas (Este mes, Mes pasado)</li>
                                <li>Informes del año hasta la fecha</li>
                                <li>Análisis basado en trimestres</li>
                            </ul>
                        </li>
                        <li><strong>Filtrado de proyectos:</strong> Incluye solo proyectos específicos</li>
                        <li><strong>Enfoque en organización:</strong> Entornos multi-organización</li>
                        <li><strong>Selección de usuario:</strong> Informes individuales o de equipo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Componentes del informe</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-clock me-2 text-primary"></i>Resumen de tiempo</h6>
                            <ul>
                                <li><strong>Horas totales:</strong> Tiempo completo trabajado en el período</li>
                                <li><strong>Desglose diario:</strong> Horas trabajadas cada día</li>
                                <li><strong>Tendencias semanales:</strong> Comparación semana a semana</li>
                                <li><strong>Promedio diario:</strong> Horas de trabajo diarias típicas</li>
                                <li><strong>Días de trabajo:</strong> Número de días de trabajo activos</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-project-diagram me-2 text-success"></i>Distribución de proyectos</h6>
                            <ul>
                                <li><strong>Horas de proyecto:</strong> Tiempo dedicado por proyecto</li>
                                <li><strong>Porcentaje de proyecto:</strong> Asignación de tiempo relativa</li>
                                <li><strong>Eficiencia del proyecto:</strong> Productividad por proyecto</li>
                                <li><strong>Contribución del equipo:</strong> Horas individuales vs equipo</li>
                                <li><strong>Estado del proyecto:</strong> Proyectos activos vs completados</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-chart-line me-2 text-warning"></i>Gráficos visuales</h6>
                            <ul>
                                <li><strong>Gráfico de horas diarias:</strong> Gráfico de barras del trabajo diario</li>
                                <li><strong>Gráfico circular de proyectos:</strong> Distribución de tiempo por proyecto</li>
                                <li><strong>Líneas de tendencia:</strong> Análisis de patrones de trabajo</li>
                                <li><strong>Métricas de productividad:</strong> Indicadores de eficiencia</li>
                                <li><strong>Gráficos interactivos:</strong> Pasa el cursor para detalles</li>
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
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Tabla detallada de horas de trabajo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información de la tabla</h6>
                            <ul>
                                <li><strong>Fecha:</strong> Fecha de la sesión de trabajo</li>
                                <li><strong>Proyecto:</strong> Nombre del proyecto asociado</li>
                                <li><strong>Entrada/Salida:</strong> Horas de inicio y fin</li>
                                <li><strong>Horas totales:</strong> Duración de la sesión de trabajo</li>
                                <li><strong>Notas:</strong> Descripciones de la sesión de trabajo</li>
                                <li><strong>Estado:</strong> Sesiones completadas o en progreso</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Características de la tabla</h6>
                            <ul>
                                <li><strong>Ordenación:</strong> Haz clic en los encabezados de columna para ordenar</li>
                                <li><strong>Paginación:</strong> Navega a través de grandes conjuntos de datos</li>
                                <li><strong>Búsqueda:</strong> Encuentra entradas específicas rápidamente</li>
                                <li><strong>Filtrado por estado:</strong> Mostrar solo entradas completadas</li>
                                <li><strong>Listo para exportar:</strong> Datos formateados para exportación</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-info-circle me-2"></i>Precisión de datos</h6>
                        <p class="mb-0">Los informes solo incluyen sesiones de trabajo completadas. Las sesiones en progreso (sin marcar salida) se excluyen de los cálculos de tiempo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Opciones de exportación</h5>
                </div>
                <div class="card-body">
                    <h6>Formatos disponibles</h6>
                    <ul>
                        <li><strong>Exportación CSV:</strong> 
                            <ul>
                                <li>Formato compatible con Excel</li>
                                <li>Todos los datos de horas de trabajo</li>
                                <li>Fácil de importar a otros sistemas</li>
                                <li>Perfecto para procesamiento de nóminas</li>
                            </ul>
                        </li>
                        <li><strong>Informes PDF:</strong>
                            <ul>
                                <li>Informes formateados profesionalmente</li>
                                <li>Gráficos y resúmenes</li>
                                <li>Listo para presentación a clientes</li>
                                <li>Formato amigable para imprimir</li>
                            </ul>
                        </li>
                    </ul>
                    
                    <p><small><i class="fas fa-file-export me-1"></i>La exportación incluye todos los datos filtrados basados en tus criterios seleccionados.</small></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Informes de equipo</h5>
                </div>
                <div class="card-body">
                    <h6>Características para gerentes y administradores</h6>
                    <ul>
                        <li><strong>Vista general del equipo:</strong>
                            <ul>
                                <li>Horas de todos los miembros del equipo</li>
                                <li>Métricas de productividad individual</li>
                                <li>Patrones de colaboración del equipo</li>
                                <li>Tasas de participación en proyectos</li>
                            </ul>
                        </li>
                        <li><strong>Informes de organización:</strong>
                            <ul>
                                <li>Análisis entre proyectos</li>
                                <li>Insights a nivel de departamento</li>
                                <li>Datos de asignación de recursos</li>
                                <li>Benchmarking de eficiencia</li>
                            </ul>
                        </li>
                    </ul>
                    
                    <p><small><i class="fas fa-shield-alt me-1"></i>El acceso depende de tu rol y permisos de organización.</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Análisis del panel</h5>
                </div>
                <div class="card-body">
                    <p>Los análisis rápidos también están disponibles en tu panel para insights inmediatos:</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-tachometer-alt me-2 text-primary"></i>Estadísticas rápidas</h6>
                            <ul>
                                <li>Horas trabajadas hoy</li>
                                <li>Total de esta semana</li>
                                <li>Progreso mensual</li>
                                <li>Estado de trabajo actual</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-chart-bar me-2 text-success"></i>Gráfico semanal</h6>
                            <ul>
                                <li>Patrón de trabajo de 7 días</li>
                                <li>Comparación de horas diarias</li>
                                <li>Tendencias visuales de productividad</li>
                                <li>Indicadores de consistencia del trabajo</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-history me-2 text-warning"></i>Actividad reciente</h6>
                            <ul>
                                <li>Últimas 5 sesiones de trabajo</li>
                                <li>Distribución de proyectos</li>
                                <li>Patrones de duración de sesiones</li>
                                <li>Análisis de frecuencia de trabajo</li>
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
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Usar informes eficazmente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-target me-2 text-primary"></i>Productividad personal</h6>
                            <ul>
                                <li><strong>Patrones diarios:</strong> Identifica tus horas más productivas</li>
                                <li><strong>Balance de proyectos:</strong> Asegura una asignación de tiempo apropiada</li>
                                <li><strong>Consistencia del trabajo:</strong> Mantén horarios de trabajo regulares</li>
                                <li><strong>Análisis de descansos:</strong> Optimiza patrones de descanso</li>
                                <li><strong>Seguimiento de objetivos:</strong> Monitorea el progreso hacia metas</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-handshake me-2 text-success"></i>Informes para clientes</h6>
                            <ul>
                                <li><strong>Horas facturables:</strong> Seguimiento preciso del tiempo para facturación</li>
                                <li><strong>Actualizaciones de proyecto:</strong> Mostrar progreso a clientes</li>
                                <li><strong>Justificación de recursos:</strong> Demostrar inversión de tiempo</li>
                                <li><strong>Transparencia:</strong> Construir confianza del cliente con informes detallados</li>
                                <li><strong>Soporte de facturas:</strong> Documentación para facturación</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-cogs me-2 text-warning"></i>Mejora de procesos</h6>
                            <ul>
                                <li><strong>Análisis de eficiencia:</strong> Encuentra mejoras en el flujo de trabajo</li>
                                <li><strong>Asignación de tiempo:</strong> Optimiza la distribución de recursos del proyecto</li>
                                <li><strong>Rendimiento del equipo:</strong> Identifica necesidades de entrenamiento</li>
                                <li><strong>Planificación de proyectos:</strong> Mejores estimaciones de tiempo para proyectos futuros</li>
                                <li><strong>Planificación de capacidad:</strong> Entiende las capacidades del equipo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h6><i class="fas fa-mobile-alt me-2"></i>Informes móviles</h6>
                <p class="mb-2">Los informes son totalmente responsivos y funcionan muy bien en dispositivos móviles. Accede a tus insights de productividad en cualquier lugar, en cualquier momento.</p>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Estadísticas rápidas:</strong> Resúmenes del panel en móvil
                    </div>
                    <div class="col-md-3">
                        <strong>Gráficos táctiles:</strong> Gráficos interactivos optimizados para táctil
                    </div>
                    <div class="col-md-3">
                        <strong>Exportar en móvil:</strong> Genera y comparte informes desde móvil
                    </div>
                    <div class="col-md-3">
                        <strong>Acceso sin conexión:</strong> Ve informes en caché sin internet
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
