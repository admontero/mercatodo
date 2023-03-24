import './bootstrap';

import { createApp } from 'vue';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import HelloWorld from '@/components/HelloWorld.vue';
import CustomerList from '@/components/CustomerList.vue';
import CustomerForm from '@/components/CustomerForm.vue';

window.app = createApp({
    setup() {
        return {
            message: 'Welcome to Your Vue.js App',
        };
    },
    components: {
        HelloWorld,
        CustomerList,
        CustomerForm,
    },
});

window.app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
});

window.app.mount('#app');
