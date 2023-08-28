<template>
    <form class="my-4" @submit.prevent="submit">
        <div class="row g-3">
            <div class="col-sm-7">
                <select class="form-select" v-model="report.name">
                    <option value="BestSellingProduct">{{ $t('Best Selling Product') }}</option>
                    <option value="BestSellingCategory">{{ $t('Best Selling Category') }}</option>
                    <option value="BestBuyer">{{ $t('Best Buyer') }}</option>
                    <option value="SalesAndUsersByState">{{ $t('Sales And Users By State') }}</option>
                    <option value="SalesByMonth">{{ $t('Sales By Month') }}</option>
                </select>
            </div>
            <div class="col-sm">
                <select class="form-select" v-model="report.records">
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
                report: {
                    name: 'BestSellingProduct',
                    records: 10,
                },
            }
        },
        methods: {
            submit() {
                axios.post('/api/admin/reports', this.report)
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
    }
</script>

<style>

</style>
