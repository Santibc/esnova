import './bootstrap';
import Alpine from 'alpinejs';

import jQuery from 'jquery';
window.$ = jQuery;
window.jQuery = jQuery; // Importante para que funcione DataTables

// DataTables núcleo
import 'datatables.net-dt';

// Extensiones de botones
import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';
import 'datatables.net-buttons/js/buttons.colVis.js';
import Swal from 'sweetalert2';
window.Swal = Swal;
// Dependencias de exportación
import jszip from 'jszip';
window.JSZip = jszip;


window.Alpine = Alpine;
Alpine.start();
