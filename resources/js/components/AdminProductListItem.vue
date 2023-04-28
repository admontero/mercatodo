<template>
    <tr>
        <th scope="row">{{ product.name }}</th>
        <td>{{ product.code }}</td>
        <td>{{ productPrice }}</td>
        <td>{{ product.stock }}</td>
        <td>
            <span class="badge bg-success text-uppercase" v-if="product.status === 'activated'">
                {{ $t(product.status) }}
            </span>
            <span class="badge bg-warning text-uppercase" v-else-if="product.status === 'inactivated'">
                {{ $t(product.status) }}
            </span>
        </td>
        <td>{{ product.ago }}</td>
        <td>
            <div class="d-flex gap-2">
                <update-status-product
                    :product="product"
                    @update-product="refreshProduct"
                ></update-status-product>
                <a :href="`/admin/products/${product.slug}/edit`" class="link-info">
                    <IconEdit :size="20" stroke-width="2" />
                </a>
            </div>
        </td>
    </tr>
</template>

<script>
    import UpdateStatusProduct from './UpdateStatusProduct.vue';
    import { useToast } from "vue-toastification";
    import { trans } from 'laravel-vue-i18n';
    import { IconEdit } from '@tabler/icons-vue';

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        components: {
            UpdateStatusProduct,
            IconEdit,
        },
        props: {
            product: {
                type: Object,
                required: true,
            }
        },
        methods: {
            refreshProduct(status) {
                this.product.status = status;
                this.toast.success(trans(`Product ${status}`), {
                    position: "bottom-left",
                    timeout: 3000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: false,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
            }
        },
        computed: {
            productPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            }
        }
    }
</script>

<style>

</style>
