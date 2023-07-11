<template>
    <div class="accordion mt-4" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    {{ $t('Best Selling Product') }}
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" style="">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select" v-model="queries.records">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                            {{ $t('Records') }}
                        </div>
                        <div>
                            <button class="btn btn-primary" @click.prevent="generateReport('best-selling-product')">
                                {{ $t('Generate') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    {{ $t('Best Buyer') }}
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" style="">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select" v-model="queries.records">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                            {{ $t('Records') }}
                        </div>
                        <div>
                            <button class="btn btn-primary" @click.prevent="generateReport('best-buyer')">
                                {{ $t('Generate') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            }
        },
        methods: {
            generateReport(reportName) {
                axios.get(`/api/admin/reports/${reportName}?${this.queryString}`)
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

                        this.resetQueries();
                    }).catch(err => {
                        this.toast.error(trans(err.response.data.message), {
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
            },
            resetQueries() {
                this.queries = {
                    records: 10,
                }
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
