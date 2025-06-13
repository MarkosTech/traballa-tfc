<div id="diseno" class="section">
    <h2>3. Diseño</h2>
    <div class="section-description">
        <p>Esta sección aborda la arquitectura del sistema, el modelo de datos y las interfaces de usuario que conforman la solución.</p>
    </div>
    
    <div id="arquitectura">
        <h3>3.1 Arquitectura del sistema</h3>
        <p>Traballa implementa una arquitectura de aplicación web estructurada en capas con separación de responsabilidades, pero sin seguir estrictamente el patrón MVC tradicional. La aplicación está organizada en las siguientes capas:</p>
        <ul>
            <li><strong>Capa de Presentación:</strong> Interfaces de usuario desarrolladas con HTML5, CSS3 y JavaScript, con lógica de vista integrada mediante PHP.</li>
            <li><strong>Capa de Aplicación:</strong> Scripts PHP que combinan lógica de presentación y de negocio, procesando las solicitudes del usuario directamente en las páginas.</li>
            <li><strong>Capa de Datos:</strong> Funciones y clases PHP para acceso a la base de datos MySQL, con algunas abstracciones básicas.</li>
        </ul>
        <p>El sistema sigue un enfoque pragmático que prioriza la simplicidad y rapidez de desarrollo, con separación modular básica que permite la mantenibilidad del código.</p>
        
        <div class="mermaid architecture-flow-diagram" style="max-width: 500px; margin: 0 auto;">
flowchart TD
    CLIENTE["CLIENTE (BROWSER)"]
    SERVIDOR["SERVIDOR WEB"]
    PAGINAS_PHP["PÁGINAS PHP\n(Vista + Lógica integrada)"]
    FUNCIONES["FUNCIONES Y CLASES\n(Lógica de negocio y acceso a datos)"]
    DB["BASE DE DATOS\n(MySQL)"]
    
    CLIENTE -- "HTTP Request" --> SERVIDOR
    SERVIDOR -- "HTTP Response" --> CLIENTE
    
    SERVIDOR -- "PHP Request" --> PAGINAS_PHP
    PAGINAS_PHP -- "Llamadas a funciones" --> FUNCIONES
    FUNCIONES -- "Datos procesados" --> PAGINAS_PHP
    PAGINAS_PHP --> SERVIDOR
    
    FUNCIONES -- "SQL Queries" --> DB
    DB -- "Resultados" --> FUNCIONES
    
    classDef box rx:5,ry:5;
    class CLIENTE,SERVIDOR,PAGINAS_PHP,FUNCIONES,DB box;
            </div>
            
            <h4>Diagrama de arquitectura</h4>
            <div class="mermaid architecture-diagram" style="max-width: 520px; margin: 0 auto;">
flowchart TB
    subgraph cliente[CLIENTE]
        navegador[Navegador Web<br>HTML, CSS, JavaScript]
    end

    subgraph servidor[SERVIDOR]
        presentacion[Capa de Presentación<br>PHP - Páginas con Vista y Lógica]
        servicios[Capa de Servicios<br>PHP - Funciones y Clases Auxiliares]
        datos[Capa de Datos<br>MySQL/MariaDB]
    end

    navegador -->|HTTP/HTTPS| presentacion
    presentacion --> servicios
    servicios --> datos
</div>

            <h4>Flujo de comunicación</h4>
            <div class="mermaid communication-diagram" style="max-width: 500px; margin: 0 auto;">
sequenceDiagram
    participant Cliente
    participant Servidor
    participant DB as Base de Datos
    
    Cliente->>Servidor: 1. Solicitud HTTP
    Servidor->>DB: 2. Consulta SQL
    DB-->>Servidor: 3. Respuesta SQL
    Servidor-->>Cliente: 4. Respuesta HTTP
    
    Cliente->>Servidor: 5. Actualización AJAX
    Servidor->>DB: 6. Consulta SQL
    DB-->>Servidor: 7. Respuesta SQL
    Servidor-->>Cliente: 8. Respuesta JSON
