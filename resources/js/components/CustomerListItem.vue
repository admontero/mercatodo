<template>
  <tr>
        <th scope="row">{{ customer.first_name }} {{ customer.last_name }}</th>
        <td>{{ customer.user.email }}</td>
        <td>
            <span class="badge bg-success text-uppercase" v-if="customer.user.status === 'activated'">
                {{ $t(customer.user.status) }}
            </span>
            <span class="badge bg-warning text-uppercase" v-else-if="customer.user.status === 'inactivated'">
                {{ $t(customer.user.status) }}
            </span>
        </td>
        <td>{{ customer.user.ago }}</td>
        <td>
            <div class="d-flex gap-2">
                <update-status-customer
                    :customer="customer"
                    @update-customer="refreshUser"
                ></update-status-customer>
                <a :href="`/admin/customers/${customer.id}/edit`" class="link-info">
                    <IconEdit :size="20" stroke-width="2" />
                </a>
            </div>
        </td>
    </tr>
</template>

<script>
    import UpdateStatusCustomer from './UpdateStatusCustomer.vue';
    import { useToast } from "vue-toastification";
    import { trans } from 'laravel-vue-i18n';
    import { IconEdit } from '@tabler/icons-vue';

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        components: {
            UpdateStatusCustomer,
            IconEdit
        },
        props: {
            customer: {
                type: Object,
                required: true,
            }
        },
        methods: {
            refreshUser(status) {
                this.customer.user.status = status;
                this.toast.success(trans(`Customer ${status}`), {
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
        }
    }
</script>

<style>

</style>
