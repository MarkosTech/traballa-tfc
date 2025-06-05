<?php
/**
 * Traballa - Privacy policy
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */
?>

<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy policy - Traballa Tracker</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #ffffff 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .privacy-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 2rem;
        }

        .privacy-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .back-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .contact-info {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 0.75rem;
            border-left: 4px solid var(--primary-color);
        }

        .last-updated {
            background: rgba(102, 126, 234, 0.05);
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .rights-card {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .rights-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
        }

        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        @media (max-width: 768px) {
            .privacy-header {
                padding: 2rem 0 1rem;
            }
            
            .privacy-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }

        /* Language toggle styles */
        .language-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50px;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .language-toggle button {
            background: transparent;
            border: 2px solid transparent;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .language-toggle button.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .language-toggle button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Hide/show content based on language */
        [data-lang="es"] {
            display: none;
        }

        html[lang="es"] [data-lang="en"] {
            display: none;
        }

        html[lang="es"] [data-lang="es"] {
            display: block;
        }

        html[lang="en"] [data-lang="en"] {
            display: block;
        }

        html[lang="en"] [data-lang="es"] {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Language Toggle -->
    <div class="language-toggle">
        <button onclick="setLanguage('en')" id="btn-en" class="active">EN</button>
        <button onclick="setLanguage('es')" id="btn-es">ES</button>
    </div>

    <!-- English Header -->
    <div class="privacy-header" data-lang="en">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-shield-alt me-3"></i>Privacy policy
            </h1>
            <p class="lead mb-0">General Data Protection Regulation (GDPR) compliance</p>
        </div>
    </div>

    <!-- Spanish Header -->
    <div class="privacy-header" data-lang="es">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-shield-alt me-3"></i>Política de privacidad
            </h1>
            <p class="lead mb-0">Cumplimiento del Reglamento General de Protección de Datos (GDPR)</p>
        </div>
    </div>

    <div class="container">
        <!-- English Content -->
        <div class="privacy-container" data-lang="en">
                <div class="last-updated">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <strong>Last updated:</strong> <?php echo date('F j, Y'); ?>
                </div>

                <div class="highlight-box">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>Important notice
                    </h5>
                    <p class="mb-0">
                        This privacy policy describes how we collect, use and protect your personal information 
                        in accordance with the General Data Protection Regulation (GDPR).
                    </p>
                </div>
                            
                <!-- Information about the controller -->
                <h3 class="section-title">1. Data controller</h3>
                <div class="ps-3">
                    <p><strong>Controller:</strong> Marcos Núñez Fernández</p>
                    <p><strong>Contact:</strong> privacy@markostech.es</p>
                    <p><strong>Project:</strong> Traballa</p>
                    <p><strong>Project purpose:</strong> Final Course Project (TFC)</p>
                    <p><strong>Repository:</strong> <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">https://github.com/markostech/traballa-tfc</a></p>
                </div>

                <!-- Data we collect -->
                <h3 class="section-title">2. Data we collect</h3>
                <div class="ps-3">
                    <h5>2.1 Registration and authentication data</h5>
                    <ul>
                        <li><strong>Identifying data:</strong> First name, last name, username, email address</li>
                        <li><strong>Authentication data:</strong> Password (stored encrypted)</li>
                        <li><strong>Session data:</strong> Login and logout information, IP address</li>
                        <li><strong>Legal basis:</strong> Consent of the data subject (Art. 6.1.a GDPR)</li>
                    </ul>

                    <h5>2.2 Work activity data</h5>
                    <ul>
                        <li><strong>Working hours:</strong> Date, start and end time, associated project</li>
                        <li><strong>Projects:</strong> Project name, description, participants</li>
                        <li><strong>Tasks:</strong> Description of tasks performed, time spent</li>
                        <li><strong>Legal basis:</strong> Performance of a contract or pre-contractual measures (Art. 6.1.b GDPR)</li>
                    </ul>

                    <h5>2.3 Technical data</h5>
                    <ul>
                        <li><strong>Browser information:</strong> User-Agent, preferred language</li>
                        <li><strong>Connection data:</strong> IP address, access timestamps</li>
                        <li><strong>Technical cookies:</strong> For session functionality</li>
                        <li><strong>Legal basis:</strong> Legitimate interest for system security (Art. 6.1.f GDPR)</li>
                    </ul>
                </div>

                <!-- Purpose of processing -->
                <h3 class="section-title">3. Processing purposes</h3>
                <div class="ps-3">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Purpose</th>
                                    <th>Legal basis</th>
                                    <th>Data used</th>
                                    <th>Retention</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>User management and authentication</td>
                                    <td>Consent (Art. 6.1.a)</td>
                                    <td>Registration data</td>
                                    <td>Until account deletion</td>
                                </tr>
                                <tr>
                                    <td>Working hours tracking</td>
                                    <td>Contract performance (Art. 6.1.b)</td>
                                    <td>Work data</td>
                                    <td>3 years after completion</td>
                                </tr>
                                <tr>
                                    <td>Report generation</td>
                                    <td>Contract performance (Art. 6.1.b)</td>
                                    <td>Activity data</td>
                                    <td>3 years after completion</td>
                                </tr>
                                <tr>
                                    <td>System security</td>
                                    <td>Legitimate interest (Art. 6.1.f)</td>
                                    <td>Access logs</td>
                                    <td>1 year</td>
                                </tr>
                                <tr>
                                    <td>Legal compliance</td>
                                    <td>Legal obligation (Art. 6.1.c)</td>
                                    <td>All data</td>
                                    <td>According to applicable regulations</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data sharing -->
                <h3 class="section-title">4. Data sharing</h3>
                <div class="ps-3">
                    <p><strong>General principle:</strong> We do not share your personal data with third parties, except in the following cases:</p>
                    <ul>
                        <li><strong>Project members:</strong> Work data shared with other members of the same project</li>
                        <li><strong>Legal obligations:</strong> When required by competent authorities</li>
                        <li><strong>Explicit consent:</strong> When you have given your specific consent</li>
                    </ul>
                    <p><strong>International transfers:</strong> All data is processed within the European Union.</p>
                </div>

                <!-- Data retention -->
                <h3 class="section-title">5. Data retention</h3>
                <div class="ps-3">
                    <ul>
                        <li><strong>Active account data:</strong> While the account is active</li>
                        <li><strong>Work data:</strong> 3 years after project completion</li>
                        <li><strong>Security logs:</strong> 1 year for security purposes</li>
                        <li><strong>Data with withdrawn consent:</strong> Immediate deletion, except legal obligation</li>
                    </ul>
                    <p><strong>Automatic deletion:</strong> The system automatically deletes data when the retention period expires.</p>
                </div>

                <!-- Your rights -->
                <h3 class="section-title">6. Your rights under GDPR</h3>
                <div class="ps-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card rights-card border-primary mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-eye"></i> Right of access (Art. 15)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to obtain information about the processing of your personal data and access to it.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-primary">Access my data</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card rights-card border-warning mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-edit"></i> Right of rectification (Art. 16)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to obtain rectification of inaccurate personal data concerning you.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-warning">Correct data</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card rights-card border-danger mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-trash"></i> Right to be forgotten (Art. 17)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to obtain erasure of personal data when certain circumstances apply.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-danger">Delete data</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card rights-card border-info mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-ban"></i> Right to restriction (Art. 18)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to obtain restriction of processing when certain conditions are met.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-info">Restrict processing</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card rights-card border-success mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-exchange-alt"></i> Right to portability (Art. 20)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to receive personal data in a structured, commonly used and machine-readable format.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-success">Export data</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card rights-card border-secondary mb-3">
                                <div class="card-header">
                                    <strong><i class="fas fa-hand-paper"></i> Right to object (Art. 21)</strong>
                                </div>
                                <div class="card-body">
                                    <p>Right to object to processing of personal data for reasons related to your particular situation.</p>
                                    <a href="gdpr.php" class="btn btn-sm btn-secondary">Object</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security measures -->
                <h3 class="section-title">7. Security measures</h3>
                <div class="ps-3">
                    <p>We implement appropriate technical and organizational measures to ensure the security of your data:</p>
                    <ul>
                        <li><strong>Encryption:</strong> Passwords are stored with secure hash (bcrypt)</li>
                        <li><strong>Secure connections:</strong> Use of HTTPS for all communications</li>
                        <li><strong>Access control:</strong> Required authentication and session control</li>
                        <li><strong>Audit logs:</strong> Recording of all activities related to personal data</li>
                        <li><strong>Backups:</strong> Regular encrypted backups</li>
                        <li><strong>Data minimization:</strong> We only collect strictly necessary data</li>
                    </ul>
                </div>

                <!-- Cookies -->
                <h3 class="section-title">8. Cookies and similar technologies</h3>
                <div class="ps-3">
                    <p>We use the following cookies:</p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Purpose</th>
                                    <th>Duration</th>
                                    <th>Legal basis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Session cookies</td>
                                    <td>Maintain authenticated user session</td>
                                    <td>Until browser closes</td>
                                    <td>Strictly necessary</td>
                                </tr>
                                <tr>
                                    <td>Security cookies</td>
                                    <td>24 hours</td>
                                    <td>Strictly necessary</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p><strong>Cookie control:</strong> You can configure your browser to reject cookies, although this may affect system functionality.</p>
                </div>

                <!-- Breach notification -->
                <h3 class="section-title">9. Security breach notification</h3>
                <div class="ps-3">
                    <p>In case of a security breach that may pose a high risk to your rights and freedoms:</p>
                    <ul>
                        <li><strong>Notification to authorities:</strong> We will inform the supervisory authority within 72 hours</li>
                        <li><strong>Communication to data subject:</strong> We will notify you without undue delay if there is high risk</li>
                        <li><strong>Measures taken:</strong> Information about measures taken to address the breach</li>
                    </ul>
                </div>

                <!-- Contact and complaints -->
                <h3 class="section-title">10. Contact and complaints</h3>
                <div class="ps-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-info">
                                <h5 class="text-primary mb-3">Contact to exercise rights</h5>
                                <address>
                                    <strong>Marcos Núñez Fernández</strong><br>
                                    Email: privacy@markostech.es<br>
                                    Response: Maximum 1 month
                                </address>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-info">
                                <h5 class="text-primary mb-3">Supervisory authority</h5>
                                <address>
                                    <strong>Spanish Data Protection Agency</strong><br>
                                    Website: <a href="https://www.aepd.es" target="_blank" class="text-primary">www.aepd.es</a><br>
                                    Phone: 901 100 099
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Updates -->
                <h3 class="section-title">11. Updates to this policy</h3>
                <div class="ps-3">
                    <p>This privacy policy may be updated to reflect changes in our data processing practices or applicable legislation.</p>
                    <p><strong>Last update:</strong> June 1, 2025</p>
                    <p><strong>Change notification:</strong> We will notify you by email of any significant changes to this policy.</p>
                </div>

                <!-- Legal basis summary -->
                <div class="highlight-box">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-balance-scale me-2"></i>Summary of legal bases
                    </h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                <h6>Consent</h6>
                                <small>User registration, marketing</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-file-contract fa-2x text-info mb-2"></i>
                                <h6>Contract performance</h6>
                                <small>Hours management, projects</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                <h6>Legitimate interest</h6>
                                <small>Security, fraud prevention</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="login.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <a href="gdpr.php" class="back-btn ms-2">
                        <i class="fas fa-user-shield"></i>
                        Manage my GDPR data
                    </a>
                </div>
        </div>

        <!-- Spanish Content -->
        <div class="privacy-container" data-lang="es">
                <div class="last-updated">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <strong>Última actualización:</strong> <?php echo date('j F Y'); ?>
                </div>

                <div class="highlight-box">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>Aviso importante
                    </h5>
                    <p class="mb-0">
                        Esta política de privacidad describe cómo recopilamos, utilizamos y protegemos tu información personal 
                        de acuerdo con el Reglamento General de Protección de Datos (GDPR).
                    </p>
                </div>
                            
                <!-- Information about the controller -->
                <h3 class="section-title">1. Responsable del tratamiento</h3>
                <div class="ps-3">
                    <p><strong>Responsable:</strong> Marcos Núñez Fernández</p>
                    <p><strong>Contacto:</strong> privacy@markostech.es</p>
                    <p><strong>Proyecto:</strong> Traballa</p>
                    <p><strong>Finalidad del proyecto:</strong> Trabajo de fin de curso (TFC)</p>
                    <p><strong>Repositorio:</strong> <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="text-primary">https://github.com/markostech/traballa-tfc</a></p>
                </div>

                <!-- Data we collect -->
                <h3 class="section-title">2. Datos que recopilamos</h3>
                <div class="ps-3">
                    <h5>2.1 Datos de registro y autenticación</h5>
                    <ul>
                        <li><strong>Datos identificativos:</strong> Nombre, apellidos, nombre de usuario, dirección de email</li>
                        <li><strong>Datos de autenticación:</strong> Contraseña (almacenada de forma cifrada)</li>
                        <li><strong>Datos de sesión:</strong> Información de inicio y cierre de sesión, dirección IP</li>
                        <li><strong>Base legal:</strong> Consentimiento del interesado (Art. 6.1.a GDPR)</li>
                    </ul>

                    <h5>2.2 Datos de actividad laboral</h5>
                    <ul>
                        <li><strong>Horas trabajadas:</strong> Fecha, hora de inicio y fin, proyecto asociado</li>
                        <li><strong>Proyectos:</strong> Nombre del proyecto, descripción, participantes</li>
                        <li><strong>Tareas:</strong> Descripción de tareas realizadas, tiempo empleado</li>
                        <li><strong>Base legal:</strong> Ejecución de un contrato o aplicación de medidas precontractuales (Art. 6.1.b GDPR)</li>
                    </ul>

                    <h5>2.3 Datos técnicos</h5>
                    <ul>
                        <li><strong>Información del navegador:</strong> User-Agent, idioma preferido</li>
                        <li><strong>Datos de conexión:</strong> Dirección IP, timestamps de acceso</li>
                        <li><strong>Cookies técnicas:</strong> Para el funcionamiento de la sesión</li>
                        <li><strong>Base legal:</strong> Interés legítimo para la seguridad del sistema (Art. 6.1.f GDPR)</li>
                    </ul>
                </div>

                <!-- Purpose of processing -->
                <h3 class="section-title">3. Finalidades del tratamiento</h3>
                <div class="ps-3">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Finalidad</th>
                                    <th>Base legal</th>
                                    <th>Datos utilizados</th>
                                    <th>Retención</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gestión de usuarios y autenticación</td>
                                    <td>Consentimiento (Art. 6.1.a)</td>
                                    <td>Datos de registro</td>
                                    <td>Hasta eliminación de cuenta</td>
                                </tr>
                                <tr>
                                    <td>Seguimiento de horas de trabajo</td>
                                    <td>Ejecución de contrato (Art. 6.1.b)</td>
                                    <td>Datos laborales</td>
                                    <td>3 años tras finalización</td>
                                </tr>
                                <tr>
                                    <td>Generación de informes</td>
                                    <td>Ejecución de contrato (Art. 6.1.b)</td>
                                    <td>Datos de actividad</td>
                                    <td>3 años tras finalización</td>
                                </tr>
                                <tr>
                                    <td>Seguridad del sistema</td>
                                    <td>Interés legítimo (Art. 6.1.f)</td>
                                    <td>Logs de acceso</td>
                                    <td>1 año</td>
                                </tr>
                                <tr>
                                    <td>Cumplimiento legal</td>
                                    <td>Obligación legal (Art. 6.1.c)</td>
                                    <td>Todos los datos</td>
                                    <td>Según normativa aplicable</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            <!-- Data sharing -->
            <h3 class="section-title">4. Compartición de datos</h3>
            <div class="ps-3">
                <p><strong>Principio general:</strong> No compartimos tus datos personales con terceros, excepto en los siguientes casos:</p>
                <ul>
                    <li><strong>Miembros del proyecto:</strong> Datos de trabajo compartidos con otros miembros del mismo proyecto</li>
                    <li><strong>Obligaciones legales:</strong> Cuando sea requerido por autoridades competentes</li>
                    <li><strong>Consentimiento explícito:</strong> Cuando hayas dado tu consentimiento específico</li>
                </ul>
                <p><strong>Transferencias internacionales:</strong> Todos los datos se procesan dentro de la Unión Europea.</p>
            </div>

            <!-- Data retention -->
            <h3 class="section-title">5. Conservación de datos</h3>
            <div class="ps-3">
                <ul>
                    <li><strong>Datos de cuenta activa:</strong> Mientras la cuenta esté activa</li>
                    <li><strong>Datos laborales:</strong> 3 años después de la finalización del proyecto</li>
                    <li><strong>Logs de seguridad:</strong> 1 año para fines de seguridad</li>
                    <li><strong>Datos con consentimiento retirado:</strong> Eliminación inmediata, salvo obligación legal</li>
                </ul>
                <p><strong>Eliminación automática:</strong> El sistema elimina automáticamente los datos cuando expira el período de retención.</p>
            </div>

            <!-- Your rights -->
            <h3 class="section-title">6. Tus derechos bajo el GDPR</h3>
            <div class="ps-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card rights-card border-primary mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-eye"></i> Derecho de acceso (Art. 15)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a obtener información sobre el tratamiento de tus datos personales y acceso a los mismos.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-primary">Acceder a mis datos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rights-card border-warning mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-edit"></i> Derecho de rectificación (Art. 16)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a obtener la rectificación de los datos personales inexactos que te conciernan.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-warning">Corregir datos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rights-card border-danger mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-trash"></i> Derecho al olvido (Art. 17)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a obtener la supresión de los datos personales cuando concurran determinadas circunstancias.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-danger">Eliminar datos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rights-card border-info mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-ban"></i> Derecho a la limitación (Art. 18)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a obtener la limitación del tratamiento cuando se cumplan determinadas condiciones.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-info">Limitar procesamiento</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rights-card border-success mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-exchange-alt"></i> Derecho a la portabilidad (Art. 20)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a recibir los datos personales en un formato estructurado, de uso común y lectura mecánica.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-success">Exportar datos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rights-card border-secondary mb-3">
                            <div class="card-header">
                                <strong><i class="fas fa-hand-paper"></i> Derecho de oposición (Art. 21)</strong>
                            </div>
                            <div class="card-body">
                                <p>Derecho a oponerte al tratamiento de datos personales por motivos relacionados con tu situación particular.</p>
                                <a href="gdpr.php" class="btn btn-sm btn-secondary">Oponerse</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security measures -->
            <h3 class="section-title">7. Medidas de seguridad</h3>
            <div class="ps-3">
                <p>Implementamos medidas técnicas y organizativas apropiadas para garantizar la seguridad de tus datos:</p>
                <ul>
                    <li><strong>Cifrado:</strong> Las contraseñas se almacenan con hash seguro (bcrypt)</li>
                    <li><strong>Conexiones seguras:</strong> Uso de HTTPS para todas las comunicaciones</li>
                    <li><strong>Control de acceso:</strong> Autenticación requerida y control de sesiones</li>
                    <li><strong>Logs de auditoría:</strong> Registro de todas las actividades relacionadas con datos personales</li>
                    <li><strong>Copias de seguridad:</strong> Respaldos regulares con cifrado</li>
                    <li><strong>Minimización de datos:</strong> Solo recopilamos los datos estrictamente necesarios</li>
                </ul>
            </div>

            <!-- Cookies -->
            <h3 class="section-title">8. Cookies y tecnologías similares</h3>
            <div class="ps-3">
                <p>Utilizamos las siguientes cookies:</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Finalidad</th>
                                <th>Duración</th>
                                <th>Base legal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cookies de sesión</td>
                                <td>Mantener la sesión del usuario autenticado</td>
                                <td>Hasta cerrar navegador</td>
                                <td>Estrictamente necesarias</td>
                            </tr>
                            <tr>
                                <td>Cookies de seguridad</td>
                                <td>24 horas</td>
                                <td>Estrictamente necesarias</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p><strong>Control de cookies:</strong> Puedes configurar tu navegador para rechazar cookies, aunque esto puede afectar al funcionamiento del sistema.</p>
            </div>

            <!-- Breach notification -->
            <h3 class="section-title">9. Notificación de violaciones de seguridad</h3>
            <div class="ps-3">
                <p>En caso de violación de seguridad que pueda entrañar un alto riesgo para tus derechos y libertades:</p>
                <ul>
                    <li><strong>Notificación a autoridades:</strong> Informaremos a la autoridad de control en un plazo de 72 horas</li>
                    <li><strong>Comunicación al interesado:</strong> Te notificaremos sin dilación indebida si existe alto riesgo</li>
                    <li><strong>Medidas adoptadas:</strong> Información sobre las medidas adoptadas para abordar la violación</li>
                </ul>
            </div>

            <!-- Contact and complaints -->
            <h3 class="section-title">10. Contacto y reclamaciones</h3>
            <div class="ps-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <h5 class="text-primary mb-3">Contacto para ejercer derechos</h5>
                            <address>
                                <strong>Marcos Núñez Fernández</strong><br>
                                Email: privacy@markostech.es<br>
                                Respuesta: Máximo 1 mes
                            </address>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-info">
                            <h5 class="text-primary mb-3">Autoridad de control</h5>
                            <address>
                                <strong>Agencia Española de Protección de Datos</strong><br>
                                Web: <a href="https://www.aepd.es" target="_blank" class="text-primary">www.aepd.es</a><br>
                                Teléfono: 901 100 099
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Updates -->
            <h3 class="section-title">11. Actualizaciones de esta política</h3>
            <div class="ps-3">
                <p>Esta política de privacidad puede actualizarse para reflejar cambios en nuestras prácticas de tratamiento de datos o en la legislación aplicable.</p>
                <p><strong>Última actualización:</strong> 1 de junio de 2025</p>
                <p><strong>Notificación de cambios:</strong> Te notificaremos por email cualquier cambio significativo en esta política.</p>
            </div>

            <!-- Legal basis summary -->
            <div class="highlight-box">
                <h4 class="text-primary mb-3">
                    <i class="fas fa-balance-scale me-2"></i>Resumen de bases legales
                </h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                            <h6>Consentimiento</h6>
                            <small>Registro de usuario, marketing</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-file-contract fa-2x text-info mb-2"></i>
                            <h6>Ejecución de contrato</h6>
                            <small>Gestión de horas, proyectos</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                            <h6>Interés legítimo</h6>
                            <small>Seguridad, prevención fraude</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="login.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Volver al sistema
                </a>
                <a href="gdpr.php" class="back-btn ms-2">
                    <i class="fas fa-user-shield"></i>
                    Gestionar mis datos GDPR
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Language switching functionality
        function setLanguage(lang) {
            const htmlElement = document.getElementById('html-root');
            const enBtn = document.getElementById('btn-en');
            const esBtn = document.getElementById('btn-es');
            
            // Update HTML lang attribute
            htmlElement.setAttribute('lang', lang);
            
            // Update button states
            if (lang === 'en') {
                enBtn.classList.add('active');
                esBtn.classList.remove('active');
                // Update page title
                document.title = 'Privacy Policy - Traballa Tracker';
            } else {
                esBtn.classList.add('active');
                enBtn.classList.remove('active');
                // Update page title
                document.title = 'Política de Privacidad - Traballa Tracker';
            }
            
            // Store preference in localStorage
            localStorage.setItem('preferredLanguage', lang);
        }
        
        // Load preferred language on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('preferredLanguage');
            const browserLang = navigator.language.startsWith('es') ? 'es' : 'en';
            const defaultLang = savedLang || browserLang;
            
            setLanguage(defaultLang);
        });
    </script>
</body>
</html>
