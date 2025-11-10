new Vue({
    el: '#app',
    data() {
        return {
            name: '',
            email: '',
            radio: '',
            textarea: '',
            submitted: false,
            errors: {
                name: false,
                email: false,
                radio: false,
                textarea: false
            }
        };
    },
    computed: {
        helpType(){
            if(this.radio === 'delete'){
                return '削除理由'
            }else{
                return 'お問い合わせ内容'
            }
        },
        textareaPlaceholder(){
            if(this.radio === 'delete'){
                return '削除したい理由をご記入ください'
            }else if(this.radio === 'other'){
                return 'お問い合わせ内容をご記入ください'
            }
        }
    },
    methods: {
        validateField(field) {
            if (!this.submitted) return;

            if (field === 'name') {
                this.errors.name = this.name.trim() === '';
            }
            if (field === 'email') {
                const regex = new RegExp(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i)
                this.errors.email = !regex.test(this.email);
            }
            if (field === 'radio') {
                this.errors.radio = this.radio === '';
            }
            if (field === 'textarea') {
                this.errors.textarea = this.textarea.trim() === '';
            }
        },
        clearError(field) {
            if (this.errors[field]) {
                this.errors[field] = false;
            }
        },
        handleSubmit() {
            this.submitted = true;
            this.validateField('name');
            this.validateField('email');
            this.validateField('radio');
            this.validateField('textarea');
            if (
                !this.errors.name &&
                !this.errors.email &&
                !this.errors.radio &&
                !this.errors.textarea
            ) {
                this.$refs.helpForm.submit();
            }
        }
    }
});