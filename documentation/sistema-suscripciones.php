<div id="sistema-suscripciones" class="section">
    <h3>3.6 Sistema de suscripciones</h3>
    <div class="section-description">
        <p>Traballa incorpora un sistema de suscripciones que permite ofrecer diferentes niveles de servicio según las necesidades de los usuarios y organizaciones.</p>
    </div>
    
    <div id="modelo-datos-suscripciones">
        <h4>3.6.1 Modelo de datos de suscripciones</h4>
        <p>El sistema de suscripciones se basa en las siguientes tablas principales:</p>
        
        <div class="mermaid subscription-model-diagram" style="max-width: 600px; margin: 0 auto;">
erDiagram
    subscription_plans {
        int id PK
        string name
        string description
        decimal price
        enum billing_cycle
        int max_users
        int max_projects
        json features
        boolean is_active
        datetime created_at
        datetime updated_at
    }
    
    organizations {
        int id PK
        string name
        string description
        int current_plan_id FK
        enum subscription_status
        datetime trial_ends_at
    }
    
    organization_subscriptions {
        int id PK
        int organization_id FK
        int plan_id FK
        enum status
        datetime start_date
        datetime end_date
        datetime trial_end_date
        string payment_method
        string payment_id
    }
    
    subscription_usage {
        int id PK
        int organization_id FK
        date month
        int users_count
        int projects_count
        int api_calls
        bigint storage_used
    }
    
    organizations ||--o{ organization_subscriptions : "has"
    subscription_plans ||--o{ organization_subscriptions : "used in"
    subscription_plans ||--o{ organizations : "current plan"
    organizations ||--o{ subscription_usage : "tracks usage"
        </div>
        
        <p>La funcionalidad de suscripciones se implementa principalmente a través de estas tablas y está gestionada por la clase <code>SubscriptionManager</code>.</p>
    </div>
    
    <div id="planes-suscripcion">
        <h4>3.6.2 Planes de suscripción</h4>
        <p>El sistema está configurado con tres planes de suscripción predeterminados:</p>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Descripción</th>
                    <th>Precio (€/mes)</th>
                    <th>Máx. usuarios</th>
                    <th>Máx. proyectos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Free</td>
                    <td>Perfect for individuals</td>
                    <td>0.00</td>
                    <td>1</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Pro</td>
                    <td>Ideal for small teams</td>
                    <td>9.00</td>
                    <td>10</td>
                    <td>Ilimitado (-1)</td>
                </tr>
                <tr>
                    <td>Enterprise</td>
                    <td>Built for organizations</td>
                    <td>15.00</td>
                    <td>Ilimitado (-1)</td>
                    <td>Ilimitado (-1)</td>
                </tr>
            </tbody>
        </table>
        
        <p>Cada plan incluye un conjunto definido de características almacenadas como un objeto JSON en la tabla <code>subscription_plans</code>:</p>
        
        <pre><code>{
    "time_tracking": true|false,
    "basic_reports": true|false,
    "team_collaboration": true|false,
    "advanced_reports": true|false,
    "api_access": true|false,
    "priority_support": true|false,
    "calendar_integration": true|false,
    "custom_branding": true|false
}</code></pre>
    </div>
    
    <div id="gestion-subscripciones">
        <h4>3.6.3 Gestión de suscripciones</h4>
        <p>La clase <code>SubscriptionManager</code> encapsula toda la lógica relacionada con las suscripciones y proporciona los siguientes métodos principales:</p>
        
        <ul class="function-list">
            <li><strong>getPlans()</strong>: Recupera todos los planes de suscripción disponibles.</li>
            <li><strong>getPlan($plan_id)</strong>: Obtiene un plan específico por ID.</li>
            <li><strong>getPlanByName($plan_name)</strong>: Obtiene un plan específico por nombre.</li>
            <li><strong>getOrganizationSubscription($organization_id)</strong>: Obtiene la suscripción actual de una organización.</li>
            <li><strong>getOrganizationPlan($organization_id)</strong>: Obtiene el plan actual de una organización desde la tabla organizations.</li>
            <li><strong>canPerformAction($organization_id, $action)</strong>: Comprueba si una organización puede realizar una acción basada en su plan.</li>
            <li><strong>getUsageStats($organization_id)</strong>: Obtiene estadísticas de uso para una organización.</li>
            <li><strong>upgradePlan($organization_id, $new_plan_id)</strong>: Actualiza el plan de una organización.</li>
            <li><strong>startTrial($organization_id, $plan_id, $trial_days)</strong>: Inicia un período de prueba para una organización.</li>
            <li><strong>cancelSubscription($organization_id)</strong>: Cancela la suscripción de una organización.</li>
            <li><strong>updateUsageStats($organization_id)</strong>: Actualiza las estadísticas de uso para una organización.</li>
            <li><strong>assignPlan($organization_id, $plan_id)</strong>: Asigna un plan a una organización (para organizaciones nuevas).</li>
            <li><strong>getPlanLimitsText($plan)</strong>: Obtiene los límites del plan como texto formateado.</li>
        </ul>
    </div>
    
    <div id="estados-suscripcion">
        <h4>3.6.4 Estados de suscripción</h4>
        <p>Las suscripciones pueden tener varios estados que se gestionan a través del campo <code>subscription_status</code> en la tabla <code>organizations</code> y el campo <code>status</code> en la tabla <code>organization_subscriptions</code>:</p>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>active</td>
                    <td>La suscripción está activa y el plan se está pagando.</td>
                </tr>
                <tr>
                    <td>trial</td>
                    <td>La organización está en un período de prueba gratuito.</td>
                </tr>
                <tr>
                    <td>expired</td>
                    <td>La suscripción ha expirado (no implementado activamente).</td>
                </tr>
                <tr>
                    <td>cancelled</td>
                    <td>La suscripción ha sido cancelada y se ha vuelto al plan gratuito.</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div id="control-acceso-caracteristicas">
        <h4>3.6.5 Control de acceso a características</h4>
        <p>El control de acceso a las características basado en el plan se implementa principalmente a través del método <code>canPerformAction()</code>, que comprueba si una organización puede realizar una acción específica según su plan actual:</p>
        
        <pre><code>// Comprobación de permisos basados en suscripción
if (!canPerformAction($organization_id, 'advanced_reports')) {
    // Mostrar mensaje de error o redireccionar
    return;
}</code></pre>
        
        <p>Las acciones comprobadas incluyen:</p>
        <ul>
            <li><code>add_user</code>: Comprobar si se puede añadir más usuarios (límite de usuarios)</li>
            <li><code>create_project</code>: Comprobar si se pueden crear más proyectos (límite de proyectos)</li>
            <li><code>team_collaboration</code>: Comprobar si la colaboración en equipo está habilitada</li>
            <li><code>advanced_reports</code>: Comprobar si los informes avanzados están habilitados</li>
            <li><code>api_access</code>: Comprobar si el acceso API está habilitado</li>
            <li><code>priority_support</code>: Comprobar si el soporte prioritario está habilitado</li>
            <li><code>calendar_integration</code>: Comprobar si la integración del calendario está habilitada</li>
        </ul>
    </div>
    
    <div id="interfaz-usuario-suscripciones">
        <h4>3.6.6 Interfaz de usuario para gestión de suscripciones</h4>
        <p>La gestión de suscripciones se realiza principalmente a través de la página <code>subscription.php</code>, que permite a los usuarios ver su plan actual, explorar planes disponibles, actualizar su plan y cancelar suscripciones.</p>
        
        <p>Además, el estado de la suscripción se muestra en la barra lateral para proporcionar contexto constante sobre el plan actual y notificar cuando un período de prueba está por finalizar.</p>
    </div>
</div>
