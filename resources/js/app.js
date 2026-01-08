import './bootstrap';
import 'flowbite';
import { initFlowbite } from 'flowbite';
import Swal from 'sweetalert2'
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';

import.meta.glob([
  '../images/**',
]);

Livewire.hook("commit", ({ component, commit, respond, succeed, fail }) => {
    succeed(({ snapshot, effect }) => {
        queueMicrotask(() => {
            initFlowbite();
        });
    });
});

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});

window.Swal = Swal