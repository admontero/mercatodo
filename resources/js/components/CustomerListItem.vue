<template>
  <tr>
        <th scope="row">{{ customer.first_name }} {{ customer.last_name }}</th>
        <td>{{ customer.email }}</td>
        <td>
            <span class="badge bg-success text-uppercase" v-if="customer.status === 'activated'">
                {{ customer.status }}
            </span>
            <span class="badge bg-warning text-uppercase" v-else-if="customer.status === 'inactivated'">
                {{ customer.status }}
            </span>
        </td>
        <td>{{ customer.ago }}</td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <update-status-customer
                    :customer="customer"
                    @update-customer="refreshUser"
                ></update-status-customer>
                <a :href="`/admin/customers/${customer.id}/edit`" class="text-info">
                    <span data-feather="edit" class="align-text-bottom"></span>
                </a>
            </div>
        </td>
    </tr>
</template>

<script>
    import UpdateStatusCustomer from './UpdateStatusCustomer.vue';
    import { useToast } from "vue-toastification";

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        components: {
            UpdateStatusCustomer
        },
        props: {
            customer: {
                type: Object,
                required: true,
            }
        },
        mounted() {
            feather.replace();
        },
        methods: {
            refreshUser(status) {
                this.customer.status = status;
                this.toast.success(`Cliente ${this.getStatus}`, {
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
            getStatus() {
                if (this.customer.status === 'activated') return 'activado';

                return 'desactivado';
            }
        }
    }
</script>

<style>

</style>