</div>
        </div>

        <div id="patron-arquitectonico">
            <h3>3.1.1 Consideraciones sobre el patrón arquitectónico</h3>
            <p>Es importante aclarar que Traballa <strong>no implementa el patrón MVC (Modelo-Vista-Controlador)</strong> en su forma tradicional. En su lugar, utiliza una arquitectura simplificada que mezcla la lógica de presentación con la lógica de negocio en las mismas páginas PHP. Esta decisión arquitectónica se justifica por:</p>
            <ul>
                <li><strong>Simplicidad de desarrollo:</strong> Reduce la complejidad del código para un proyecto de alcance específico</li>
                <li><strong>Rapidez de implementación:</strong> Permite el desarrollo ágil sin la sobrecarga de múltiples capas de abstracción</li>
                <li><strong>Mantenibilidad adecuada:</strong> Para el tamaño y complejidad del proyecto, la separación es suficiente</li>
                <li><strong>Facilidad de comprensión:</strong> La lógica completa de cada página está contenida en un solo archivo</li>
            </ul>
            <p>La estructura actual sigue un patrón más próximo a:</p>
            <ul>
                <li><strong>Páginas monolíticas con separación modular:</strong> Cada página maneja su propia lógica de presentación y negocio</li>
                <li><strong>Funciones auxiliares centralizadas:</strong> Lógica común en includes/ para reutilización</li>
                <li><strong>Acceso a datos mediante funciones:</strong> Sin capa de abstracción ORM, usando funciones directas</li>
            </ul>
        </div>
        
        <div id="modelo-datos">
            <h3>3.2 Modelo de datos</h3>
            <p>El modelo de datos de Traballa está compuesto por las siguientes entidades principales:</p>
            <ul>
                <li><strong>Users:</strong> Información de usuarios y credenciales.</li>
                <li><strong>Projects:</strong> Datos de proyectos, incluyendo nombre, descripción, fechas y estado.</li>
                <li><strong>TimeEntries:</strong> Registros de tiempo asociados a usuarios y proyectos.</li>
                <li><strong>Tasks:</strong> Tareas individuales dentro de proyectos, con estados para el tablero Kanban.</li>
                <li><strong>Organizations:</strong> Grupos de usuarios que comparten proyectos y recursos.</li>
                <li><strong>Reports:</strong> Configuraciones y plantillas para la generación de informes.</li>
            </ul>
            <p>Las relaciones entre estas entidades están diseñadas para garantizar la integridad referencial y facilitar las consultas comunes del sistema.</p>
            
            <div class="mermaid data-model-diagram" style="max-width: 600px; margin: 0 auto;">
flowchart TD
    USUARIOS["USUARIOS"]
    ORGANIZACIONES["ORGANIZACIONES"]
    PROYECTOS["PROYECTOS"]
    REGISTRO_HORAS["REGISTRO HORAS"]
    TAREAS["TAREAS"]
    INFORMES["INFORMES"]
    KANBAN_TABS["KANBAN TABS"]
    
    ORGANIZACIONES -- "relación" --> USUARIOS
    ORGANIZACIONES -- "contiene" --> PROYECTOS
    
    USUARIOS --> REGISTRO_HORAS
    ORGANIZACIONES --> TAREAS
    PROYECTOS --> INFORMES
    
    TAREAS --> KANBAN_TABS
    KANBAN_TABS --> TAREAS
    
    classDef entity rx:5,ry:5,fill:#f9f9f9,stroke:#333;
    class USUARIOS,ORGANIZACIONES,PROYECTOS,REGISTRO_HORAS,TAREAS,INFORMES,KANBAN_TABS entity;
            </div>
        </div>
        
        <div id="interfaces-usuario">
            <h3>3.3 Interfaces de usuario</h3>
            <p>Las interfaces de usuario de Traballa siguen principios de diseño centrado en el usuario, con un enfoque en la simplicidad y la eficiencia:</p>
            <ul>
                <li><strong>Dashboard:</strong> Panel principal con resumen de actividad reciente y acceso rápido a funciones principales.</li>
                <li><strong>Registro de Tiempo:</strong> Interfaz intuitiva para iniciar, pausar y registrar tiempos con mínimos clics.</li>
                <li><strong>Tablero Kanban:</strong> Vista de columnas personalizables con tarjetas de tareas que se pueden arrastrar y soltar.</li>
                <li><strong>Calendario:</strong> Vista mensual, semanal y diaria de actividades programadas.</li>
                <li><strong>Reportes:</strong> Interfaces para configurar, visualizar y exportar informes con diferentes filtros y formatos.</li>
                <li><strong>Configuración:</strong> Paneles para la administración de usuarios, proyectos y preferencias del sistema.</li>
            </ul>
            <p>Todas las interfaces siguen un diseño responsivo que se adapta a diferentes tamaños de pantalla, desde dispositivos móviles hasta monitores de escritorio.</p>
        </div>

        <div id="diagramas-sistema">
            <h3>3.4 Diagramas del sistema</h3>
            <p>Los siguientes diagramas ilustran las principales entidades y relaciones en el sistema, así como el flujo de información entre los diferentes componentes:</p>
            
            <h4>Modelo entidad-relación</h4>
            <div class="mermaid er-diagram" style="max-width: 300px; margin: 0 auto;">
