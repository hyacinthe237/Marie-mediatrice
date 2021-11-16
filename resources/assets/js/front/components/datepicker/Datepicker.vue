
<template>
    <div class="datepicker-container">
        <input type="text" readonly
               :class="classDesign"
               :value="date_formatted"
               @click="showDatepicker">

        <input type="hidden"
               :id="id"
               :name="name"
               :value="date_raw">

        <datepicker-agenda :disable-passed-days="disablePassedDays"
                           :disable-future-days="disableFutureDays"
                           :doubled="doubled"
                           :disabled-days="disabledDays"
                           :lang="lang"
                           :orientation="orientation"
                           :show="isVisible"
                           :initial-date="initialDate"
                           @change="selectDate"
                           @hide="hideDatePicker"
                           @cancel="cancelDateSelection">
        </datepicker-agenda>
    </div>
</template>

<script>
    import moment from 'moment';
    import DatepickerAgenda from './components/DatepickerAgenda.vue';

    export default {
        components: {
            'datepicker-agenda': DatepickerAgenda
        },

        props: {
            classDesign: { type: String, default: '' },
            disablePassedDays: { type: Boolean, default: false },
            disableFutureDays: { type: Boolean, default: false },
            disabledDays: { type: Array, default() { return [] } },
            doubled: { type: Boolean, default: false },
            format: { type: String, default: 'YYYY-MM-DD' },
            id: { type: String, default: 'vue-datepicker' },
            lang: { type: String, default: 'en' },
            name: { type: String, default: 'datepicker' },
            orientation: { type: String, default: 'portrait' },
            initialDate: { type: Object,
                default() {
                    return moment();
                }
            },
        },

        data () {
            return {
                date: '',
                isVisible: false
            }
        },

        computed: {
            date_formatted () {
                if (this.date) return this.date.format(this.format);
                return this.initialDate.format(this.format)
            },
            date_raw () {
                if (this.date) return this.date.format('YYYY-MM-DD')
                return this.initialDate.format('YYYY-MM-DD')
            }
        },

        mounted () {
            moment.locale(this.lang)
        },

        watch: {
            initialDate (val) {
                this.date = val
            }
        },

        methods: {
            selectDate (newDate) {
                this.date = newDate;
                this.$emit('changed', newDate)
            },

            showDatepicker () {
                this.isVisible = true;
                setTimeout( () => document.addEventListener('click', this.hideDatePicker), 0);
            },

            hideDatePicker () {
                this.isVisible = false;
                document.removeEventListener('click', this.hideDatePicker);
            },

            cancelDateSelection () {
                this.hideDatePicker();
            }
        }
    };
</script>
