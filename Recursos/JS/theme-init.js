// Aplica la clase `dark-theme` al <html> o <body> según el valor guardado en 
// localStorage
// Ejecutar lo antes posible (inclúyelo en <head> para evitar flash de color)
(function(){
  try{
    var theme = localStorage.getItem('theme');
    var el = document.documentElement || document.body;
    if(theme === 'dark') el.classList.add('dark-theme');
    else el.classList.remove('dark-theme');
  }catch(e){ /* no-op si localStorage no disponible */ }
})();