erDiagram
    USERS {
        string id PK
        string name
        string email
        string role
    }
    ORGANIZATIONS {
        string id PK
        string name
        string description
    }
    PROJECTS {
        string id PK
        string name
        string organization_id FK
        string status
    }
    
    USERS ||--o{ ORGANIZATIONS : "pertenece a"
    ORGANIZATIONS ||--o{ PROJECTS : "contiene"
</div>

            <h4>Gestión de tiempo y trabajo</h4>
            <div class="mermaid er-diagram" style="max-width: 500px; margin: 0 auto;">
erDiagram
    USERS {
        string id PK
        string name
        string email
    }
    PROJECTS {
        string id PK
        string name
    }
    WORK_HOURS {
        string id PK
        string user_id FK
        string project_id FK
        datetime clock_in
        datetime clock_out
        float total_hours
    }
    BREAKS {
        string id PK
        string work_hour_id FK
        datetime start_time
        datetime end_time
        float duration
    }
    
    USERS ||--o{ WORK_HOURS : "registra"
    PROJECTS ||--o{ WORK_HOURS : "incluye"
    WORK_HOURS ||--o{ BREAKS : "contiene"
</div>

            <h4>Sistema kanban</h4>
            <div class="mermaid er-diagram" style="max-width: 600px; margin: 0 auto;">
erDiagram
    PROJECTS {
        string id PK
        string name
    }
    USERS {
        string id PK
        string name
    }
    KANBAN_COLUMNS {
        string id PK
        string project_id FK
        string name
        int position
    }
    KANBAN_TASKS {
        string id PK
        string column_id FK
        string title
        string assigned_to FK
        string status
        string created_by FK
    }
    
    PROJECTS ||--o{ KANBAN_COLUMNS : "tiene"
    KANBAN_COLUMNS ||--o{ KANBAN_TASKS : "contiene"
    USERS ||--o{ KANBAN_TASKS : "asignado a"
    USERS ||--o{ KANBAN_TASKS : "creado por"
</div>

            <h4>Diagrama de entidad-relación completo</h4>
            <div class="mermaid er-complete-diagram">
erDiagram
    USERS {
        int id PK
        string name
        string email
        string password
        string role
        datetime created_at
    }
    
    ORGANIZATIONS {
        int id PK
        string name
        string description
        string logo
        datetime created_at
        datetime updated_at
    }
    
    ORGANIZATION_MEMBERS {
        int id PK
        int organization_id FK
        int user_id FK
        boolean is_admin
        datetime joined_at
    }
    
    PROJECTS {
        int id PK
        int organization_id FK
        string name
        string description
        string status
        datetime created_at
        datetime updated_at
        datetime last_kanban_update
    }
    
    PROJECT_MEMBERS {
        int id PK
        int project_id FK
        int user_id FK
        boolean is_manager
        datetime joined_at
    }
    
    WORK_HOURS {
        int id PK
        int user_id FK
        int project_id FK
        datetime clock_in
        datetime clock_out
        float total_hours
        string notes
        string status
    }
    
    BREAKS {
        int id PK
        int work_hour_id FK
        datetime start_time
        datetime end_time
        float duration
        string break_type
        string notes
        string status
    }
    
    KANBAN_TABS {
        int id PK
        int project_id FK
        string name
        int position
        boolean is_default
        datetime created_at
        datetime updated_at
    }
    
    KANBAN_COLUMNS {
        int id PK
        int project_id FK
        int tab_id FK
        string name
        int position
        datetime created_at
        datetime updated_at
    }
    
    KANBAN_TASKS {
        int id PK
        int column_id FK
        int project_id FK
        int tab_id FK
        string title
        string description
        int assigned_to FK
        int position
        string status
        int created_by FK
        datetime due_date
        datetime created_at
        datetime updated_at
    }
    
    CALENDAR_EVENTS {
        int id PK
        string title
        string description
        datetime start_date
        datetime end_date
        string event_type
        int user_id FK
        int project_id FK
        int organization_id FK
        datetime created_at
        datetime updated_at
    }
    
    ANALYTICS {
        int id PK
        int user_id FK
        int project_id FK
        datetime record_date
        float total_hours
        float overtime
    }
    
    LOGIN_ATTEMPTS {
        int id PK
        string email
        string ip_address
        datetime attempt_time
    }
    
    PASSWORD_RESETS {
        int id PK
        int user_id FK
        string token
        datetime expires_at
        datetime created_at
    }
    
    USER_SESSIONS {
        string session_id PK
        string session_data
        int expiry
        datetime created_at
    }
    
    ORGANIZATIONS ||--o{ ORGANIZATION_MEMBERS : has
    USERS ||--o{ ORGANIZATION_MEMBERS : belongs_to
    
    ORGANIZATIONS ||--o{ PROJECTS : owns
    PROJECTS ||--o{ PROJECT_MEMBERS : has
    USERS ||--o{ PROJECT_MEMBERS : member_of
    
    USERS ||--o{ WORK_HOURS : records
    PROJECTS ||--o{ WORK_HOURS : includes
    WORK_HOURS ||--o{ BREAKS : contains
    
    PROJECTS ||--o{ KANBAN_TABS : has
    PROJECTS ||--o{ KANBAN_COLUMNS : has
    KANBAN_TABS ||--o{ KANBAN_COLUMNS : organizes
    KANBAN_COLUMNS ||--o{ KANBAN_TASKS : contains
    PROJECTS ||--o{ KANBAN_TASKS : includes
    KANBAN_TABS ||--o{ KANBAN_TASKS : organizes
    USERS ||--o{ KANBAN_TASKS : assigned_to
    USERS ||--o{ KANBAN_TASKS : created_by
    
    USERS ||--o{ CALENDAR_EVENTS : personal_events
    PROJECTS ||--o{ CALENDAR_EVENTS : project_events
    ORGANIZATIONS ||--o{ CALENDAR_EVENTS : org_events
    
    USERS ||--o{ ANALYTICS : generates
    PROJECTS ||--o{ ANALYTICS : tracked_in
    
    USERS ||--o{ PASSWORD_RESETS : requests
</div>

            <h4>Diagrama de flujo de procesos principales</h4>
            <div class="mermaid process-flow-diagram" style="max-width: 500px; margin: 0 auto;">
flowchart TD
    A[Usuario inicia sesión] --> B{¿Autenticado?}
    B -->|No| C[Mostrar error]
    B -->|Sí| D[Cargar dashboard]
    
    D --> E[Seleccionar proyecto]
    E --> F[Registrar entrada]
    F --> G[Trabajar en proyecto]
    
    G --> H{¿Tomar descanso?}
    H -->|Sí| I[Iniciar descanso]
    I --> J[Finalizar descanso]
    J --> G
    
    H -->|No| K{¿Continuar trabajando?}
    K -->|Sí| G
    K -->|No| L[Registrar salida]
    
    L --> M[Calcular horas totales]
    M --> N[Guardar registro]
    N --> O[Actualizar analytics]
    
    G --> P{¿Usar Kanban?}
    P -->|Sí| Q[Gestionar tareas]
    Q --> R[Actualizar estado]
    R --> G
    
    P -->|No| S{¿Programar eventos?}
    S -->|Sí| T[Crear evento calendario]
    T --> G
    
    S -->|No| U{¿Generar reportes?}
    U -->|Sí| V[Configurar filtros]
    V --> W[Generar reporte]
    W --> X[Exportar datos]
    
    classDef startEnd fill:#4caf50,stroke:#2e7d32,color:#fff
    classDef process fill:#2196f3,stroke:#1565c0,color:#fff
    classDef decision fill:#ff9800,stroke:#ef6c00,color:#fff
    classDef data fill:#9c27b0,stroke:#6a1b9a,color:#fff
    
    class A,C,X startEnd
    class D,E,F,G,I,J,L,M,N,O,Q,R,T,V,W process
    class B,H,K,P,S,U decision
</div>
    </div>
</div>
