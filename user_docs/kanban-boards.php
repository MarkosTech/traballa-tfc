<?php
/**
 * User Documentation - Kanban Boards
 */
?>

<div class="documentation-section" id="kanban-boards">
    <h2 class="section-title">
        <i class="fas fa-columns me-2"></i>Tableros kanban
    </h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h5><i class="fas fa-tasks me-2"></i>Gestión visual de tareas</h5>
                <p class="mb-0">Organiza el trabajo con tableros kanban interactivos que incluyen funcionalidad de arrastrar y soltar, colaboración en tiempo real, flujos de trabajo personalizables y gestión de tareas optimizada para móviles.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Acceder a los tableros kanban</h5>
                </div>
                <div class="card-body">
                    <h6>Cómo llegar a tu tablero</h6>
                    <ol>
                        <li><strong>Seleccionar proyecto:</strong> Elige un proyecto desde la página kanban</li>
                        <li><strong>O acceso directo:</strong> Ve a través de la página de detalles del proyecto</li>
                        <li><strong>Vista del tablero:</strong> Ve todas las columnas y tareas</li>
                        <li><strong>Navegación por pestañas:</strong> Cambia entre múltiples pestañas del tablero</li>
                    </ol>
                    
                    <div class="alert alert-info alert-sm mt-3">
                        <small><i class="fas fa-lock me-1"></i>Solo puedes acceder a tableros kanban de proyectos en los que estés asignado.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-columns me-2"></i>Entender el diseño del tablero</h5>
                </div>
                <div class="card-body">
                    <h6>Componentes del tablero</h6>
                    <ul>
                        <li><strong>Pestañas:</strong> Múltiples vistas de flujo de trabajo por proyecto</li>
                        <li><strong>Columnas:</strong> Etapas del flujo de trabajo (Por hacer, En progreso, Terminado)</li>
                        <li><strong>Tareas:</strong> Elementos de trabajo individuales dentro de las columnas</li>
                        <li><strong>Sección completada:</strong> Área plegable de tareas completadas</li>
                    </ul>
                    
                    <div class="alert alert-success alert-sm mt-3">
                        <small><i class="fas fa-mobile-alt me-1"></i>El diseño totalmente responsivo funciona en todos los dispositivos incluyendo interfaces táctiles.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Crear y gestionar tareas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-plus-circle me-2 text-success"></i>Agregar tareas</h6>
                            <ol>
                                <li><strong>Botón agregar tarea:</strong> Haz clic en "Agregar tarea" en cualquier columna</li>
                                <li><strong>Detalles de la tarea:</strong>
                                    <ul>
                                        <li>Título de la tarea (obligatorio)</li>
                                        <li>Descripción (opcional)</li>
                                        <li>Miembro del equipo asignado</li>
                                        <li>Fecha de vencimiento</li>
                                        <li>Estado inicial</li>
                                    </ul>
                                </li>
                                <li><strong>Guardar tarea:</strong> Envía para crear en la columna seleccionada</li>
                            </ol>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-edit me-2 text-primary"></i>Editar tareas</h6>
                            <ol>
                                <li><strong>Menú de tarea:</strong> Haz clic en el menú de tres puntos en cualquier tarea</li>
                                <li><strong>Opción editar:</strong> Selecciona "Editar tarea"</li>
                                <li><strong>Actualizar detalles:</strong> Modifica cualquier información de la tarea</li>
                                <li><strong>Guardar cambios:</strong> Aplica las actualizaciones a la tarea</li>
                            </ol>
                            
                            <p><small>Todos los miembros del equipo pueden editar tareas, pero las asignaciones pueden requerir permisos apropiados.</small></p>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-arrows-alt me-2 text-warning"></i>Estado de la tarea</h6>
                            <p>Tres opciones de estado para cada tarea:</p>
                            <ul>
                                <li><strong>Activa:</strong> <i class="fas fa-play text-success"></i> Actualmente en progreso</li>
                                <li><strong>Pendiente:</strong> <i class="fas fa-pause text-warning"></i> En espera o pausada</li>
                                <li><strong>Completada:</strong> <i class="fas fa-check text-primary"></i> Trabajo terminado</li>
                            </ul>
                            
                            <p><small>Haz clic en los íconos de estado para cambiar rápidamente el estado de la tarea.</small></p>
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
                    <h5 class="mb-0"><i class="fas fa-hand-paper me-2"></i>Funcionalidad de arrastrar y soltar</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-mouse me-2 text-primary"></i>Interacción en escritorio</h6>
                            <ul>
                                <li><strong>Arrastrar tareas:</strong> Haz clic y arrastra tareas entre columnas</li>
                                <li><strong>Reordenar tareas:</strong> Arrastra dentro de las columnas para cambiar prioridad</li>
                                <li><strong>Reordenar columnas:</strong> Arrastra encabezados de columnas para reorganizar el flujo de trabajo</li>
                                <li><strong>Guardado automático:</strong> Los cambios se guardan automáticamente</li>
                                <li><strong>Actualizaciones en tiempo real:</strong> Otros usuarios ven los cambios inmediatamente</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6><i class="fas fa-mobile-alt me-2 text-success"></i>Móvil y táctil</h6>
                            <ul>
                                <li><strong>Soporte táctil:</strong> Arrastrar y soltar completo en dispositivos táctiles</li>
                                <li><strong>Interfaz optimizada:</strong> Objetivos táctiles amigables para móviles</li>
                                <li><strong>Retroalimentación háptica:</strong> Retroalimentación visual durante las interacciones</li>
                                <li><strong>Diseño responsivo:</strong> Se adapta automáticamente al tamaño de pantalla</li>
                                <li><strong>Controles por gestos:</strong> Gestos táctiles intuitivos</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-sync me-2"></i>Colaboración en tiempo real</h6>
                        <p class="mb-0">Los cambios realizados por los miembros del equipo se sincronizan cada 15 segundos. Verás las actualizaciones automáticamente sin refrescar la página.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Gestionar pestañas</h5>
                </div>
                <div class="card-body">
                    <h6>Múltiples vistas de flujo de trabajo</h6>
                    <ul>
                        <li><strong>Pestaña predeterminada:</strong> Cada proyecto comienza con un tablero principal</li>
                        <li><strong>Agregar pestañas:</strong> Crea tableros adicionales para diferentes flujos de trabajo</li>
                        <li><strong>Nombres de pestañas:</strong> Personaliza los nombres de las pestañas para mayor claridad</li>
                        <li><strong>Cambiar pestañas:</strong> Navega entre diferentes vistas del tablero</li>
                        <li><strong>Eliminar pestañas:</strong> Quita pestañas innecesarias (excepto la predeterminada)</li>
                    </ul>
                    
                    <p><small><i class="fas fa-user-shield me-1"></i>Solo los gerentes de proyecto pueden crear y eliminar pestañas.</small></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-columns me-2"></i>Gestión de columnas</h5>
                </div>
                <div class="card-body">
                    <h6>Personalización del flujo de trabajo</h6>
                    <ul>
                        <li><strong>Agregar columnas:</strong> Crea nuevas etapas del flujo de trabajo</li>
                        <li><strong>Nombres de columnas:</strong> Personaliza los nombres de las etapas</li>
                        <li><strong>Reordenar columnas:</strong> Arrastra encabezados para reorganizar</li>
                        <li><strong>Editar columnas:</strong> Renombra columnas existentes</li>
                        <li><strong>Eliminar columnas:</strong> Quita etapas de flujo de trabajo no utilizadas</li>
                    </ul>
                    
                    <p><small><i class="fas fa-exclamation-triangle me-1"></i>Eliminar columnas moverá todas las tareas a la primera columna.</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-check-double me-2"></i>Gestión de tareas completadas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Flujo de trabajo de finalización</h6>
                            <ul>
                                <li><strong>Marcar como completada:</strong> Usa botones de estado o arrastra para completar</li>
                                <li><strong>Agrupación automática:</strong> Las tareas completadas se mueven a una sección separada</li>
                                <li><strong>Vista plegable:</strong> Oculta/muestra tareas completadas para reducir el desorden</li>
                                <li><strong>Conteo de tareas:</strong> Ve el número de tareas completadas por columna</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Características de tareas completadas</h6>
                            <ul>
                                <li><strong>Ver historial:</strong> Expande para ver todo el trabajo completado</li>
                                <li><strong>Reactivar tareas:</strong> Cambia el estado de vuelta a activo si es necesario</li>
                                <li><strong>Editar completadas:</strong> Modifica detalles de tareas finalizadas</li>
                                <li><strong>Seguimiento de progreso:</strong> Indicación visual de finalización del trabajo</li>
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
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Mejores prácticas de kanban</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-clipboard-list me-2 text-primary"></i>Creación de tareas</h6>
                            <ul>
                                <li><strong>Títulos claros:</strong> Usa nombres de tareas descriptivos y accionables</li>
                                <li><strong>Tamaño adecuado:</strong> Divide tareas grandes en otras más pequeñas</li>
                                <li><strong>Buenas descripciones:</strong> Agrega contexto y requisitos</li>
                                <li><strong>Asignar propiedad:</strong> Siempre asigna tareas a miembros del equipo</li>
                                <li><strong>Establece fechas límite:</strong> Usa fechas límite para trabajo sensible al tiempo</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-project-diagram me-2 text-success"></i>Diseño del flujo de trabajo</h6>
                            <ul>
                                <li><strong>Columnas simples:</strong> Comienza con etapas básicas del flujo de trabajo</li>
                                <li><strong>Nombres claros:</strong> Usa términos que tu equipo entienda</li>
                                <li><strong>Flujo lógico:</strong> Organiza columnas en orden de trabajo</li>
                                <li><strong>Limitar WIP:</strong> Evita demasiadas tareas activas</li>
                                <li><strong>Revisión regular:</strong> Ajusta el flujo de trabajo según sea necesario</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4">
                            <h6><i class="fas fa-users me-2 text-warning"></i>Colaboración en equipo</h6>
                            <ul>
                                <li><strong>Actualizaciones diarias:</strong> Revisa y actualiza el estado de las tareas regularmente</li>
                                <li><strong>Comunicación clara:</strong> Usa las descripciones de tareas efectivamente</li>
                                <li><strong>Conciencia del estado:</strong> Mantén el estado de las tareas actualizado</li>
                                <li><strong>Coordinación del equipo:</strong> Discute el flujo de trabajo en reuniones de equipo</li>
                                <li><strong>Mejora continua:</strong> Refina el proceso con el tiempo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h6><i class="fas fa-rocket me-2"></i>Características avanzadas</h6>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Sincronización en tiempo real:</strong> Ve los cambios del equipo instantáneamente
                    </div>
                    <div class="col-md-3">
                        <strong>Optimizado para móviles:</strong> Funcionalidad completa en dispositivos móviles
                    </div>
                    <div class="col-md-3">
                        <strong>Atajos de teclado:</strong> Acciones rápidas para usuarios avanzados
                    </div>
                    <div class="col-md-3">
                        <strong>Retroalimentación visual:</strong> Animaciones e indicadores de estado
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
