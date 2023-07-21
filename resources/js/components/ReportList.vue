<template>
    <form class="my-4" @submit.prevent="submit" method="get">
        <div class="row g-3">
            <div class="col-sm-7">
                <select class="form-select" v-model="report">
                    <option value="best-selling-product">{{ $t('Best Selling Product') }}</option>
                    <option value="best-selling-category">{{ $t('Best Selling Category') }}</option>
                    <option value="best-buyer">{{ $t('Best Buyer') }}</option>
                    <option value="sales-and-users-by-state">{{ $t('Sales And Users By State') }}</option>
                    <option value="sales-by-month">{{ $t('Sales By Month') }}</option>
                </select>
            </div>
            <div class="col-sm">
                <select class="form-select" v-model="queries.records">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-sm">
                <button class="btn btn-primary" type="submit">
                    {{ $t('Generate') }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>
    import { trans } from 'laravel-vue-i18n';
    import { useToast } from "vue-toastification";

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        data() {
            return {
                errors: [],
                queries: {
                    records: 10,
                },
                report: 'best-selling-product',
            }
        },
        methods: {
            submit() {
                axios.get(`/api/admin/reports/${this.report}?${this.queryString}`)
                    .then(res => {
                        this.toast.success(trans(res.data.message), {
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
                    }).catch(err => {
                        this.toast.error(trans('Failed Report Generation'), {
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
                    })
            }
        },
        computed: {
            queryString() {
                this.loading = true
                return Object.keys(this.queries)
                    .map(key => {
                        if (this.queries[key] || this.queries[key] === 0) {
                            return encodeURIComponent(key) + '=' + encodeURIComponent(this.queries[key])
                        }
                    })
                    .filter(param => param)
                    .join('&');
            }
        },
    }
</script>

<style>

</style>
