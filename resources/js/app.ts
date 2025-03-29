import '../css/app.css';
import { createApp } from 'vue';
import CsvUpload from './components/CsvUpload.vue';

const app = createApp({});
app.component('csvUpload', CsvUpload);
app.mount('#app');
