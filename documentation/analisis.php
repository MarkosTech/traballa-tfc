<div id="analisis" class="section">
    <h2>2. Análisis: Requerimientos del sistema</h2>
    <div class="section-description">
        <p>Esta sección detalla los requisitos funcionales y no funcionales del sistema, así como los casos de uso que definen su funcionamiento.</p>
    </div>
    
    <div id="requisitos-funcionales">
        <h3>2.1 Requisitos funcionales</h3>
        <p>Los requisitos funcionales principales del sistema incluyen:</p>
        <ul>
            <li><strong>Gestión de usuarios:</strong> Registro, inicio de sesión, recuperación de contraseña, perfiles de usuario.</li>
            <li><strong>Registro de tiempo:</strong> Creación, edición y eliminación de registros de tiempo, inicio y pausa de temporizadores, categorización por proyectos y tareas.</li>
            <li><strong>Gestión de proyectos:</strong> Creación y administración de proyectos, asignación de miembros, seguimiento de progreso.</li>
            <li><strong>Tablero Kanban:</strong> Visualización de tareas en columnas personalizables, arrastrar y soltar para cambiar estados, filtros y etiquetas.</li>
            <li><strong>Calendario:</strong> Vista de calendario con eventos de trabajo programados, integración con registros de tiempo.</li>
            <li><strong>Reportes:</strong> Generación de informes de tiempo por usuario, proyecto o cliente, exportación en diferentes formatos.</li>
            <li><strong>Notificaciones:</strong> Alertas sobre deadlines, asignaciones de tareas y actualizaciones de proyectos.</li>
        </ul>
    </div>
    
    <div id="requisitos-no-funcionales">
        <h3>2.2 Requisitos no funcionales</h3>
        <p>Los requisitos no funcionales incluyen:</p>
        <ul>
            <li><strong>Usabilidad:</strong> Interfaz intuitiva y fácil de usar, con tiempos de aprendizaje mínimos.</li>
            <li><strong>Rendimiento:</strong> Tiempos de respuesta rápidos, incluso con múltiples usuarios simultáneos.</li>
            <li><strong>Seguridad:</strong> Protección de datos de usuario, autenticación segura, cifrado de información sensible.</li>
            <li><strong>Escalabilidad:</strong> Capacidad para crecer con el aumento de usuarios y proyectos sin degradar el rendimiento.</li>
            <li><strong>Disponibilidad:</strong> Funcionamiento 24/7 con mínimo tiempo de inactividad para mantenimiento.</li>
            <li><strong>Accesibilidad:</strong> Cumplimiento de estándares WCAG 2.1 para garantizar acceso a usuarios con discapacidades.</li>
            <li><strong>Compatibilidad:</strong> Funcionamiento en diferentes navegadores y dispositivos (responsive design).</li>
        </ul>
    </div>
    
    <div id="casos-uso">
        <h3>2.3 Casos de uso</h3>
        <p>Los casos de uso del sistema han sido diseñados siguiendo las mejores prácticas de ingeniería de software, identificando actores, precondiciones, flujos principales y alternativos para cada funcionalidad.</p>
        
        <h4>2.3.1 Actores del sistema</h4>
        <p>Los actores identificados en el sistema son:</p>
        <ul>
            <li><strong>Usuario final:</strong> Profesional que utiliza el sistema para registrar tiempo y gestionar proyectos</li>
            <li><strong>Administrador de organización:</strong> Usuario con permisos para gestionar otros usuarios y configuraciones</li>
            <li><strong>Manager de proyecto:</strong> Usuario responsable de la supervisión y coordinación de proyectos específicos</li>
            <li><strong>Administrador del sistema:</strong> Responsable técnico del mantenimiento y configuración global</li>
        </ul>
        
        <h4>2.3.2 Casos de uso detallados</h4>
        
        <h5>CU-001: Registro e inicio de sesión de usuario</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-001</td></tr>
                <tr><th>Nombre</th><td>Registro e inicio de sesión de usuario</td></tr>
                <tr><th>Actores</th><td>Usuario final, Administrador</td></tr>
                <tr><th>Precondiciones</th><td>El sistema debe estar disponible y funcionando</td></tr>
                <tr><th>Descripción</th><td>El usuario se registra en el sistema o inicia sesión con credenciales existentes</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a la página de login</li>
            <li>El usuario introduce email y contraseña</li>
            <li>El sistema valida las credenciales</li>
            <li>El sistema redirige al dashboard principal</li>
            <li>Se registra la actividad de login</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Registro nuevo:</strong> Si el usuario no tiene cuenta, puede registrarse proporcionando nombre, email y contraseña</li>
            <li><strong>A2 - Credenciales incorrectas:</strong> El sistema muestra error y permite reintentar o recuperar contraseña</li>
            <li><strong>A3 - Recuperación de contraseña:</strong> El usuario puede solicitar un enlace de recuperación por email</li>
        </ul>
        
        <h5>CU-002: Registro de tiempo de trabajo</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-002</td></tr>
                <tr><th>Nombre</th><td>Registro de tiempo de trabajo</td></tr>
                <tr><th>Actores</th><td>Usuario final</td></tr>
                <tr><th>Precondiciones</th><td>Usuario autenticado, al menos un proyecto disponible</td></tr>
                <tr><th>Descripción</th><td>El usuario registra sus horas de trabajo en proyectos específicos</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario selecciona un proyecto</li>
            <li>El usuario hace clic en "Fichar entrada"</li>
            <li>El sistema registra la hora de inicio</li>
            <li>El usuario trabaja en el proyecto</li>
            <li>El usuario hace clic en "Fichar salida"</li>
            <li>El sistema calcula y guarda las horas trabajadas</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Pausas:</strong> El usuario puede pausar el trabajo y reanudar posteriormente</li>
            <li><strong>A2 - Edición manual:</strong> El usuario puede editar registros de tiempo existentes</li>
            <li><strong>A3 - Notas adicionales:</strong> El usuario puede agregar descripción del trabajo realizado</li>
        </ul>
        
        <h5>CU-003: Gestión de proyectos</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-003</td></tr>
                <tr><th>Nombre</th><td>Gestión de proyectos</td></tr>
                <tr><th>Actores</th><td>Usuario final, Manager de proyecto, Administrador</td></tr>
                <tr><th>Precondiciones</th><td>Usuario autenticado con permisos adecuados</td></tr>
                <tr><th>Descripción</th><td>Creación, edición y administración de proyectos en el sistema</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a la sección de proyectos</li>
            <li>El usuario hace clic en "Nuevo proyecto"</li>
            <li>El usuario completa información: nombre, descripción, fechas</li>
            <li>El usuario asigna miembros del equipo</li>
            <li>El sistema crea el proyecto y notifica a los miembros</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Edición:</strong> Modificación de datos de proyectos existentes</li>
            <li><strong>A2 - Archivo:</strong> Cambio de estado del proyecto a archivado</li>
            <li><strong>A3 - Permisos:</strong> Asignación de roles específicos dentro del proyecto</li>
        </ul>
        
        <h5>CU-004: Gestión del tablero Kanban</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-004</td></tr>
                <tr><th>Nombre</th><td>Gestión del tablero Kanban</td></tr>
                <tr><th>Actores</th><td>Usuario final, Manager de proyecto</td></tr>
                <tr><th>Precondiciones</th><td>Proyecto creado y acceso autorizado</td></tr>
                <tr><th>Descripción</th><td>Visualización y manipulación de tareas mediante metodología Kanban</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede al tablero Kanban del proyecto</li>
            <li>El usuario visualiza las columnas (Por hacer, En progreso, Hecho)</li>
            <li>El usuario crea nueva tarea completando título y descripción</li>
            <li>El usuario arrastra tareas entre columnas según el progreso</li>
            <li>El sistema actualiza automáticamente el estado de las tareas</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Personalización:</strong> Creación de columnas personalizadas</li>
            <li><strong>A2 - Asignaciones:</strong> Asignación de tareas a miembros específicos</li>
            <li><strong>A3 - Filtros:</strong> Aplicación de filtros por asignado, fecha, etc.</li>
            <li><strong>A4 - Etiquetas:</strong> Uso de etiquetas para categorizar tareas</li>
        </ul>
        
        <h5>CU-005: Generación de informes</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-005</td></tr>
                <tr><th>Nombre</th><td>Generación de informes</td></tr>
                <tr><th>Actores</th><td>Usuario final, Manager de proyecto, Administrador</td></tr>
                <tr><th>Precondiciones</th><td>Datos de tiempo registrados en el sistema</td></tr>
                <tr><th>Descripción</th><td>Creación y exportación de informes de productividad y tiempo</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a la sección de informes</li>
            <li>El usuario selecciona tipo de informe (por usuario, proyecto, periodo)</li>
            <li>El usuario configura filtros y parámetros</li>
            <li>El sistema genera el informe con gráficos y estadísticas</li>
            <li>El usuario puede exportar en PDF, Excel o CSV</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Informes programados:</strong> Configuración de informes automáticos periódicos</li>
            <li><strong>A2 - Comparativas:</strong> Informes comparativos entre periodos o proyectos</li>
            <li><strong>A3 - Personalización:</strong> Creación de plantillas de informes personalizadas</li>
        </ul>
        
        <h5>CU-006: Administración de usuarios y permisos</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-006</td></tr>
                <tr><th>Nombre</th><td>Administración de usuarios y permisos</td></tr>
                <tr><th>Actores</th><td>Administrador de organización, Administrador del sistema</td></tr>
                <tr><th>Precondiciones</th><td>Permisos de administración</td></tr>
                <tr><th>Descripción</th><td>Gestión de usuarios, roles y permisos dentro de la organización</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El administrador accede al panel de administración</li>
            <li>El administrador visualiza la lista de usuarios</li>
            <li>El administrador invita nuevos usuarios por email</li>
            <li>El administrador asigna roles y permisos específicos</li>
            <li>El sistema envía invitaciones y actualiza permisos</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Desactivación:</strong> Desactivación temporal de cuentas de usuario</li>
            <li><strong>A2 - Grupos:</strong> Creación de grupos de usuarios con permisos similares</li>
            <li><strong>A3 - Auditoría:</strong> Visualización de logs de actividad de usuarios</li>
        </ul>
        
        <h5>CU-007: Gestión de calendario</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-007</td></tr>
                <tr><th>Nombre</th><td>Gestión de calendario</td></tr>
                <tr><th>Actores</th><td>Usuario final</td></tr>
                <tr><th>Precondiciones</th><td>Usuario autenticado</td></tr>
                <tr><th>Descripción</th><td>Planificación y visualización de eventos y tareas programadas</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a la vista de calendario</li>
            <li>El usuario selecciona una fecha</li>
            <li>El usuario crea un evento con título, descripción y duración</li>
            <li>El usuario asocia el evento con un proyecto (opcional)</li>
            <li>El sistema guarda el evento y muestra recordatorios</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Eventos recurrentes:</strong> Creación de eventos que se repiten periódicamente</li>
            <li><strong>A2 - Integración:</strong> Sincronización con calendarios externos (Google, Outlook)</li>
            <li><strong>A3 - Notificaciones:</strong> Configuración de recordatorios por email o popup</li>
        </ul>
        
        <h5>CU-008: Gestión de descansos y pausas</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-008</td></tr>
                <tr><th>Nombre</th><td>Gestión de descansos y pausas</td></tr>
                <tr><th>Actores</th><td>Usuario final</td></tr>
                <tr><th>Precondiciones</th><td>Usuario autenticado, sesión de trabajo activa</td></tr>
                <tr><th>Descripción</th><td>El usuario puede tomar descansos durante su jornada laboral y el sistema los registra correctamente</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario está trabajando en un proyecto (tiempo corriendo)</li>
            <li>El usuario hace clic en "Iniciar descanso"</li>
            <li>El sistema pausa el temporizador de trabajo</li>
            <li>El sistema inicia el temporizador de descanso</li>
            <li>El usuario hace clic en "Finalizar descanso"</li>
            <li>El sistema reanuda el temporizador de trabajo</li>
            <li>El sistema registra la duración del descanso</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Tipos de descanso:</strong> El usuario puede categorizar el descanso (almuerzo, café, personal)</li>
            <li><strong>A2 - Descanso automático:</strong> El sistema puede sugerir descansos después de cierto tiempo trabajado</li>
            <li><strong>A3 - Descanso prolongado:</strong> Si el descanso excede un tiempo límite, el sistema solicita confirmación</li>
        </ul>
        
        <h5>CU-009: Colaboración en proyectos</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-009</td></tr>
                <tr><th>Nombre</th><td>Colaboración en proyectos</td></tr>
                <tr><th>Actores</th><td>Usuario final, Manager de proyecto</td></tr>
                <tr><th>Precondiciones</th><td>Proyecto compartido, múltiples usuarios asignados</td></tr>
                <tr><th>Descripción</th><td>Los usuarios pueden colaborar en proyectos compartidos viendo actividad de otros miembros</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a un proyecto compartido</li>
            <li>El usuario visualiza la actividad de otros miembros del equipo</li>
            <li>El usuario puede comentar en tareas de otros miembros</li>
            <li>El usuario recibe notificaciones de cambios en el proyecto</li>
            <li>El usuario puede asignar tareas a otros miembros</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - Chat integrado:</strong> Los usuarios pueden comunicarse mediante chat en tiempo real</li>
            <li><strong>A2 - Menciones:</strong> Los usuarios pueden mencionar a otros miembros en comentarios</li>
            <li><strong>A3 - Historial de actividad:</strong> Visualización del historial completo de cambios del proyecto</li>
        </ul>
        
        <h5>CU-010: Exportación e integración de datos</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-010</td></tr>
                <tr><th>Nombre</th><td>Exportación e integración de datos</td></tr>
                <tr><th>Actores</th><td>Usuario final, Administrador</td></tr>
                <tr><th>Precondiciones</th><td>Datos registrados en el sistema</td></tr>
                <tr><th>Descripción</th><td>El usuario puede exportar sus datos y sincronizar con herramientas externas</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario accede a la sección de exportación</li>
            <li>El usuario selecciona el rango de fechas y tipo de datos</li>
            <li>El usuario elige el formato de exportación (CSV, PDF, Excel)</li>
            <li>El sistema genera el archivo con los datos solicitados</li>
            <li>El usuario descarga el archivo generado</li>
        </ol>
        <p><strong>Flujos alternativos:</strong></p>
        <ul>
            <li><strong>A1 - API externa:</strong> Sincronización automática con herramientas de facturación</li>
            <li><strong>A2 - Backup programado:</strong> Exportación automática periódica de datos</li>
            <li><strong>A3 - Importación:</strong> Importación de datos desde otras herramientas de gestión de tiempo</li>
        </ul>
        
        <h4>2.3.4 Casos de uso de excepción</h4>
        
        <h5>CU-011: Recuperación de sesión perdida</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-011</td></tr>
                <tr><th>Nombre</th><td>Recuperación de sesión perdida</td></tr>
                <tr><th>Actores</th><td>Usuario final</td></tr>
                <tr><th>Precondiciones</th><td>Pérdida de conexión durante trabajo activo</td></tr>
                <tr><th>Descripción</th><td>El sistema recupera el trabajo en progreso tras una desconexión inesperada</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>El usuario pierde conexión mientras tiene tiempo corriendo</li>
            <li>Al reconectarse, el sistema detecta la sesión perdida</li>
            <li>El sistema presenta opciones de recuperación</li>
            <li>El usuario puede continuar desde donde se quedó o registrar el tiempo hasta la desconexión</li>
            <li>El sistema actualiza los registros según la decisión del usuario</li>
        </ol>
        
        <h5>CU-012: Manejo de conflictos de datos</h5>
        <div class="table-container">
            <table>
                <tr><th>ID</th><td>CU-012</td></tr>
                <tr><th>Nombre</th><td>Manejo de conflictos de datos</td></tr>
                <tr><th>Actores</th><td>Usuario final, Sistema</td></tr>
                <tr><th>Precondiciones</th><td>Edición simultánea de datos por múltiples usuarios</td></tr>
                <tr><th>Descripción</th><td>El sistema resuelve conflictos cuando múltiples usuarios editan la misma información</td></tr>
            </table>
        </div>
        <p><strong>Flujo principal:</strong></p>
        <ol>
            <li>Dos usuarios intentan editar la misma tarea simultáneamente</li>
            <li>El sistema detecta el conflicto al guardar</li>
            <li>El sistema presenta ambas versiones al último usuario en guardar</li>
            <li>El usuario puede elegir mantener su versión, aceptar la otra, o combinar ambas</li>
            <li>El sistema guarda la resolución y notifica a ambos usuarios</li>
        </ol>
        
        <h4>2.3.3 Diagrama de casos de uso</h4>
        <div class="mermaid use-case-diagram">
