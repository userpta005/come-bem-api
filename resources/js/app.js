/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { createApp } from 'vue';
import App from './app.vue'

// Import our custom CSS
import '../sass/app.scss'

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap'

const app = createApp(App)
app.mount("#app")
