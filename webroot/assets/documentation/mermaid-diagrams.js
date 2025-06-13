// Inicializar Mermaid
document.addEventListener('DOMContentLoaded', function() {
    mermaid.initialize({
        theme: 'default',
        startOnLoad: true,
        securityLevel: 'loose',
        flowchart: { 
            useMaxWidth: true,
            htmlLabels: true,
            curve: 'linear'
        },
        er: {
            useMaxWidth: true,
            entityPadding: 25,
            fontSize: 18
        },
        gantt: {
            titleTopMargin: 25,
            barHeight: 20,
            barGap: 4,
            topPadding: 50,
            leftPadding: 75,
            gridLineStartPadding: 35,
            fontSize: 12,
            numberSectionStyles: 4
        },
        sequence: {
            showSequenceNumbers: false,
            actorMargin: 80,
            boxMargin: 10,
            mirrorActors: false
        }
    });
    
    // Asegurarse de que todos los diagramas sean responsivos
    window.addEventListener('resize', function() {
        mermaid.init(undefined, document.querySelectorAll('.mermaid'));
    });
});
