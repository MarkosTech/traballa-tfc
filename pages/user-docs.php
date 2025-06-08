<?php
/**
 * Traballa - User Documentation
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

if (!defined('INDEX_EXEC')) {
    exit('Direct access not allowed.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación de usuario - Traballa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
</head>
<body>

<div class="container-fluid">
        
        <!-- Header -->
        <div class="row mb-4 print-hidden">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">
                            Documentación de usuario
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Table of Contents Sidebar -->
            <div class="col-lg-3 col-md-4 print-hidden" id="tableOfContents">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Tabla de contenidos
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="#getting-started">
                                <i class="fas fa-rocket me-2"></i>Primeros pasos
                            </a>
                            <a class="nav-link" href="#time-tracking">
                                <i class="fas fa-clock me-2"></i>Seguimiento de tiempo
                            </a>
                            <a class="nav-link" href="#project-management">
                                <i class="fas fa-project-diagram me-2"></i>Gestión de proyectos
                            </a>
                            <a class="nav-link" href="#kanban-boards">
                                <i class="fas fa-columns me-2"></i>Tableros kanban
                            </a>
                            <a class="nav-link" href="#calendar-events">
                                <i class="fas fa-calendar-alt me-2"></i>Calendario y eventos
                            </a>
                            <a class="nav-link" href="#reports-analytics">
                                <i class="fas fa-chart-bar me-2"></i>Informes y análisis
                            </a>
                            <a class="nav-link" href="#productivity-tools">
                                <i class="fas fa-brain me-2"></i>Herramientas de productividad
                            </a>
                            <a class="nav-link" href="#account-settings">
                                <i class="fas fa-cogs me-2"></i>Cuenta y configuración
                            </a>
                            <a class="nav-link" href="#troubleshooting-faq">
                                <i class="fas fa-question-circle me-2"></i>Solución de problemas y FAQ
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Documentation Content -->
            <div class="col-lg-9 col-md-8" id="documentationContent">
                <div class="documentation-container">

                <!-- Print-only elements that will be dynamically created -->
                <div class="print-only" style="display: none;">
                    <!-- Online version notice -->
                    <div class="print-notice">
                        <h3>📱 Versión Digital Mejorada Disponible</h3>
                        <p>Esta documentación está disponible en una versión digital mejorada con:</p>
                        <p>• Búsqueda interactiva • Navegación optimizada • Contenido actualizado • Enlaces activos</p>
                        <p class="url">https://traballa.me/user-docs</p>
                        <p style="font-size: 9pt; color: #666; margin-top: 0.8rem;">
                            Accede desde cualquier dispositivo para una experiencia de consulta superior
                        </p>
                    </div>
                    
                    <!-- Print Index -->
                    <div class="print-index">
                        <h2 style="text-align: center; margin: 2rem 0 1.5rem 0; border-bottom: 2px solid #333; padding-bottom: 0.5rem;">
                            📋 Índice de Contenidos
                        </h2>
                        <div class="index-content" style="columns: 2; column-gap: 2rem; margin-bottom: 2rem;">
                            <div class="index-section">
                                <h4>1. Primeros pasos</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Creación de cuenta y acceso</li>
                                    <li>• Configuración inicial del perfil</li>
                                    <li>• Navegación por la interfaz</li>
                                    <li>• Configuración de preferencias</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>2. Seguimiento de tiempo</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Inicio y parada de temporizadores</li>
                                    <li>• Gestión de descansos</li>
                                    <li>• Categorización de actividades</li>
                                    <li>• Técnica Pomodoro</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>3. Gestión de proyectos</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Creación y configuración</li>
                                    <li>• Gestión de miembros</li>
                                    <li>• Asignación de tareas</li>
                                    <li>• Seguimiento del progreso</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>4. Tableros kanban</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Configuración de tableros</li>
                                    <li>• Gestión de columnas</li>
                                    <li>• Creación y movimiento de tarjetas</li>
                                    <li>• Colaboración en equipo</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>5. Calendario y eventos</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Visualización de calendarios</li>
                                    <li>• Programación de eventos</li>
                                    <li>• Gestión de recordatorios</li>
                                    <li>• Sincronización externa</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>6. Informes y análisis</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Generación de informes</li>
                                    <li>• Análisis de productividad</li>
                                    <li>• Métricas de tiempo</li>
                                    <li>• Exportación de datos</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>7. Herramientas de productividad</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Técnicas de trabajo</li>
                                    <li>• Gestión de interrupciones</li>
                                    <li>• Optimización del tiempo</li>
                                    <li>• Métricas avanzadas</li>
                                </ul>
                            </div>
                            
                            <div class="index-section">
                                <h4>8. Cuenta y configuración</h4>
                                <ul style="margin-left: 1rem; list-style: none;">
                                    <li>• Gestión del perfil</li>
                                    <li>• Configuración de seguridad</li>
                                    <li>• Preferencias del sistema</li>
                                    <li>• Gestión de organizaciones</li>
                                </ul>
                            </div>
                        </div>
                        <hr style="margin: 2rem 0; border-top: 1px solid #333;">
                    </div>
                </div>

                    <!-- Getting Started -->
                    <?php include __DIR__ . '/../user_docs/getting-started.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Time Tracking -->
                    <?php include __DIR__ . '/../user_docs/time-tracking.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Project Management -->
                    <?php include __DIR__ . '/../user_docs/project-management.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Kanban Boards -->
                    <?php include __DIR__ . '/../user_docs/kanban-boards.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Calendar & Events -->
                    <?php include __DIR__ . '/../user_docs/calendar-events.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Reports & Analytics -->
                    <?php include __DIR__ . '/../user_docs/reports-analytics.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Productivity Tools -->
                    <?php include __DIR__ . '/../user_docs/productivity-tools.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Account & Settings -->
                    <?php include __DIR__ . '/../user_docs/account-settings.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Troubleshooting & FAQ -->
                    <?php include __DIR__ . '/../user_docs/troubleshooting-faq.php'; ?>
                </div>
            </div>
        </div>
</div>

<!-- Back to Top Button -->
<button type="button" class="btn btn-primary position-fixed print-hidden" id="backToTop" style="bottom: 20px; right: 20px; z-index: 1030; display: none;">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
/* Básico */
body {
    background-color: #f8f9fa;
}

