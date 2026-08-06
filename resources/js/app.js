// =============================================
// app.js — Entry Point
// Urutan import TIDAK boleh diubah
// =============================================

// 1. Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// 2. Axios
import axios from 'axios';
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// 3. Select2 (jQuery required)
import $ from 'jquery';
window.$ = window.jQuery = $;
import 'select2';
import 'select2/dist/css/select2.min.css';

// 4. SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// 5. Init modules
import './select2.init.js';
import './alert.init.js';
import { initDatatable } from './datatable.js';

initDatatable(Alpine);

// 6. Start Alpine
Alpine.start();
