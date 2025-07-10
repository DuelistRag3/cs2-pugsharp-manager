import './bootstrap';
import 'flowbite';
import Swal from 'sweetalert2'
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';

import.meta.glob([
  '../images/**',
]);

document.addEventListener('livewire:navigated', () => {
    initFlowbite();
})

window.Swal = Swal