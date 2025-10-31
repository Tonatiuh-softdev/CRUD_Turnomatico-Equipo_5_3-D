const translations = {
    'es': {
        // Textos compartidos/comunes
        'idioma': 'Español',
        'cerrar': 'Cerrar',
        'guardar': 'Guardar',
        
        // Index page
        'pantalla_espera': 'Pantalla de espera',
        'pantalla_turno': 'Pantalla de turno',
        'pantalla_empleado': 'Pantalla de empleado',
        
        // Pantalla de espera
        'esperando_turno': 'Esperando turno',
        'tiempo_estimado': 'Tiempo estimado',
        
        // Pantalla de turno
        'tomar_turno': 'Tomar turno',
        'su_turno': 'Su turno es',
        
        // Pantalla empleado
        'siguiente_turno': 'Siguiente turno',
        'llamar_cliente': 'Llamar cliente',


        // Palabras muy usadas
        'Nombre': 'Nombre',
        'Descripción': 'Descripción',
        'Eliminar' : 'Eliminar',
        'Configurar' : 'Configurar',
        'Correo' : 'Correo',




        // Servicios
        'Administrar Servicios': 'Administrar Servicios',
        'Agregar Servicio': 'Agregar Servicio',
        'Editar Servicio': 'Editar Servicio',
        'Guardar Cambios': 'Guardar Cambios',
        'Servicio' : 'Servicio',

        // Cajas
        'Administrar Cajas': 'Administrar Cajas',
        
        //Empleados
        'Administrar Empleados': 'Administrar Empleados',

        //Clientes
        'Administrar Clientes': 'Administrar Clientes',
       
    },
    'en': {
        // Shared/common texts
        'idioma': 'English',
        'cerrar': 'Close',
        'guardar': 'Save',
        
        // Index page
        'pantalla_espera': 'Waiting Screen',
        'pantalla_turno': 'Turn Screen',
        'pantalla_empleado': 'Employee Screen',
        
        // Waiting screen
        'esperando_turno': 'Waiting for turn',
        'tiempo_estimado': 'Estimated time',
        
        // Turn screen
        'tomar_turno': 'Take turn',
        'su_turno': 'Your turn is',
        
        // Employee screen
        'siguiente_turno': 'Next turn',
        'llamar_cliente': 'Call client',

        // Frequently used words
        'Nombre': 'Name',
        'Descripción': 'Description',
        'Eliminar' : 'Delete',
        'Configurar' : 'Configure',
        'Correo' : 'Email',

        // Services
        'Administrar Servicios': 'Manage Services',
        'Agregar Servicio': 'Add Service',
        'Editar Servicio': 'Edit Service',
        'Guardar Cambios': 'Save Changes',
        'Servicio' : 'Service', 

        // Boxes
        'Administrar Cajas': 'Manage Boxes',

        //Employees
        'Administrar Empleados': 'Manage Employees',

        //Clients
        'Administrar Clientes': 'Manage Clients',
       }
};

// Función para traducir toda la página
function translatePage(lang) {
    if (!translations[lang]) {
        console.error('Language not found:', lang);
        return;
    }
    
    // Guardar idioma seleccionado en localStorage
    localStorage.setItem('selectedLanguage', lang);
    
    // Traducir elementos con data-translate
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });
    
    // Traducir placeholders
    document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        if (translations[lang][key]) {
            element.placeholder = translations[lang][key];
        }
    });
    
    // Traducir títulos/tooltips
    document.querySelectorAll('[data-translate-title]').forEach(element => {
        const key = element.getAttribute('data-translate-title');
        if (translations[lang][key]) {
            element.title = translations[lang][key];
        }
    });
}

// Función para obtener una traducción específica
function getTranslation(key, lang = null) {
    const currentLang = lang || localStorage.getItem('selectedLanguage') || 'es';
    return translations[currentLang][key] || key;
}

// Inicializar traducción cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('Iniciando traducción...');
    const savedLang = localStorage.getItem('selectedLanguage') || 
                     navigator.language.substring(0, 2) || 
                     'es';
    console.log('Idioma seleccionado:', savedLang);
    translatePage(savedLang);
    
    // Verificar que los elementos están marcados correctamente
    const elements = document.querySelectorAll('[data-translate]');
    console.log('Elementos para traducir encontrados:', elements.length);
});