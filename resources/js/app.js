import './bootstrap';

import { createApp } from 'vue';
import HelloWorld from '@/components/HelloWorld.vue';
import CustomerList from '@/components/CustomerList.vue';

window.app = createApp({
    setup() {
        return {
            message: 'Welcome to Your Vue.js App',
        };
    },
    components: {
        HelloWorld,
        CustomerList,
    },
}).mount('#app');
