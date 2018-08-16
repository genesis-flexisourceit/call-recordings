<template>
    <div class="container py-4">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">
                    Call Recording Transcriber
                    <a class="btn btn-link float-right btn-sm" href="javascript:void(0)" @click="showModal"><i class="fas fa-upload"></i> Upload</a>
                </div>

                <div class="card-body">
                    <!--<select-file :recordings="recordings"></select-file>-->
                    <recording-list :recordings="recordings" v-on:files-deleted="updateList"></recording-list>
                </div>
            </div>
        </div>
        <b-modal :title="modal.title" hide-footer v-model="modal.show" no-close-on-backdrop lazy :hide-header-close="modal.hideClose">
            <upload-file v-on:loading="modalLoading" v-on:loaded="modalLoaded" v-on:file-uploaded="fileUploaded"></upload-file>
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
                recordingSelection: [],
                modal : {
                    title: 'Upload New File',
                    show: false,
                    hideClose: false,
                },
            }
        },
        methods: {
            showModal : function(){
                this.modal.show = true;
            },
            modalLoading: function(){
                this.modal.hideClose = true;
            },
            modalLoaded: function(){
                this.modal.hideClose = false;
            },
            fileUploaded: function(recording){
                this.recordings.push(recording);
            },
            pushToRecordingSelection: function(recording){
                this.recordingSelection.push({
                    label : recording.file_name + '.' + recording.type,
                    value: recording.id,
                });
            },
            updateList: function(recordings){
                this.recordings = recordings;
            }
        },
        beforeMount(){
            let self = this;
            //Assign recordings injected from the home.blade
            self.recordings = Laravel.recordings;
        }
    }
</script>

<style scoped>

</style>