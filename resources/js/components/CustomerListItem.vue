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
            <update-status-customer
                :customer="customer"
                @update-customer="refreshUser"
            ></update-status-customer>
        </td>
    </tr>
</template>

<script>
    import UpdateStatusCustomer from './UpdateStatusCustomer.vue';

    export default {
        components: {
            UpdateStatusCustomer
        },
        props: {
            customer: {
                type: Object,
                required: true,
            }
        },
        methods: {
            refreshUser(status) {
                this.customer.status = status;
            }
        }
    }
</script>

<style>

</style>
