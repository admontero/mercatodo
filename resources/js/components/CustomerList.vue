<template>
    <div>
        <div v-if="!loading">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Creado hace</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers.data" :key="customer.id">
                        <th scope="row">{{ customer.first_name }} {{ customer.last_name }}</th>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.ago }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                class="mt-4 admin-pagination"
                :data="customers"
                @pagination-change-page="getCustomers"
                :limit="2"
                align="center"
            />
        </div>
        <div class="d-flex justify-content-center align-items-center" v-else>
            <div class="spinner-border text-dark mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    export default {
        components: {
            Bootstrap5Pagination
        },
        data() {
            return {
                customers: [],
                loading: false,
            }
        },
        mounted() {
            this.getCustomers()
        },
        methods: {
            async getCustomers(page = 1) {
                this.loading = true;
                await axios.get(`/customers?page=${page}`)
                    .then(res => {
                        this.customers = res.data
                        this.loading = false
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style>

</style>
