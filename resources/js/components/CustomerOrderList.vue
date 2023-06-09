<template>
    <div>
        <div v-if="!loading">
            <div v-if="total">
                <customer-order-list-item
                    v-for="order in orders.data"
                    :key="order.id"
                    :order="order"
                    @retry-pay="updateLoading"
                ></customer-order-list-item>
                <Bootstrap5Pagination
                    class="mt-4"
                    :data="orders"
                    @pagination-change-page="getOrders"
                    :limit="2"
                    align="center"
                />
            </div>
            <div class="p-3" v-else>
                <p class="fw-medium text-center">
                    {{ $t('There are no orders to display') }}.
                </p>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center" v-else>
            <div class="spinner-border text-primary mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';
    import CustomerOrderListItem from './CustomerOrderListItem.vue';

    export default {
        components: {
            Bootstrap5Pagination,
            CustomerOrderListItem,
        },
        data() {
            return {
                orders: [],
                total: '',
                loading: false,
            }
        },
        mounted() {
            this.getOrders()
        },
        methods: {
            async getOrders(page = 1) {
                this.loading = true;
                await axios.get(`/api/customer/orders?page=${page}`)
                    .then(res => {
                        this.orders = res.data
                        this.total = res.data.meta.total
                        this.loading = false
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            },
            updateLoading(state) {
                this.loading = state
            }
        }
    }
</script>
