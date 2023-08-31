import Alpine from "alpinejs";
import focus from "@alpinejs/focus";
window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.start();

/*Esta seccion es para aparecer/desaparecer el menu responsivo*/
const boton = document.querySelector('#btn_menu');
const menu = document.querySelector('#menu');

boton.addEventListener('click', () =>{
    console.log('me diste click')
    menu.classList.toggle('hidden')

} )