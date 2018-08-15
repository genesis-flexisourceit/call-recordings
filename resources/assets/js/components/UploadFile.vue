<template>
    <div>
        <form @submit.prevent="upload">
            <div v-if="alert.show" class="alert" :class="[alert.class]" role="alert" v-html="alert.message">
            </div>
            <div class="form-group">
                <input type="file" required class="form-control" ref="file" v-on:change="handleFile()" accept="audio/*"  />
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
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
                    }
                }).catch(function(error){
                    self.alert = {
                        show : true,
                        class : 'alert-danger',
                        message : error.response.data.message
                    }
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