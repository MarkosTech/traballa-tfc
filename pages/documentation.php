<?php
/**
 * Traballa - Technical Documentation
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
    <title>Documentación técnica - Traballa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Mermaid for diagrams -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10.8.0/dist/mermaid.min.js"></script>
    
    <!-- Custom CSS -->
    <link href="assets/documentation/estilos_unificados.css" rel="stylesheet">
    <link href="assets/documentation/diagram-styles.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
</head>
<body>

<div class="container-fluid">
        <div class="row">
            <!-- Table of Contents Sidebar -->
            <div class="col-lg-3 col-md-4 print-hidden" id="tableOfContents">
                <div class="card toc-fixed" style="top: 0px;">
                    <div class="card-body p-0">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="#resumen">
                                <i class="fas fa-file-alt me-2"></i>Resumen del proyecto
                            </a>
                            <a class="nav-link" href="#estudio-preliminar">
                                <i class="fas fa-search me-2"></i>Estudio preliminar
                            </a>
                            <a class="nav-link main-section" href="#analisis" data-bs-toggle="collapse" data-bs-target="#analisis-submenu" aria-expanded="false">
                                <i class="fas fa-chart-line me-2"></i>Análisis
                                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
                            </a>
                            <div class="collapse" id="analisis-submenu">
                                <div class="submenu">
                                    <a class="nav-link submenu-item" href="#requisitos-funcionales">
                                        <i class="fas fa-cog me-2"></i>Requisitos funcionales
                                    </a>
                                    <a class="nav-link submenu-item" href="#requisitos-no-funcionales">
                                        <i class="fas fa-shield-alt me-2"></i>Requisitos no funcionales
                                    </a>
                                    <a class="nav-link submenu-item" href="#casos-uso">
                                        <i class="fas fa-users me-2"></i>Casos de uso
                                    </a>
                                    <a class="nav-link submenu-item" href="#normativa">
                                        <i class="fas fa-gavel me-2"></i>Normativa
                                    </a>
                                </div>
                            </div>
                            <a class="nav-link main-section" href="#diseno" data-bs-toggle="collapse" data-bs-target="#diseno-submenu" aria-expanded="false">
                                <i class="fas fa-drafting-compass me-2"></i>Diseño
                                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
                            </a>
                            <div class="collapse" id="diseno-submenu">
                                <div class="submenu">
                                    <a class="nav-link submenu-item" href="#arquitectura">
                                        <i class="fas fa-sitemap me-2"></i>Arquitectura del sistema
                                    </a>
                                    <a class="nav-link submenu-item" href="#modelo-datos">
                                        <i class="fas fa-database me-2"></i>Modelo de datos
                                    </a>
                                    <a class="nav-link submenu-item" href="#interfaces-usuario">
                                        <i class="fas fa-desktop me-2"></i>Interfaces de usuario
                                    </a>
                                    <a class="nav-link submenu-item" href="#diagramas-sistema">
                                        <i class="fas fa-project-diagram me-2"></i>Diagramas del sistema
                                    </a>
                                </div>
                            </div>
                            <a class="nav-link main-section" href="#codificacion" data-bs-toggle="collapse" data-bs-target="#codificacion-submenu" aria-expanded="false">
                                <i class="fas fa-code me-2"></i>Codificación
                                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
                            </a>
                            <div class="collapse" id="codificacion-submenu">
                                <div class="submenu">
                                    <a class="nav-link submenu-item" href="#tecnologias">
                                        <i class="fas fa-tools me-2"></i>Tecnologías utilizadas
                                    </a>
                                    <a class="nav-link submenu-item" href="#estructura-codigo">
                                        <i class="fas fa-folder-tree me-2"></i>Estructura del código
                                    </a>
                                    <a class="nav-link submenu-item" href="#plan-pruebas">
                                        <i class="fas fa-vial me-2"></i>Plan de pruebas
                                    </a>
                                </div>
                            </div>
                            <a class="nav-link" href="#planificacion">
                                <i class="fas fa-calendar-alt me-2"></i>Planificación
                            </a>
                            <a class="nav-link" href="#presupuesto">
                                <i class="fas fa-euro-sign me-2"></i>Presupuesto
                            </a>
                            <a class="nav-link" href="#conclusiones">
                                <i class="fas fa-flag-checkered me-2"></i>Conclusiones
                            </a>
                            <a class="nav-link main-section" href="#manuales" data-bs-toggle="collapse" data-bs-target="#manuales-submenu" aria-expanded="false">
                                <i class="fas fa-book-open me-2"></i>Manuales
                                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
                            </a>
                            <div class="collapse" id="manuales-submenu">
                                <div class="submenu">
                                    <a class="nav-link submenu-item" href="#manual-instalacion">
                                        <i class="fas fa-download me-2"></i>Manual de instalación
                                    </a>
                                    <a class="nav-link submenu-item" href="#manual-usuario">
                                        <i class="fas fa-user-guide me-2"></i>Manual de usuario
                                    </a>
                                </div>
                            </div>
                            <a class="nav-link" href="#despliegue">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Despliegue
                            </a>
                            <a class="nav-link" href="#bibliografia">
                                <i class="fas fa-bookmark me-2"></i>Bibliografía
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Documentation Content -->
            <div class="col-lg-9 col-md-8" id="documentationContent">
                <div class="documentation-container">

                    <!-- Portada -->
                    <?php include __DIR__ . '/../documentation/portada.php'; ?>

                    <hr class="my-5">

                    <!-- Resumen -->
                    <?php include __DIR__ . '/../documentation/resumen.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Estudio Preliminar -->
                    <?php include __DIR__ . '/../documentation/estudio-preliminar.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Análisis -->
                    <?php include __DIR__ . '/../documentation/analisis.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Diseño -->
                    <?php include __DIR__ . '/../documentation/diseno.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Codificación -->
                    <?php include __DIR__ . '/../documentation/codificacion.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Planificación -->
                    <?php include __DIR__ . '/../documentation/planificacion.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Presupuesto -->
                    <?php include __DIR__ . '/../documentation/presupuesto.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Conclusiones -->
                    <?php include __DIR__ . '/../documentation/conclusiones.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Manuales -->
                    <?php include __DIR__ . '/../documentation/manuales.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Despliegue -->
                    <?php include __DIR__ . '/../documentation/despliegue.php'; ?>
                    
                    <hr class="my-5">
                    
                    <!-- Bibliografía -->
                    <?php include __DIR__ . '/../documentation/bibliografia.php'; ?>
                </div>
            </div>
        </div>
</div>

<!-- Back to Top Button -->
<button type="button" class="btn btn-primary position-fixed print-hidden" id="backToTop" style="bottom: 20px; right: 20px; z-index: 1030; display: none;">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Back Button -->
<button type="button" class="btn btn-secondary position-fixed print-hidden" id="backButton" style="top: 20px; right: 20px; z-index: 1030;" onclick="window.history.back()">
    <i class="fas fa-arrow-left me-2"></i>Volver
</button>

<style>
/* Layout básico */
body { background-color: #f8f9fa; }

.container-fluid {
    background: white;
    margin: 1rem auto;
    max-width: 95%;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 2rem;
}

/* Tabla de contenidos */
#tableOfContents {
    position: fixed;
    top: 20px;
    left: 20px;
    width: 300px;
    height: calc(100vh - 40px);
    z-index: 1000;
}

