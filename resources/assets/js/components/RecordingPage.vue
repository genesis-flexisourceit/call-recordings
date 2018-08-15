<template>
    <div class="container py-4">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">
                    Call Recording Transcriber
                    <a class="btn btn-link float-right btn-sm" href="javascript:void(0)" @click="showModal">Upload</a>
                </div>

                <div class="card-body">
                    <select-file></select-file>
                    <recording-list :recordings="recordings"></recording-list>
                </div>
            </div>
        </div>
        <b-modal :title="modal.title" hide-footer v-model="modal.show" no-close-on-backdrop lazy>
            <upload-file></upload-file>
        </b-modal>
    </div>
</template>

<script>
    import SelectFile from "./SelectFile";
    import UploadFile from "./UploadFile";
    import RecordingList from "./RecordingList";
    export default {
        name: "RecordingPage",
        components: {RecordingList, UploadFile, SelectFile},
        data(){
            return {
                recordings : [],
                modal : {
                    title: 'Upload New File',
                    show: false,
                }
            }
        },
        methods: {
            showModal : function(){
                this.modal.show = true;
            },
        },
        beforeMount(){
            //Assign recordings injected from the home.blade
            this.recordings = Laravel.recordings;
        }
    }
</script>

<style scoped>

</style>