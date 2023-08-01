import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/*Esta seccion es para aparecer/desaparecer el menu responsivo*/
const boton = document.querySelector('#btn_menu');
const menu = document.querySelector('#menu');

boton.addEventListener('click', () =>{
    console.log('me diste click')
    menu.classList.toggle('hidden')

} )