.container-fluid {
    background: white;
    margin: 1rem auto;
    max-width: 95%;
}

/* Header */
.row.mb-4 {
    background-color: #007bff;
    color: white;
    margin: 0 -15px 2rem -15px;
    padding: 2rem;
}

/* Tabla de contenidos */
#tableOfContents .nav-link:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

/* Responsive */
@media (max-width: 767.98px) {
    #tableOfContents {
        position: relative !important;
        width: 100% !important;
        margin-bottom: 2rem;
    }
    
    .sticky-top {
        position: relative !important;
    }
}

/* Print styles - Enhanced */
@media print {
    @page {
        size: A4;
        margin: 15mm;
        
        /* Header y Footer en cada página */
        @top-center {
            content: "Traballa - Documentación de Usuario";
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #666;
            padding-bottom: 5mm;
            border-bottom: 0.5pt solid #ddd;
        }
    }
    
    * {
        color: black !important;
        background: white !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        line-height: 1.4;
        color: black;
        background: white;
    }
    
    /* Hide navigation and interactive elements */
    .print-hidden,
    #tableOfContents,
    #backToTop,
    .btn,
    .search-container,
    .breadcrumb,
    .navbar,
    .sidebar {
        display: none !important;
    }
    
    /* Show print-only elements */
    .print-only {
        display: block !important;
    }
    
    .print-notice {
        display: block !important;
        border: 2px solid #333;
        padding: 1rem;
        margin-bottom: 1.5rem;
        text-align: center;
        font-size: 10pt;
    }
    
    .print-notice h3 {
        margin: 0 0 0.5rem 0;
        font-size: 12pt;
        font-weight: bold;
    }
    
    .print-notice .url {
        font-weight: bold;
        text-decoration: underline;
    }
    
    /* Print Index Styles */
    .print-index {
        display: block !important;
        page-break-after: always !important;
        margin-bottom: 2rem;
    }
    
    .print-index h2 {
        font-size: 14pt !important;
        font-weight: bold !important;
        text-align: center !important;
        margin: 1.5rem 0 1rem 0 !important;
        border-bottom: 2px solid #333 !important;
        padding-bottom: 0.5rem !important;
    }
    
    .index-content {
        columns: 2 !important;
        column-gap: 2rem !important;
        column-fill: balance !important;
    }
    
    .index-section {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
        margin-bottom: 1rem !important;
    }
    
    .index-section h4 {
        font-size: 11pt !important;
        font-weight: bold !important;
        margin: 0.5rem 0 0.3rem 0 !important;
        color: #333 !important;
    }
    
    .index-section ul {
        margin: 0.3rem 0 0.8rem 1rem !important;
        padding: 0 !important;
        list-style: none !important;
    }
    
    .index-section li {
        font-size: 9pt !important;
        margin-bottom: 0.2rem !important;
        padding-left: 0 !important;
        color: #555 !important;
    }
    
    /* Layout */
    .container-fluid, .row, .col-lg-9, .col-md-8 {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Typography - Mejorada */
    h1 { 
        font-size: 16pt; 
        margin: 1rem 0 0.5rem 0;
        page-break-after: avoid;
        font-weight: bold;
        color: #333 !important;
        border-bottom: 1pt solid #333;
        padding-bottom: 0.3rem;
    }
    h2 { 
        font-size: 14pt; 
        margin: 0.8rem 0 0.4rem 0;
        page-break-after: avoid;
        font-weight: bold;
        color: #444 !important;
    }
    h3 { 
        font-size: 12pt; 
        margin: 0.6rem 0 0.3rem 0;
        page-break-after: avoid;
        font-weight: bold;
        color: #555 !important;
    }
    h4, h5, h6 { 
        font-size: 11pt; 
        margin: 0.4rem 0 0.2rem 0;
        page-break-after: avoid;
        font-weight: bold;
        color: #666 !important;
    }
    
    p { 
        margin-bottom: 0.6rem;
        text-align: justify;
        orphans: 3;
        widows: 3;
    }
    
    /* Mejorar la legibilidad del texto */
    .lead {
        font-size: 12pt !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        margin-bottom: 1rem !important;
    }
    
    /* Destacar texto importante */
    strong, b {
        font-weight: bold !important;
        color: #333 !important;
    }
    
    em, i {
        font-style: italic !important;
        color: #444 !important;
    }
    
    /* Cards and sections - Mejor manejo de contenido complejo */
    .card, .documentation-section {
        border: none !important;
        margin-bottom: 0.8rem !important;
        padding: 0.8rem !important;
        page-break-inside: avoid !important;
        background: white !important;
    }
    
    /* Numeración automática de secciones */
    body {
        counter-reset: section-counter;
    }
    
    .documentation-section h1:before {
        counter-increment: section-counter;
        content: counter(section-counter) ". ";
        font-weight: bold;
        color: #007bff !important;
    }
    
    /* Mejores estilos para elementos destacados */
    .alert {
        border: 1pt solid #333 !important;
        padding: 0.7rem !important;
        margin: 0.6rem 0 !important;
        page-break-inside: avoid !important;
        background: #f8f9fa !important;
    }
    
    .alert-info {
        border-left: 3pt solid #17a2b8 !important;
        background: #e7f7ff !important;
    }
    
    .alert-warning {
        border-left: 3pt solid #ffc107 !important;
        background: #fff8e1 !important;
    }
    
    .alert-success {
        border-left: 3pt solid #28a745 !important;
        background: #e8f5e8 !important;
    }
    
    .alert-danger {
        border-left: 3pt solid #dc3545 !important;
        background: #ffeaea !important;
    }
    
    /* Force individual cards to stay together */
    .card {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
    
    /* Handle Bootstrap grid system for print */
    .row {
        margin: 0 !important;
        display: block !important;
        page-break-inside: auto !important;
    }
    
    .col-md-6, .col-md-12, .col-lg-6, .col-lg-12,
    [class*="col-"] {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 0 0.5rem 0 !important;
        display: block !important;
        float: none !important;
    }
    
    /* Keep section headers together with some content */
    .documentation-section h1,
    .documentation-section h2,
    .section-title {
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
        margin-bottom: 0.8rem !important;
    }
    
    /* Ensure section headers don't appear alone at bottom of page */
    .documentation-section h1 + *,
    .documentation-section h2 + *,
    .section-title + *,
    .documentation-section h3 + * {
        page-break-before: avoid !important;
    }
    
    /* Better spacing between cards */
    .card + .card {
        margin-top: 0.6rem !important;
    }
    
    .card-header {
        background: #f0f0f0 !important;
        border-bottom: 1px solid #333 !important;
        font-weight: bold !important;
        padding: 0.3rem !important;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    /* Alerts with better break handling */
    .alert {
        border: 1px solid #333 !important;
        padding: 0.5rem !important;
        margin: 0.4rem 0 !important;
        page-break-inside: avoid !important;
    }
    
    /* Special handling for small alerts */
    .alert-sm, .alert.alert-sm {
        font-size: 9pt !important;
        padding: 0.3rem !important;
        margin: 0.2rem 0 !important;
    }
    
    /* Listas mejoradas con mejor espaciado y formato */
    ul, ol {
        margin: 0.8rem 0 !important;
        padding-left: 2rem !important;
        page-break-inside: auto !important;
    }
    
    li {
        margin-bottom: 0.5rem !important;
        page-break-inside: avoid !important;
        line-height: 1.4 !important;
    }
    
    /* Listas anidadas */
    ul ul, ol ol, ul ol, ol ul {
        margin: 0.3rem 0 !important;
        padding-left: 1.5rem !important;
    }
    
    /* Elementos de lista con iconos (convertir a texto) */
    li:before {
        font-weight: bold;
        margin-right: 0.5rem;
    }
    
    /* Numeración personalizada para listas ordenadas */
    ol {
        counter-reset: item;
    }
    
    ol > li {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    ol > li:before {
        content: counter(item) ". ";
        counter-increment: item;
        font-weight: bold;
        margin-right: 0.5rem;
    }
    
    /* Checkboxes simulados para listas de tareas */
    .task-list li:before {
        content: "☐ ";
        font-size: 12pt;
        margin-right: 0.5rem;
    }
    
    .task-list .completed:before {
        content: "☑ ";
    }
    
    /* Tablas mejoradas con mejor formato */
    table { 
        border-collapse: collapse; 
        width: 100%; 
        margin: 0.8rem 0; 
        font-size: 10pt;
        page-break-inside: avoid;
        border: 1pt solid #333 !important;
    }
    
    th, td { 
        border: 0.5pt solid #666 !important; 
        padding: 0.5rem !important; 
        text-align: left; 
        vertical-align: top;
    }
    
    th { 
        background: #f0f0f0 !important; 
        font-weight: bold !important;
        color: #333 !important;
        text-align: center;
    }
    
    /* Alternar colores de filas */
    tr:nth-child(even) td {
        background: #f9f9f9 !important;
    }
    
    /* Tabla de contenidos especial */
    .table-of-contents {
        border: 2pt solid #333 !important;
        background: #f8f9fa !important;
        margin: 1rem 0 !important;
    }
    
    .table-of-contents th {
        background: #e9ecef !important;
        text-align: center !important;
        font-size: 11pt !important;
    }
    
    /* Bloques de código mejorados */
    pre, code {
        page-break-inside: avoid;
        border: 1pt solid #ccc !important;
        padding: 0.5rem !important;
        background: #f8f8f8 !important;
        font-family: 'Courier New', monospace !important;
        font-size: 9pt !important;
        line-height: 1.3 !important;
        color: #333 !important;
    }
    
    pre {
        border-radius: 0 !important;
        margin: 0.8rem 0 !important;
        padding: 0.8rem !important;
        white-space: pre-wrap !important;
        word-wrap: break-word !important;
        border-left: 3pt solid #007bff !important;
    }
    
    code {
        padding: 0.2rem 0.4rem !important;
        border-radius: 0 !important;
        background: #f0f0f0 !important;
        font-weight: bold !important;
    }
    
    /* Bloques de código con título */
    .code-block {
        border: 1pt solid #333 !important;
        margin: 0.8rem 0 !important;
        page-break-inside: avoid !important;
    }
    
    .code-block-title {
        background: #333 !important;
        color: white !important;
        padding: 0.3rem 0.5rem !important;
        font-size: 9pt !important;
        font-weight: bold !important;
        margin: 0 !important;
    }
    
    /* Resaltado de sintaxis simulado */
    .keyword { color: #0066cc !important; font-weight: bold !important; }
    .string { color: #009900 !important; }
    .comment { color: #666 !important; font-style: italic !important; }
    .number { color: #cc6600 !important; }
    
    /* Use HR elements as page breaks */
    hr.my-5 {
        display: block !important;
        page-break-before: always !important;
        page-break-after: avoid !important;
        border: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        visibility: hidden !important;
    }
    
    /* Hide other HR elements */
    hr:not(.my-5) {
        display: none !important;
    }
    
    .my-5, .mb-5, .mt-5 {
        margin: 0.5rem 0 !important;
    }
    
    /* Ocultar completamente todos los iconos en impresión */
    .fas, .far, .fab, .fal, .fad, [class*="fa-"], i {
        display: none !important;
    }
    
    /* Expandir FAQ y acordeones en impresión */
    .accordion-body {
        display: block !important;
        height: auto !important;
        overflow: visible !important;
        padding: 0.8rem !important;
    }
    
    .accordion-collapse {
        display: block !important;
        height: auto !important;
    }
    
    .accordion-collapse.collapse:not(.show) {
        display: block !important;
        height: auto !important;
    }
    
    .accordion-button {
        display: block !important;
        background: #f8f9fa !important;
        border: none !important;
        font-weight: bold !important;
        padding: 0.5rem !important;
        color: #333 !important;
        text-align: left !important;
    }
    
    .accordion-button:after {
        display: none !important;
    }
    
    .accordion-item {
        border: 1pt solid #ddd !important;
        margin-bottom: 0.5rem !important;
        page-break-inside: avoid !important;
    }
    
    .accordion-header {
        margin-bottom: 0 !important;
    }
    
    /* Mejorar la numeración de preguntas FAQ */
    .accordion {
        counter-reset: faq-counter;
    }
    
    .accordion-item {
        counter-increment: faq-counter;
    }
    
    .accordion-button:before {
        content: "Q" counter(faq-counter) ": ";
        font-weight: bold;
        margin-right: 0.5rem;
        color: #007bff !important;
    }
    
    /* Resaltar respuestas */
    .accordion-body p:first-child {
        font-weight: bold !important;
        color: #333 !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Ajustes finales para optimización */
    
    /* Mejor handling de elementos flotantes */
    .float-left, .float-start {
        float: none !important;
        display: block !important;
        margin-bottom: 0.5rem !important;
    }
    
    .float-right, .float-end {
        float: none !important;
        display: block !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Clearfix para elementos flotantes */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    
    /* Elementos fijos se vuelven relativos */
    .fixed-top, .fixed-bottom, .sticky-top {
        position: relative !important;
        top: auto !important;
        bottom: auto !important;
    }
    
    /* Optimización de memoria y rendimiento */
    * {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Asegurar que el contenido no se corte */
    html, body {
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
    }
    
    /* Últimos ajustes para evitar contenido huérfano */
    p, div, li {
        orphans: 3 !important;
        widows: 3 !important;
    }
    
    /* Espaciado final entre secciones principales */
    .documentation-section:not(:last-child) {
        margin-bottom: 2rem !important;
        padding-bottom: 1rem !important;
        border-bottom: 1pt solid #ddd !important;
    }
    
    /* Página de título mejorada */
    .print-title-page {
        page-break-after: always !important;
        text-align: center !important;
        padding: 4rem 2rem !important;
        border: 2pt solid #333 !important;
        margin-bottom: 2rem !important;
    }
    
    .print-title-page h1 {
        font-size: 24pt !important;
        margin-bottom: 2rem !important;
        color: #007bff !important;
    }
    
    .print-title-page .subtitle {
        font-size: 16pt !important;
        margin-bottom: 3rem !important;
        color: #666 !important;
    }
    
    /* Footer personalizado para cada página */
    @page {
        @bottom-left {
            content: "© 2025 Traballa";
            font-size: 8pt;
            color: #666;
        }
    }
}
    
    /* Badges y etiquetas */
    .badge {
        border: 1pt solid #333 !important;
        padding: 0.2rem 0.4rem !important;
        font-size: 8pt !important;
        font-weight: bold !important;
        background: #f0f0f0 !important;
        color: #333 !important;
        border-radius: 0 !important;
    }
    
    .badge-primary { background: #e3f2fd !important; border-color: #1976d2 !important; }
    .badge-success { background: #e8f5e9 !important; border-color: #388e3c !important; }
    .badge-warning { background: #fff3e0 !important; border-color: #f57c00 !important; }
    .badge-danger { background: #ffebee !important; border-color: #d32f2f !important; }
    
    /* Blockquotes */
    blockquote {
        border-left: 3pt solid #007bff !important;
        padding-left: 1rem !important;
        margin: 1rem 0 !important;
        font-style: italic !important;
        background: #f8f9ff !important;
        padding: 0.8rem !important;
        page-break-inside: avoid !important;
    }
    
    blockquote p {
        margin-bottom: 0.5rem !important;
    }
    
    blockquote cite {
        font-size: 9pt !important;
        color: #666 !important;
        font-style: normal !important;
    }
    
    /* Pasos numerados */
    .step-list {
        counter-reset: step-counter;
        margin: 1rem 0 !important;
    }
    
    .step-list li {
        counter-increment: step-counter;
        position: relative;
        padding-left: 2.5rem !important;
        margin-bottom: 1rem !important;
        page-break-inside: avoid !important;
    }
    
    .step-list li:before {
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 0;
        background: #007bff !important;
        color: white !important;
        width: 1.8rem;
        height: 1.8rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 10pt;
    }
    
    /* Elementos de navegación breadcrumb */
    .breadcrumb {
        background: #f8f9fa !important;
        border: 1pt solid #ddd !important;
        padding: 0.5rem 1rem !important;
        margin-bottom: 1rem !important;
        font-size: 9pt !important;
    }
    
    .breadcrumb-item:before {
        content: "→ ";
        margin-right: 0.3rem;
        color: #666 !important;
    }
    
    .breadcrumb-item:first-child:before {
        content: "";
    }
    
    /* Elementos de estado y progreso */
    .progress {
        height: 8pt !important;
        background: #f0f0f0 !important;
        border: 1pt solid #ddd !important;
        margin: 0.5rem 0 !important;
    }
    
    .progress-bar {
        background: #333 !important;
        color: white !important;
        font-size: 8pt !important;
        line-height: 8pt !important;
    }
    
    /* Tooltips convertidos a texto */
    [data-bs-toggle="tooltip"]:after {
        content: " (" attr(title) ")";
        font-size: 8pt;
        color: #666;
        font-style: italic;
    }
    
    /* Acordeones optimizados */
    .accordion-item {
        border: 1pt solid #ddd !important;
        margin-bottom: 0.5rem !important;
        page-break-inside: avoid !important;
    }
    
    .accordion-header {
        background: #f8f9fa !important;
        padding: 0.5rem !important;
        font-weight: bold !important;
        border-bottom: 1pt solid #ddd !important;
    }
    
    .accordion-body {
        padding: 0.8rem !important;
        display: block !important; /* Mostrar todo el contenido */
    }
    
    /* Eliminar elementos interactivos de acordeones */
    .accordion-button {
        pointer-events: none !important;
    }
    
    .accordion-button:after {
        display: none !important;
    }
    
    /* Pestañas convertidas a secciones */
    .nav-tabs {
        display: none !important;
    }
    
    .tab-content .tab-pane {
        display: block !important;
        opacity: 1 !important;
        border-top: 2pt solid #007bff !important;
        padding-top: 0.8rem !important;
        margin-bottom: 1rem !important;
        page-break-inside: avoid !important;
    }
    
    .tab-pane:before {
        content: attr(aria-labelledby);
        display: block;
        font-weight: bold;
        font-size: 11pt;
        margin-bottom: 0.5rem;
        color: #007bff !important;
        text-transform: capitalize;
    }
    
    /* Elementos multimedia */
    video, audio, iframe {
        display: none !important;
    }
    
    /* Placeholder untuk multimedia */
    video:after, iframe:after {
        content: "📹 Contenido multimedia disponible en la versión digital";
        display: block !important;
        padding: 1rem !important;
        border: 2pt dashed #ccc !important;
        text-align: center !important;
        font-style: italic !important;
        color: #666 !important;
        background: #f9f9f9 !important;
    }
    
    /* Formularios optimizados */
    .form-control, .form-select, input, textarea, select {
        border: 1pt solid #333 !important;
        padding: 0.3rem !important;
        background: white !important;
        font-size: 10pt !important;
    }
    
    .form-label {
        font-weight: bold !important;
        color: #333 !important;
        margin-bottom: 0.3rem !important;
    }
    
    /* Botones convertidos a texto */
    .btn {
        border: 1pt solid #333 !important;
        padding: 0.3rem 0.6rem !important;
        background: #f0f0f0 !important;
        color: #333 !important;
        font-weight: bold !important;
        text-decoration: none !important;
        display: inline-block !important;
        margin: 0.2rem !important;
    }
    
    .btn-primary { background: #e3f2fd !important; border-color: #1976d2 !important; }
    .btn-success { background: #e8f5e9 !important; border-color: #388e3c !important; }
    .btn-warning { background: #fff3e0 !important; border-color: #f57c00 !important; }
    .btn-danger { background: #ffebee !important; border-color: #d32f2f !important; }
    
    /* Spinner y loading elements */
    .spinner-border, .spinner-grow {
        display: none !important;
    }
    
    /* Eliminar efectos visuales */
    * {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }
    
    /* Prevent awkward page breaks */
    h1, h2, h3, h4, h5, h6 {
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
    }
    
    /* Allow content to flow naturally between pages */
    .documentation-section {
        page-break-inside: auto !important;
    }
    
    /* Keep small elements together */
    .alert, .badge, pre, code, blockquote {
        page-break-inside: avoid !important;
    }
    
    /* Smart table handling */
    table {
        page-break-inside: auto !important;
        margin: 0.8rem 0 !important;
    }
    
    /* Keep table headers with at least one row */
    thead, thead + tbody tr:first-child {
        page-break-after: avoid !important;
    }
    
    /* Avoid single table rows at page boundaries */
    tr {
        page-break-inside: avoid !important;
    }
    
    /* Improved spacing and break control */
    .documentation-section > .row:first-child {
        page-break-after: avoid !important;
    }
    
    /* Prevent orphaned content */
    .card-body > *:last-child {
        margin-bottom: 0 !important;
    }
    
    /* Better handling of nested content */
    .card .alert {
        margin: 0.3rem 0 !important;
    }
    
    .card ol, .card ul {
        margin: 0.4rem 0 !important;
    }
    
    /* Ensure proper spacing around major sections */
    .documentation-section {
        margin-bottom: 1rem !important;
    }
    
    /* Handle complex card layouts better */
    .row > [class*="col-"]:first-child .card {
        page-break-before: avoid !important;
    }
    
    .row > [class*="col-"]:last-child .card {
        page-break-after: avoid !important;
    }

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Code blocks */
.documentation-section code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    border: 1px solid #e9ecef;
}

.documentation-section pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    overflow-x: auto;
}

/* Accordion improvements */
.accordion-button {
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    background-color: #e7f3ff;
    color: #0066cc;
}

/* Badge enhancements */
.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}

/* List improvements */
.documentation-section ol,
.documentation-section ul {
    padding-left: 1.5rem;
}

.documentation-section li {
    margin-bottom: 0.5rem;
}

/* Link styles */
.documentation-section a {
    color: #007bff;
    text-decoration: none;
}

.documentation-section a:hover {
    text-decoration: underline;
}

/* Search highlighting */
.search-highlight {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    padding: 0.1rem 0.2rem;
    border-radius: 0.2rem;
    font-weight: bold;
    color: #856404;
    border: 1px solid #f39c12;
}

/* Enhanced search container */
.search-container .card-body {
    background: linear-gradient(135deg, #f8f9ff 0%, #e6f3ff 100%);
    border-left: 4px solid #007bff;
}

.search-container .input-group-text {
    border: none;
}

.search-container .form-control {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-container .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Print button styling */
.btn-group .btn {
    margin-left: 0.5rem;
}

/* Enhanced responsive behavior */
@media (max-width: 991.98px) {
    .container-fluid {
        margin: 1rem auto;
        max-width: 98%;
    }
    
    .documentation-section {
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 575.98px) {
    .row.mb-4 {
        padding: 1.5rem 1rem 1rem 1rem;
    }
    
    .row.mb-4 .btn-group {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .row.mb-4 .btn {
        margin-left: 0;
    }
}

/* Smooth transitions */
.documentation-section,
.card,
.alert {
    transition: all 0.3s ease;
}

/* Loading animation for search */
.search-container .input-group-text i {
    transition: transform 0.3s ease;
}

.search-container .form-control:focus + .search-container .input-group-text i {
    transform: scale(1.1);
}

/* Better print preview */
@media screen {
    .print-only {
        display: none !important;
    }
    
    .print-notice {
        display: none !important;
    }
}

/* Focus indicators for accessibility */
button:focus,
.btn:focus,
.form-control:focus,
.nav-link:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}

/* Improved table styling for screen */
@media screen {
    table {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    th {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    tr:hover td {
        background-color: #f8f9ff;
    }
}

/* Code block improvements for screen */
@media screen {
    pre {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    code {
        border-radius: 0.3rem;
    }
}

/* Iconos mejorados - Fallback y estilos adicionales */
@media screen {
    /* Asegurar que los iconos Font Awesome se vean correctamente */
    .fas, .far, .fab, .fal, .fad {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif !important;
        font-weight: 900;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    .far {
        font-weight: 400;
    }
    
    .fal {
        font-weight: 300;
    }
    
    .fad {
        font-weight: 900;
    }
    
    /* Estilos específicos para iconos en navegación */
    .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 8px;
        font-size: 14px;
        vertical-align: middle;
    }
    
    /* Iconos en el botón de volver arriba */
    #backToTop i {
        font-size: 16px;
        line-height: 1;
    }
    
    /* Iconos en tarjetas y headers */
    .card-header i {
        margin-right: 8px;
        font-size: 14px;
    }
    
    /* Fallback para iconos que no se cargan */
    .icon-fallback {
        display: inline-block;
        width: 20px;
        height: 20px;
        text-align: center;
        font-family: Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        line-height: 20px;
    }
    
    /* Estilos para iconos Unicode como fallback */
    .unicode-icon {
        font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif;
        font-style: normal;
        font-variant: normal;
        font-weight: normal;
        line-height: 1;
        margin-right: 0.5rem;
        display: inline-block;
        text-align: center;
        min-width: 1.2em;
    }
}
</style>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Simple utility scripts -->
    <script>
    // Función para el botón de volver arriba
    window.addEventListener('scroll', function() {
        const backToTopButton = document.getElementById('backToTop');
        if (backToTopButton) {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        }
    });
    
    // Funcionalidad del botón volver arriba
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        if (backToTopButton) {
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        
        // Abrir todos los acordeones por defecto
        const accordionCollapses = document.querySelectorAll('.accordion-collapse');
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        accordionCollapses.forEach(collapse => {
            collapse.classList.add('show');
            collapse.style.height = 'auto';
            collapse.style.overflow = 'visible';
        });
        
        accordionButtons.forEach(button => {
            button.classList.remove('collapsed');
            button.setAttribute('aria-expanded', 'true');
        });
    });
    </script>
</body>
</html>
