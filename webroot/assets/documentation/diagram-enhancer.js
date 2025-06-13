// Mejora la visualización de diagramas
document.addEventListener('DOMContentLoaded', function() {
    // Mejora para diagramas ASCII
    const asciiDiagrams = document.querySelectorAll('pre.ascii');
    asciiDiagrams.forEach(function(diagram) {
        // Aplica fuente monoespaciada y evita ruptura de línea
        diagram.style.fontFamily = "'Roboto Mono', monospace";
        diagram.style.whiteSpace = "pre";
        diagram.style.overflow = "auto";
        diagram.style.fontSize = "14px";
        diagram.style.lineHeight = "1.4";
        diagram.style.backgroundColor = "#f5f5f5";
        diagram.style.padding = "15px";
        diagram.style.borderRadius = "5px";
        diagram.style.border = "1px solid #ddd";
    });
    
    // Mejora para diagramas Mermaid
    const mermaidDiagrams = document.querySelectorAll('.mermaid');
    mermaidDiagrams.forEach(function(diagram) {
        // Añade un contenedor con estilos para mejorar la presentación
        const container = document.createElement('div');
        container.className = 'diagram-container';
        
        // Inserta el contenedor antes del diagrama
        diagram.parentNode.insertBefore(container, diagram);
        
        // Mueve el diagrama dentro del contenedor
        container.appendChild(diagram);
        
        // Añade título si está disponible (buscando un elemento h4 anterior)
        const previousElement = container.previousElementSibling;
        if (previousElement && previousElement.tagName.toLowerCase() === 'h4') {
            const title = document.createElement('div');
            title.className = 'diagram-title';
            title.textContent = previousElement.textContent;
            container.insertBefore(title, diagram);
        }
    });
    
    // Añade botón para descargar diagramas como SVG
    setTimeout(function() {
        const svgElements = document.querySelectorAll('.diagram-container svg');
        svgElements.forEach(function(svg, index) {
            const downloadBtn = document.createElement('button');
            downloadBtn.className = 'download-diagram-btn';
            downloadBtn.textContent = 'Descargar SVG';
            downloadBtn.onclick = function() {
                const svgData = new XMLSerializer().serializeToString(svg);
                const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                const svgUrl = URL.createObjectURL(svgBlob);
                
                const downloadLink = document.createElement('a');
                downloadLink.href = svgUrl;
                downloadLink.download = 'diagrama_' + (index + 1) + '.svg';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            };
            
            svg.parentNode.insertBefore(downloadBtn, svg.nextSibling);
        });
    }, 1000); // Esperar a que Mermaid renderice los SVGs
});
