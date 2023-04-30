import './bootstrap';

import { createApp } from 'vue';
import { i18nVue } from 'laravel-vue-i18n';
import Toast from 'vue-toastification';

import HelloWorld from '@/components/HelloWorld.vue';
import CustomerList from '@/components/CustomerList.vue';
import CustomerForm from '@/components/CustomerForm.vue';
import CustomerProfileForm from '@/components/CustomerProfileForm.vue';
import CategoryList from '@/components/CategoryList.vue';
import CategoryForm from '@/components/CategoryForm.vue';
import CustomerProductList from '@/components/CustomerProductList.vue';
import AdminProductList from '@/components/AdminProductList.vue';
import ProductForm from '@/components/ProductForm.vue';

import 'vue-toastification/dist/index.css';
import 'vue-search-select/dist/VueSearchSelect.css'

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
        CustomerProfileForm,
        CategoryList,
        CategoryForm,
        CustomerProductList,
        AdminProductList,
        ProductForm,
    },
});

window.app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../../lang/*.json');
        return await langs[`../../lang/${lang}.json`]();
    }
});

window.app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
});

window.app.mount('#app');
