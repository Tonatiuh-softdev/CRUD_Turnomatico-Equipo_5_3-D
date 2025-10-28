(function(){
  // Selectores
  const container = document.querySelector('.InputContainer');
  if (!container) return;

  const input = container.querySelector('.input');
  let suggestionsBox = container.querySelector('.suggestions');
  if (!suggestionsBox) {
    suggestionsBox = document.createElement('div');
    suggestionsBox.className = 'suggestions';
    container.appendChild(suggestionsBox);
  }

  // Endpoint (se puede ajustar en el HTML con data-endpoint)
  const endpoint = container.dataset.endpoint || '../Componentes/PHP/BarraBusqueda.php';

  let debounceTimer = null;

  function clearSuggestions(){
    suggestionsBox.innerHTML = '';
    suggestionsBox.style.display = 'none';
  }

  function renderSuggestions(items){
    clearSuggestions();
    if (!items || items.length === 0) return;
    items.forEach(item => {
      const div = document.createElement('div');
      div.className = 'suggestion';
      div.textContent = item.label;
      div.tabIndex = 0;
      div.addEventListener('click', () => {
        if (item.url) {
          window.location.href = item.url;
        } else {
          input.value = item.label;
          clearSuggestions();
        }
      });
      div.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') div.click();
      });
      suggestionsBox.appendChild(div);
    });
    suggestionsBox.style.display = 'block';
  }

  async function doSearch(q){
    try{
      const url = endpoint + '?q=' + encodeURIComponent(q);
      const res = await fetch(url, {cache: 'no-store'});
      if (!res.ok) {
        clearSuggestions();
        return;
      }
      const json = await res.json();
      renderSuggestions(json);
    }catch(err){
      console.error('Search error', err);
      clearSuggestions();
    }
  }

  input.addEventListener('input', (e)=>{
    const val = e.target.value.trim();
    if (debounceTimer) clearTimeout(debounceTimer);
    if (val === ''){
      clearSuggestions();
      return;
    }
    debounceTimer = setTimeout(()=> doSearch(val), 300);
  });

  // Cerrar al hacer click fuera
  document.addEventListener('click', (e)=>{
    if (!container.contains(e.target)) clearSuggestions();
  });

})();