<template>
    <div>
        <div v-if="!loading">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th scope="col">{{ $t('Name') }}</th>
                        <th scope="col">{{ $t('E-Mail Address') }}</th>
                        <th scope="col">{{ $t('Status') }}</th>
                        <th scope="col">{{ $t('Created') }}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <customer-list-item
                        v-for="customer in customers.data"
                        :key="customer.id"
                        :customer="customer"
                    ></customer-list-item>
                </tbody>
            </table>
            <Bootstrap5Pagination
                class="mt-4"
                :data="customers"
                @pagination-change-page="getCustomers"
                :limit="2"
                align="center"
            />
        </div>
        <div class="d-flex justify-content-center align-items-center" v-else>
            <div class="spinner-border text-primary mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerListItem from './CustomerListItem.vue';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    export default {
        components: {
            Bootstrap5Pagination,
            CustomerListItem,
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
                await axios.get(`/api/customers?page=${page}`)
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