#tableOfContents .card {
    height: 100%;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95);
}

#tableOfContents .card-body {
    max-height: 100%;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
    padding: 0.5rem 0;
}

#tableOfContents .card-body::-webkit-scrollbar { width: 6px; }
#tableOfContents .card-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
#tableOfContents .card-body::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }

#documentationContent {
    margin-left: 340px;
    width: calc(100% - 340px);
}

/* Navegación */
#tableOfContents .nav-link {
    padding: 0.75rem 1rem;
    color: #495057;
    border-radius: 8px;
    margin: 0.125rem 0.5rem;
    transition: all 0.3s ease;
}

#tableOfContents .nav-link:hover { background-color: #f8f9fa; color: #007bff; }
#tableOfContents .nav-link.active { background-color: #007bff; color: white; }

.nav-link.main-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 500;
    cursor: pointer;
}

.nav-link.main-section:hover { background-color: #e9ecef; color: #0056b3; }
.nav-link.main-section .toggle-icon { transition: transform 0.3s ease; font-size: 0.8rem; }
.nav-link.main-section[aria-expanded="true"] .toggle-icon { transform: rotate(180deg); }

/* Submenús */
.submenu {
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    margin-left: 1rem;
    padding: 0.25rem 0;
}

.submenu-item {
    padding: 0.5rem 1rem !important;
    font-size: 0.85rem !important;
    color: #6c757d !important;
    margin: 0.125rem 0.5rem 0.125rem 0 !important;
    border-radius: 0.25rem !important;
}

.submenu-item:hover { background-color: #e3f2fd !important; color: #1976d2 !important; }
.submenu-item.active { background-color: #0056b3 !important; color: white !important; }

/* Animaciones */
.collapse { transition: all 0.3s ease; }
.collapsing { transition: height 0.3s ease; }

/* Contenido */
.section { margin-bottom: 3rem; }
.section h2 {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.mermaid { text-align: center; margin: 2rem 0; }

table { width: 100%; margin: 1rem 0; }
table th { background-color: #007bff; color: white; padding: 0.75rem; }
table td { padding: 0.5rem; border-bottom: 1px solid #dee2e6; }

pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    border-left: 4px solid #007bff;
    overflow-x: auto;
}

code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-size: 0.9em;
}

/* Botones flotantes */
#backToTop {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

#backButton {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    font-size: 0.9rem;
    background-color: #6c757d;
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
}

#backButton:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
}

/* Responsive */
@media (max-width: 767.98px) {
    #tableOfContents {
        position: relative !important;
        width: 100% !important;
        height: auto !important;
        margin-bottom: 2rem;
        left: auto !important;
        top: auto !important;
    }
    
    #tableOfContents .card { height: auto !important; }
    #tableOfContents .card-body { max-height: 50vh !important; }
    #documentationContent { margin-left: 0 !important; width: 100% !important; }
    .container-fluid { margin: 0; padding: 1rem; }
    
    #backButton {
        top: 10px !important;
        right: 10px !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.8rem !important;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    #tableOfContents { width: 280px; }
    #documentationContent { margin-left: 300px; width: calc(100% - 300px); }
}

@media (min-width: 992px) and (max-width: 1199.98px) {
    #tableOfContents { width: 290px; }
    #documentationContent { margin-left: 320px; width: calc(100% - 320px); }
}

@media (max-width: 576px) {
    .submenu { margin-left: 0.5rem; }
    .submenu-item {
        padding: 0.4rem 0.8rem !important;
        font-size: 0.8rem !important;
    }
}

/* Print */
@media print {
    .print-hidden { display: none !important; }
    .container-fluid { max-width: 100% !important; margin: 0 !important; box-shadow: none !important; }
    .section { page-break-inside: avoid; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/documentation/mermaid-diagrams.js"></script>
<script src="assets/documentation/diagram-enhancer.js"></script>
<script>
    // Initialize Mermaid
    mermaid.initialize({
        startOnLoad: true,
        theme: 'default',
        securityLevel: 'loose'
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('#tableOfContents .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            // Si es un enlace de sección principal con submenú, no prevenir el comportamiento por defecto
            if (this.classList.contains('main-section')) {
                return; // Permitir que Bootstrap maneje el colapso
            }
            
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update active state
                document.querySelectorAll('#tableOfContents .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Hacer scroll al elemento activo en la tabla de contenidos
                scrollToActiveInTOC(this);
                
                // Si es un enlace de submenú, también expandir el menú padre
                if (this.classList.contains('submenu-item')) {
                    const parentCollapse = this.closest('.collapse');
                    if (parentCollapse && !parentCollapse.classList.contains('show')) {
                        const parentToggle = document.querySelector(`[data-bs-target="#${parentCollapse.id}"]`);
                        if (parentToggle) {
                            parentToggle.click();
                        }
                    }
                }
            }
        });
    });

    // Manejar clicks en secciones principales para navegar Y expandir/contraer
    document.querySelectorAll('#tableOfContents .nav-link.main-section').forEach(link => {
        link.addEventListener('click', function(e) {
            // Permitir navegación
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100); // Pequeño delay para permitir que se complete la animación del colapso
            }
        });
    });

    // Función para expandir automáticamente el submenú de la sección activa
    function expandActiveSubmenu() {
        const sections = document.querySelectorAll('.section');
        let currentSection = '';
        
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 100 && rect.bottom >= 100) {
                currentSection = section.id;
            }
        });
        
        // Expandir el submenú correspondiente si existe
        const sectionSubmenus = {
            'analisis': 'analisis-submenu',
            'diseno': 'diseno-submenu',
            'codificacion': 'codificacion-submenu',
            'manuales': 'manuales-submenu'
        };
        
        if (sectionSubmenus[currentSection]) {
            const submenu = document.getElementById(sectionSubmenus[currentSection]);
            const toggle = document.querySelector(`[data-bs-target="#${sectionSubmenus[currentSection]}"]`);
            
            if (submenu && !submenu.classList.contains('show')) {
                const collapse = new bootstrap.Collapse(submenu, {show: true});
            }
        }
    }

    // Función para hacer scroll automático en la tabla de contenidos
    function scrollToActiveInTOC(activeElement) {
        const tocContainer = document.querySelector('#tableOfContents .card-body');
        if (!tocContainer || !activeElement) return;
        
        const containerRect = tocContainer.getBoundingClientRect();
        const elementRect = activeElement.getBoundingClientRect();
        
        // Obtener posiciones relativas al contenedor
        const containerTop = tocContainer.scrollTop;
        const elementTop = activeElement.offsetTop;
        const containerHeight = tocContainer.clientHeight;
        const elementHeight = activeElement.offsetHeight;
        
        // Verificar si el elemento está fuera de la vista
        const isAbove = elementRect.top < containerRect.top + 50; // Margen superior
        const isBelow = elementRect.bottom > containerRect.bottom - 50; // Margen inferior
        
        if (isAbove || isBelow) {
            // Calcular la posición de scroll para centrar el elemento
            const scrollPosition = elementTop - (containerHeight / 2) + (elementHeight / 2);
            
            // Aplicar scroll suave
            tocContainer.scrollTo({
                top: Math.max(0, scrollPosition),
                behavior: 'smooth'
            });
        }
        
        // Actualizar indicadores de scroll después del movimiento
        setTimeout(() => {
            updateScrollIndicators();
        }, 300);
    }

    // Función para actualizar indicadores de scroll
    function updateScrollIndicators() {
        const tocContainer = document.querySelector('#tableOfContents .card-body');
        const tocCard = document.querySelector('#tableOfContents .card');
        
        if (!tocContainer || !tocCard) return;
        
        const scrollTop = tocContainer.scrollTop;
        const scrollHeight = tocContainer.scrollHeight;
        const clientHeight = tocContainer.clientHeight;
        
        // Mostrar indicador superior si hay contenido arriba
        if (scrollTop > 10) {
            tocCard.classList.add('scrollable-top');
        } else {
            tocCard.classList.remove('scrollable-top');
        }
        
        // Mostrar indicador inferior si hay contenido abajo
        if (scrollTop < scrollHeight - clientHeight - 10) {
            tocCard.classList.add('scrollable-bottom');
        } else {
            tocCard.classList.remove('scrollable-bottom');
        }
    }

    // Agregar event listener para el scroll en la tabla de contenidos
    document.addEventListener('DOMContentLoaded', function() {
        const tocContainer = document.querySelector('#tableOfContents .card-body');
        if (tocContainer) {
            tocContainer.addEventListener('scroll', updateScrollIndicators);
            // Verificar inicialmente
            setTimeout(updateScrollIndicators, 100);
        }
    });

    // Back to top button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Highlight current section in navigation
    function highlightCurrentSection() {
        const sections = document.querySelectorAll('.section');
        const navLinks = document.querySelectorAll('#tableOfContents .nav-link');
        const submenuItems = document.querySelectorAll('#tableOfContents .submenu-item');
        
        let currentSection = '';
        let currentSubsection = '';
        let bestMatch = null;
        let bestDistance = Infinity;
        
        // Encontrar la sección más cercana al viewport
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            const distance = Math.abs(rect.top);
            
            // Considerar la sección visible si está en el viewport o muy cerca
            if (rect.top <= 150 && rect.bottom >= -50) {
                if (distance < bestDistance) {
                    bestDistance = distance;
                    bestMatch = section.id;
                }
            }
        });
        
        currentSection = bestMatch;
        
        // También verificar subsecciones con mayor precisión
        const subsections = document.querySelectorAll('[id^="requisitos-"], [id^="casos-"], [id^="normativa"], [id^="arquitectura"], [id^="modelo-"], [id^="interfaces-"], [id^="diagramas-"], [id^="tecnologias"], [id^="estructura-"], [id^="plan-"], [id^="manual-"]');
        let bestSubsectionMatch = null;
        let bestSubsectionDistance = Infinity;
        
        subsections.forEach(subsection => {
            const rect = subsection.getBoundingClientRect();
            const distance = Math.abs(rect.top);
            
            if (rect.top <= 150 && rect.bottom >= -50) {
                if (distance < bestSubsectionDistance) {
                    bestSubsectionDistance = distance;
                    bestSubsectionMatch = subsection.id;
                }
            }
        });
        
        currentSubsection = bestSubsectionMatch;
        
        // Limpiar estados activos
        navLinks.forEach(link => link.classList.remove('active'));
        submenuItems.forEach(item => item.classList.remove('active'));
        
        let activeElement = null;
        
        // Priorizar subsección sobre sección principal
        if (currentSubsection) {
            const submenuLink = document.querySelector(`#tableOfContents .submenu-item[href="#${currentSubsection}"]`);
            if (submenuLink) {
                submenuLink.classList.add('active');
                activeElement = submenuLink;
                
                // También expandir el submenú padre si no está abierto
                const parentCollapse = submenuLink.closest('.collapse');
                if (parentCollapse && !parentCollapse.classList.contains('show')) {
                    const collapse = new bootstrap.Collapse(parentCollapse, {show: true});
                    // Esperar a que se expanda antes de hacer scroll
                    setTimeout(() => {
                        scrollToActiveInTOC(submenuLink);
                    }, 350);
                } else {
                    // Si ya está expandido, hacer scroll inmediatamente
                    scrollToActiveInTOC(submenuLink);
                }
            }
        } else if (currentSection) {
            // Solo marcar sección principal si no hay subsección activa
            const mainLink = document.querySelector(`#tableOfContents .nav-link[href="#${currentSection}"]`);
            if (mainLink) {
                mainLink.classList.add('active');
                activeElement = mainLink;
                scrollToActiveInTOC(mainLink);
            }
        }
        
        // Expandir automáticamente el submenú de la sección activa
        expandActiveSubmenu();
        
        // Actualizar indicadores de scroll
        updateScrollIndicators();
        
        return activeElement;
    }
    
    // Throttled scroll event for performance
    let ticking = false;
    let isUserScrolling = false;
    let scrollTimer = null;
    
    function updateOnScroll() {
        if (!ticking) {
            requestAnimationFrame(function() {
                // Detectar si el usuario está haciendo scroll activamente
                isUserScrolling = true;
                clearTimeout(scrollTimer);
                
                // Actualizar resaltado de sección actual
                highlightCurrentSection();
                
                // Resetear flag después de un delay
                scrollTimer = setTimeout(() => {
                    isUserScrolling = false;
                }, 150);
                
                ticking = false;
            });
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', updateOnScroll);
    
    // Función mejorada para detectar intersección de secciones
    function setupIntersectionObserver() {
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -60% 0px', // Zona de activación más precisa
                threshold: [0, 0.1, 0.5, 1.0]
            };
            
            const observer = new IntersectionObserver((entries) => {
                // Solo procesar si no estamos en un scroll manual
                if (!isUserScrolling) return;
                
                let mostVisible = null;
                let maxRatio = 0;
                
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.intersectionRatio > maxRatio) {
                        maxRatio = entry.intersectionRatio;
                        mostVisible = entry.target;
                    }
                });
                
                if (mostVisible) {
                    const sectionId = mostVisible.id;
                    updateActiveNavigation(sectionId);
                }
            }, observerOptions);
            
            // Observar todas las secciones
            document.querySelectorAll('.section, [id^="requisitos-"], [id^="casos-"], [id^="normativa"], [id^="arquitectura"], [id^="modelo-"], [id^="interfaces-"], [id^="diagramas-"], [id^="tecnologias"], [id^="estructura-"], [id^="plan-"], [id^="manual-"]').forEach(section => {
                observer.observe(section);
            });
        }
    }
    
    // Función para actualizar la navegación activa
    function updateActiveNavigation(sectionId) {
        if (!sectionId) return;
        
        const navLinks = document.querySelectorAll('#tableOfContents .nav-link');
        const submenuItems = document.querySelectorAll('#tableOfContents .submenu-item');
        
        // Limpiar estados activos
        navLinks.forEach(link => link.classList.remove('active'));
        submenuItems.forEach(item => item.classList.remove('active'));
        
        // Buscar enlace correspondiente (priorizar submenús)
        let targetLink = document.querySelector(`#tableOfContents .submenu-item[href="#${sectionId}"]`);
        
        if (targetLink) {
            // Es una subsección
            targetLink.classList.add('active');
            
            // Expandir el submenú padre si no está abierto
            const parentCollapse = targetLink.closest('.collapse');
            if (parentCollapse && !parentCollapse.classList.contains('show')) {
                const collapse = new bootstrap.Collapse(parentCollapse, {show: true});
                setTimeout(() => {
                    scrollToActiveInTOC(targetLink);
                }, 350);
            } else {
                scrollToActiveInTOC(targetLink);
            }
        } else {
            // Es una sección principal
            targetLink = document.querySelector(`#tableOfContents .nav-link[href="#${sectionId}"]`);
            if (targetLink) {
                targetLink.classList.add('active');
            }
        }
    }

    // Función para actualizar indicadores de scroll
    function updateScrollIndicators() {
        const tocContainer = document.querySelector('#tableOfContents .card-body');
        const tocCard = document.querySelector('#tableOfContents .card');
        
        if (!tocContainer || !tocCard) return;
        
        const scrollTop = tocContainer.scrollTop;
        const scrollHeight = tocContainer.scrollHeight;
        const clientHeight = tocContainer.clientHeight;
        
        // Mostrar indicador superior si hay contenido arriba
        if (scrollTop > 10) {
            tocCard.classList.add('scrollable-top');
        } else {
            tocCard.classList.remove('scrollable-top');
        }
        
        // Mostrar indicador inferior si hay contenido abajo
        if (scrollTop < scrollHeight - clientHeight - 10) {
            tocCard.classList.add('scrollable-bottom');
        } else {
            tocCard.classList.remove('scrollable-bottom');
        }
        
        // Activar indicador de seguimiento cuando hay scroll activo
        if (isUserScrolling) {
            tocCard.classList.add('following');
        } else {
            setTimeout(() => {
                tocCard.classList.remove('following');
            }, 1000);
        }
    }
    
    // Initialize highlighting
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar observer de intersección
        setupIntersectionObserver();
        
        // Configurar indicadores de scroll
        const tocContainer = document.querySelector('#tableOfContents .card-body');
        if (tocContainer) {
            tocContainer.addEventListener('scroll', updateScrollIndicators);
            // Verificar inicialmente
            setTimeout(updateScrollIndicators, 100);
        }
        
        // Resaltado inicial
        highlightCurrentSection();
        
        // Expandir automáticamente el primer submenú si hay una sección activa
        setTimeout(() => {
            const activeSection = document.querySelector('.section');
            if (activeSection) {
                const sectionId = activeSection.id;
                const sectionSubmenus = {
                    'analisis': 'analisis-submenu',
                    'diseno': 'diseno-submenu',
                    'codificacion': 'codificacion-submenu',
                    'manuales': 'manuales-submenu'
                };
                
                if (sectionSubmenus[sectionId]) {
                    const submenu = document.getElementById(sectionSubmenus[sectionId]);
                    if (submenu && !submenu.classList.contains('show')) {
                        const collapse = new bootstrap.Collapse(submenu, {show: true});
                    }
                }
            }
        }, 500);
    });
</script>

</body>
</html>
