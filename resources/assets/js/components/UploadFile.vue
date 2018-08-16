<template>
    <div>
        <form @submit.prevent="upload">
            <div v-if="alert.show" class="alert" :class="[alert.class]" role="alert" v-html="alert.message">
            </div>
            <div class="form-group">
                <input type="file" required class="form-control" ref="file" v-on:change="handleFile()" accept="audio/*" :disabled="btn.loading"  />
            </div>
            <button type="submit" class="btn btn-primary" :disabled="btn.loading">{{ btn.text }}</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "UploadFile",
        data(){
            return {
                alert: {
                    show: false,
                    class: 'alert-primary',
                    message: null,
                },
                btn: {
                    text : 'Upload',
                    loading: false,
                },
                file: '',
            }
        },
        methods: {
            resetAlert: function(){
                this.alert = {
                    show: false,
                    class: 'alert-primary',
                    message: null,
                };
            },
            upload: function(){
                this.btn = {
                    text : 'Uploading...',
                    loading: true,
                };

                this.$emit('loading');

                let self = this;
                let formData = new FormData();
                formData.append('file', this.file);

                axios.post('file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (response) {
                    self.alert = {
                        show : true,
                        class : 'alert-success',
                        message : response.data.message
                    };
                    let callback = Object.assign({}, response.data.recording);
                    self.$emit('file-uploaded', callback);

                }).catch(function(error){
                    self.alert = {
                        show : true,
                        class : 'alert-danger',
                        message : error.response.data.message
                    }
                }).finally(function () {
                    self.btn = {
                        text : 'Upload',
                        loading: false,
                    };
                    self.$emit('loaded');
                });
            },
            handleFile: function(){
                this.file = this.$refs.file.files[0];
            },
        }
    }
</script>

<style scoped>
.form-control {
    height: auto;
}
</style>