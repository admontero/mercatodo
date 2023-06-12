<template>
    <div class="row">
        <div class="col-lg-8">
            <form class="my-4" @submit.prevent="submit" v-show="!loading">
                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">{{ $t('First Name') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.first_name, 'form-control is-invalid' : this.errors?.first_name }"
                            type="text"
                            aria-label="First name"
                            name="first_name"
                            id="first_name"
                            v-model="customer.first_name"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.first_name">
                            <strong>{{ this.errors.first_name[0] }}</strong>
                        </span>
                    </div>
                    <div class="col">
                        <label for="last_name" class="form-label">{{ $t('Last Name') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.last_name, 'form-control is-invalid' : this.errors?.last_name }"
                            type="text"
                            aria-label="Last name"
                            name="last_name"
                            id="last_name"
                            v-model="customer.last_name"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.last_name">
                            <strong>{{ this.errors.last_name[0] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label for="document_type" class="form-label">{{ $t('Document Type') }}</label>
                        <select
                            :class="{'form-control' : !this.errors?.document_type, 'form-control is-invalid' : this.errors?.document_type }"
                            id="document_type"
                            name="document_type"
                            v-model="customer.document_type"
                        >
                            <option
                                class="text-muted"
                                :value="null"
                            >
                                -- {{ $t('Select the type of document') }} --
                            </option>
                            <option
                                value="CC"
                                :selected="customer.document_type === 'CC'"
                            >
                                {{ $t('Citizenship Card') }}
                            </option>
                            <option
                                value="CE"
                                :selected="customer.document_type === 'CE'"
                            >
                                {{ $t('Foreigner Card') }}
                            </option>
                            <option
                                value="PPN"
                                :selected="customer.document_tyPPNe === 'PPN'"
                            >
                                {{ $t('Passport') }}
                            </option>
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.document_type">
                            <strong>{{ this.errors.document_type[0] }}</strong>
                        </span>
                    </div>
                    <div class="col">
                        <label for="document" class="form-label">{{ $t('Document') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.document, 'form-control is-invalid' : this.errors?.document }"
                            type="text"
                            aria-label="Document"
                            name="document"
                            id="document"
                            v-model="customer.document"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.document">
                            <strong>{{ this.errors.document[0] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label for="country" class="form-label">{{ $t('Country') }}</label>
                        <multiselect
                            v-model="selectedCountry"
                            :options="countries"
                            label="name"
                            track-by="id"
                            ref="selectCountry"
                            :placeholder="$t('Search country')"
                            @update:modelValue="onChangeCountry"
                        ></multiselect>
                        <span class="text-danger small" role="alert" v-if="this.errors?.country_id">
                            <strong>{{ this.errors.country_id[0] }}</strong>
                        </span>
                    </div>
                    <div class="col">
                        <label for="state" class="form-label">{{ $t('State') }}</label>
                        <multiselect
                            v-model="selectedState"
                            :options="states"
                            label="name"
                            track-by="id"
                            ref="selectState"
                            :placeholder="$t('Search state')"
                            @update:modelValue="onChangeState"
                        ></multiselect>
                        <span class="text-danger small" role="alert" v-if="this.errors?.state_id">
                            <strong>{{ this.errors.state_id[0] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label for="city" class="form-label">{{ $t('City') }}</label>
                        <multiselect
                            v-model="selectedCity"
                            :options="cities"
                            label="name"
                            track-by="id"
                            ref="selectCity"
                            :placeholder="$t('Search city')"
                            @update:modelValue="onChangeCity"
                        ></multiselect>
                        <span class="text-danger small" role="alert" v-if="this.errors?.city_id">
                            <strong>{{ this.errors.city_id[0] }}</strong>
                        </span>
                    </div>
                    <div class="col">
                        <label for="address" class="form-label">{{ $t('Address') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.address, 'form-control is-invalid' : this.errors?.address }"
                            type="text"
                            aria-label="Address"
                            name="address"
                            id="address"
                            v-model="customer.address"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.address">
                            <strong>{{ this.errors.address[0] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label for="phone" class="form-label">{{ $t('Phone') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.phone, 'form-control is-invalid' : this.errors?.phone }"
                            type="text"
                            aria-label="Phone"
                            name="phone"
                            id="phone"
                            v-model="customer.phone"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.phone">
                            <strong>{{ this.errors.phone[0] }}</strong>
                        </span>
                    </div>
                    <div class="col">
                        <label for="cell_phone" class="form-label">{{ $t('Cell Phone') }}</label>
                        <input
                            :class="{'form-control' : !this.errors?.cell_phone, 'form-control is-invalid' : this.errors?.cell_phone }"
                            type="text"
                            aria-label="Cell Phone"
                            name="cell_phone"
                            id="cell_phone"
                            v-model="customer.cell_phone"
                        >
                        <span class="invalid-feedback" role="alert" v-if="this.errors?.cell_phone">
                            <strong>{{ this.errors.cell_phone[0] }}</strong>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ $t('Save') }}
                    </button>
                </div>
            </form>
            <div class="d-flex justify-content-center align-items-center" v-if="loading">
                <div class="spinner-border text-primary mt-4" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { useToast } from "vue-toastification"
    import { trans } from 'laravel-vue-i18n'
    import Multiselect from 'vue-multiselect'

    export default {
        setup() {
            const toast = useToast()

            return { toast }
        },
        props: {
            customerId: {
                type: Number,
                required: true
            }
        },
        components: {
            Multiselect,
        },
        data() {
            return {
                customer: {},
                countries: [],
                selectedCountry: null,
                states: [],
                selectedState: null,
                cities: [],
                selectedCity: null,
                errors: [],
                loading: false,
            }
        },
        created() {
            this.loading = true
            this.getCustomer()
        },
        mounted() {
            this.$refs.selectCountry.$refs.search.setAttribute("autocomplete", "nope")
            this.$refs.selectState.$refs.search.setAttribute("autocomplete", "nope")
            this.$refs.selectCity.$refs.search.setAttribute("autocomplete", "nope")
        },
        methods: {
            submit() {
                axios.put(`/api/admin/customers/${this.customer.id}`, this.customer).then(res => {
                    this.toast.success(trans('Updated information'), {
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
                    this.errors = [];
                }).catch(err => {
                    this.errors = err.response.data.errors
                })
            },
            async getCustomer() {
                await axios.get(`/api/admin/customers/${this.customerId}`)
                    .then(res => {
                        this.customer = res.data
                        this.selectedCountry = this.customer.country_id ? { 'id': this.customer.country_id } : null
                        this.selectedState = this.customer.state_id ? { 'id': this.customer.state_id } : null
                        this.selectedCity = this.customer.city_id ? { 'id': this.customer.city_id } : null
                        this.loadSelects()
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false
                    })
            },
            async getStates() {
                await axios.get(`/api/countries/${this.customer.country_id}/states`)
                    .then(res => {
                        this.states = res.data
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false
                    })
            },
            async getCities() {
                await axios.get(`/api/states/${this.customer.state_id}/cities`)
                    .then(res => {
                        this.cities = res.data
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false
                    })
            },
            onChangeCountry(value) {
                if (!value) {
                    return this.resetStatesCities()
                }

                this.customer.country_id = value.id
                this.selectedState = null
                this.customer.state_id = null
                this.getStates()
            },
            onChangeState(value) {
                if (!value) {
                    return this.resetCities()
                }

                this.customer.state_id = value.id
                this.selectedCity = null
                this.customer.city_id = null
                this.getCities()
            },
            onChangeCity(value) {
                if (!value) {
                    this.selectedCity = null
                    this.customer.city_id = null
                    return
                }

                this.customer.city_id = value.id
            },
            resetStatesCities() {
                this.states = []
                this.selectedState = null
                this.cities = []
                this.selectedCity = null
                this.customer.country_id = null
                this.customer.state_id = null
                this.customer.city_id = null
            },
            resetCities() {
                this.cities = []
                this.selectedCity = null
                this.customer.state_id = null
                this.customer.city_id = null
            },
            loadSelects() {
                axios.get('/api/countries')
                    .then(res => {
                        this.countries = res.data
                        if (!this.selectedCountry?.id)
                            return this.loading = false

                        this.selectedCountry.name = this.countries.find(c => c.id === this.selectedCountry.id).name

                        axios.get(`/api/countries/${this.selectedCountry.id}/states`)
                            .then(res => {
                                this.states = res.data
                                if (!this.selectedState?.id)
                                    return this.loading = false

                                this.selectedState.name = this.states.find(s => s.id === this.selectedState.id).name

                                axios.get(`/api/states/${this.selectedState.id}/cities`)
                                    .then(res => {
                                        this.cities = res.data
                                        if (!this.selectedCity?.id)
                                            return this.loading = false

                                        this.selectedCity.name = this.cities.find(c => c.id === this.selectedCity.id).name
                                        return this.loading = false
                                    })
                                    .catch(err => {
                                        console.log(err)
                                        this.loading = false
                                    })
                                })
                            .catch(err => {
                                console.log(err)
                                this.loading = false
                            })
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false
                    })
            }
        },
    }
</script>

<style>

</style>