flowchart TD
    subgraph sistema[Sistema Traballa]
        subgraph autenticacion[Módulo de Autenticación]
            CU001[CU-001: Login/Registro]
            CU006[CU-006: Admin Usuarios]
        end
        
        subgraph tiempo[Módulo de Gestión de Tiempo]
            CU002[CU-002: Registro Tiempo]
            CU008[CU-008: Gestión Pausas]
            CU005[CU-005: Informes]
        end
        
        subgraph proyectos[Módulo de Proyectos]
            CU003[CU-003: Gestión Proyectos]
            CU004[CU-004: Tablero Kanban]
            CU009[CU-009: Colaboración]
        end
        
        subgraph calendario[Módulo de Planificación]
            CU007[CU-007: Calendario]
        end
        
        subgraph integracion[Módulo de Integración]
            CU010[CU-010: Exportación Datos]
        end
        
        subgraph excepcion[Casos de Excepción]
            CU011[CU-011: Recuperación Sesión]
            CU012[CU-012: Conflictos Datos]
        end
    end
    
    usuario[Usuario Final] --> CU001
    usuario --> CU002
    usuario --> CU003
    usuario --> CU004
    usuario --> CU005
    usuario --> CU007
    usuario --> CU008
    usuario --> CU009
    usuario --> CU010
    usuario --> CU011
    
    admin[Administrador] --> CU001
    admin --> CU006
    admin --> CU005
    admin --> CU010
    
    manager[Manager Proyecto] --> CU003
    manager --> CU004
    manager --> CU005
    manager --> CU009
    
    sistema_externo[Sistema Externo] --> CU010
    sistema_externo --> CU012
    
    classDef actor fill:#e1f5fe,stroke:#01579b,color:#000
    classDef usecase fill:#f3e5f5,stroke:#4a148c,color:#000
    classDef system fill:#e8f5e8,stroke:#1b5e20,color:#000
    classDef exception fill:#fff3e0,stroke:#e65100,color:#000
    
    class usuario,admin,manager,sistema_externo actor
    class CU001,CU002,CU003,CU004,CU005,CU006,CU007,CU008,CU009,CU010 usecase
    class CU011,CU012 exception
    class sistema,autenticacion,tiempo,proyectos,calendario,integracion system
    class excepcion exception
        </div>
        
        <h4>2.3.5 Matriz de trazabilidad</h4>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Caso de Uso</th>
                        <th>Requisito Funcional</th>
                        <th>Prioridad</th>
                        <th>Complejidad</th>
                        <th>Sprint</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CU-001</td>
                        <td>RF-001: Autenticación de usuarios</td>
                        <td>Alta</td>
                        <td>Media</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>CU-002</td>
                        <td>RF-002: Registro de tiempo</td>
                        <td>Alta</td>
                        <td>Alta</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>CU-003</td>
                        <td>RF-003: Gestión de proyectos</td>
                        <td>Alta</td>
                        <td>Media</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>CU-004</td>
                        <td>RF-004: Tablero Kanban</td>
                        <td>Media</td>
                        <td>Alta</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>CU-005</td>
                        <td>RF-005: Generación de informes</td>
                        <td>Media</td>
                        <td>Media</td>
                        <td>6</td>
                    </tr>
                    <tr>
                        <td>CU-006</td>
                        <td>RF-006: Administración de usuarios</td>
                        <td>Media</td>
                        <td>Media</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>CU-007</td>
                        <td>RF-007: Calendario integrado</td>
                        <td>Baja</td>
                        <td>Media</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>CU-008</td>
                        <td>RF-002: Registro de tiempo (descansos)</td>
                        <td>Media</td>
                        <td>Baja</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>CU-009</td>
                        <td>RF-008: Colaboración en tiempo real</td>
                        <td>Baja</td>
                        <td>Alta</td>
                        <td>Futuro</td>
                    </tr>
                    <tr>
                        <td>CU-010</td>
                        <td>RF-009: Exportación de datos</td>
                        <td>Media</td>
                        <td>Baja</td>
                        <td>6</td>
                    </tr>
                    <tr>
                        <td>CU-011</td>
                        <td>RF-010: Recuperación de sesión</td>
                        <td>Media</td>
                        <td>Media</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>CU-012</td>
                        <td>RF-011: Manejo de conflictos</td>
                        <td>Baja</td>
                        <td>Alta</td>
                        <td>Futuro</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p>Los casos de uso han sido priorizados según su importancia para el MVP (Producto Mínimo Viable) y su impacto en la experiencia del usuario. La implementación seguirá un enfoque iterativo, comenzando por los casos de uso fundamentales (autenticación y registro de tiempo) y progresando hacia funcionalidades más avanzadas.</p>
        
        <p>La matriz de trazabilidad permite verificar que todos los requisitos funcionales estén cubiertos por al menos un caso de uso, garantizando la completitud del análisis y facilitando la planificación del desarrollo en sprints de una semana cada uno.</p>
        
        <h4>2.3.6 Criterios de aceptación generales</h4>
        <ul>
            <li><strong>Funcionalidad:</strong> Todas las funciones deben comportarse según lo especificado en los flujos principales</li>
            <li><strong>Usabilidad:</strong> Las interfaces deben ser intuitivas y requerir mínima curva de aprendizaje</li>
            <li><strong>Rendimiento:</strong> Los tiempos de respuesta no deben exceder 3 segundos en condiciones normales</li>
            <li><strong>Seguridad:</strong> Todos los datos sensibles deben estar protegidos y encriptados</li>
            <li><strong>Compatibilidad:</strong> El sistema debe funcionar en los principales navegadores web modernos</li>
            <li><strong>Accesibilidad:</strong> Cumplimiento básico de estándares WCAG para usuarios con discapacidades</li>
        </ul>
    </div>
    
    <div id="normativa">
        <h3>2.4 Normativa</h3>
        <p>En esta sección se investiga qué normativa vigente afecta al desarrollo del proyecto y de qué manera. El proyecto debe adaptarse a las exigencias legales de los territorios donde va a operar.</p>
        
        <h4>2.4.1 Legislación de Protección de Datos</h4>
        <p>Por la naturaleza del sistema de información, una ley que se va a tener que mencionar de forma obligatoria es la <strong>Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y garantía de los derechos digitales (LOPDPGDD)</strong>. El ámbito de la LOPDPGDD es nacional.</p>
        
        <p>Si la aplicación está pensada para operar a nivel europeo, también se debe hacer referencia al <strong>General Data Protection Regulation (GDPR)</strong>. En la documentación debe afirmarse que el proyecto cumple con la normativa vigente.</p>
        
        <h4>2.4.2 Cumplimiento de la Normativa</h4>
        <p>Para cumplir la LOPDPGDD y/o GDPR debe tener un apartado en la web donde se indique quién es la persona responsable del tratamiento de los datos y para qué fines se van a utilizar. Habitualmente esta información se estructura en los siguientes apartados:</p>
        
        <ul>
            <li><strong>Aviso legal:</strong> Información sobre la titularidad del sitio web y los datos de contacto.</li>
            <li><strong>Política de privacidad:</strong> Detalle sobre qué datos se recopilan, cómo se utilizan, con quién se comparten y los derechos del usuario.</li>
            <li><strong>Política de cookies:</strong> Información sobre el uso de cookies y tecnologías de seguimiento.</li>
        </ul>
        
        <h4>2.4.3 Mecanismos de Cumplimiento</h4>
        <p>Deben explicarse los diferentes mecanismos utilizados para cumplir la legislación relativa a la protección de datos:</p>
        
        <ul>
            <li><strong>Consentimiento informado:</strong> Obtención del consentimiento explícito del usuario antes de procesar sus datos personales.</li>
            <li><strong>Minimización de datos:</strong> Recopilación únicamente de los datos necesarios para el funcionamiento del servicio.</li>
            <li><strong>Derecho de acceso:</strong> Mecanismos para que los usuarios puedan consultar qué datos personales se almacenan.</li>
            <li><strong>Derecho de rectificación:</strong> Posibilidad de corregir datos inexactos o incompletos.</li>
            <li><strong>Derecho de supresión:</strong> Implementación del "derecho al olvido" permitiendo eliminar datos cuando sea solicitado.</li>
            <li><strong>Portabilidad de datos:</strong> Capacidad de exportar los datos del usuario en un formato legible por máquina.</li>
            <li><strong>Seguridad de los datos:</strong> Implementación de medidas técnicas y organizativas apropiadas.</li>
            <li><strong>Notificación de brechas:</strong> Procedimientos para notificar violaciones de seguridad dentro de las 72 horas establecidas por la normativa.</li>
        </ul>
        
        <h4>2.4.4 Implementación en el Sistema</h4>
        <p>El sistema TRABALLA implementa los siguientes elementos para garantizar el cumplimiento normativo:</p>
        
        <ul>
            <li><strong>Páginas legales:</strong> Se han implementado las páginas de aviso legal, política de privacidad y política de cookies accesibles desde el footer del sitio.</li>
            <li><strong>Gestión de consentimientos:</strong> Banner de cookies y formularios de consentimiento para el procesamiento de datos.</li>
            <li><strong>Panel de privacidad:</strong> Sección en el perfil del usuario para gestionar sus derechos de protección de datos.</li>
            <li><strong>Cifrado de datos:</strong> Uso de HTTPS y cifrado de contraseñas mediante algoritmos seguros.</li>
            <li><strong>Logs de auditoría:</strong> Registro de accesos y modificaciones para cumplir con los requisitos de trazabilidad.</li>
            <li><strong>Procedimientos de eliminación:</strong> Implementación de rutinas de limpieza y anonimización de datos.</li>
        </ul>
        
        <p><strong>Declaración de cumplimiento:</strong> El proyecto TRABALLA cumple con la normativa vigente en materia de protección de datos, incluyendo la LOPDPGDD y el GDPR, implementando todas las medidas técnicas y organizativas necesarias para garantizar la privacidad y seguridad de los datos de los usuarios.</p>
    </div>
</div>
