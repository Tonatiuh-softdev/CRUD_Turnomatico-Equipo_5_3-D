// Detectar la página actual y marcar el enlace activo
(function() {
  function activateNavLink() {
    try {
      // Obtener el nombre del archivo PHP actual desde la URL
      const url = window.location.href;
      const pathArray = url.split('/');
      const currentFile = pathArray[pathArray.length - 1] || 'index.php';
      
      console.log('Current URL:', url);
      console.log('Current File:', currentFile);
      
      // Obtener todos los links del navbar
      const links = document.querySelectorAll('aside a');
      console.log('Total links found:', links.length);
      
      links.forEach(link => {
        const href = link.getAttribute('href');
        const hrefArray = href.split('/');
        const hrefFile = hrefArray[hrefArray.length - 1];
        
        console.log(`Comparando: ${currentFile} vs ${hrefFile}`);
        
        // Remover la clase activa primero
        link.classList.remove('active');
        
        // Comparar los nombres de archivo
        if (currentFile === hrefFile) {
          console.log(`✓ MATCH: ${currentFile} === ${hrefFile}`);
          link.classList.add('active');
        }
      });
    } catch(e) {
      console.error('Error en activateNavLink:', e);
    }
  }
  
  // Ejecutar cuando el DOM esté completamente listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOMContentLoaded ejecutado');
      setTimeout(activateNavLink, 50);
    });
  } else {
    console.log('DOM ya cargado, ejecutando ahora');
    setTimeout(activateNavLink, 50);
  }
  
  // También ejecutar cuando toda la página esté lista
  window.addEventListener('load', function() {
    console.log('Window load ejecutado');
    setTimeout(activateNavLink, 50);
  });
})();