import './bootstrap';
import './firebase';
import './echo';
import { initWebPush } from './webpush';

window.addEventListener('DOMContentLoaded', () => {
    initWebPush();
});
