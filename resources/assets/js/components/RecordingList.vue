<template>
    <div>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" v-model="all" @change="selectAll" />
                    </th>
                    <th class="text-center">
                        Filename - create datetime
                    </th>
                    <th class="text-center">
                        Status
                    </th>
                    <th class="text-center">
                        Play
                    </th>
                    <th class="text-center">
                        Link
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="recordings.length <= 0">
                    <td colspan="5">
                        No call recordings yet.
                    </td>
                </tr>
                <tr v-for="(recording, index) in recordings" :class="{ 'selected' : checkboxes.indexOf(index) >= 0}">
                    <td class="text-center">
                        <input type="checkbox" :value="index" v-model="checkboxes" />
                    </td>
                    <td class="text-center">
                        <strong>{{ recording.file_name + '.' + recording.type }}</strong> {{ recording.created_at }}
                    </td>
                    <td class="">
                        <span class="status text-center" :class="recording.status.toLowerCase()">
                            {{ recording.status }}
                        </span>
                    </td>
                    <td>
                        <audio controls>
                            <source :src="recording.url" :type="'audio/mp3'">
                        </audio>
                    </td>
                    <td class="text-nowrap">
                        <button class="btn-link btn" @click="showModal(recording)">Open Transcription</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-secondary float-right btn-sm" @click="deleteSelected()"><i class="far fa-trash-alt"></i> Delete Selected</button>

        <b-modal :title="modal.title" size="lg" hide-footer v-model="modal.show" no-close-on-backdrop lazy>
            <div v-if="modal.show">
                <div class="row">
                    <h5 class="col-md-12">File: {{ selectedRecording.file_name + '.' + selectedRecording.type }}</h5>
                    <audio class="col-md-12" controls>
                        <source :src="selectedRecording.url" :type="'audio/mp3'">
                    </audio>
                </div>
                <hr/>
                <div class="row">
                    <h5 class="col-md-12">Call Transcription</h5>
                    <div class="transcription-text col-md-12">
                        <transcription-text :id="selectedRecording.id"></transcription-text>
                    </div>
                </div>
            </div>

        </b-modal>
    </div>
</template>

<script>
    import TranscriptionText from "./TranscriptionText";
    export default {
        name: "RecordingList",
        components: {TranscriptionText},
        props: [
            'recordings'
        ],
        data(){
            return {
                checkboxes: [],
                all: false,
                modal : {
                    title: 'Call Recording Transcription',
                    show: false,
                    hideClose: false,
                },
                selectedRecording : null,
            }
        },
        methods: {
            showModal : function(recording){
                this.modal.show = true;
                this.selectedRecording = recording;
            },
            selectAll : function(){
                let self = this;
                this.checkboxes = [];
                if(this.all) {
                    this.recordings.forEach(function(value, index){
                        self.checkboxes.push(index);
                    });
                }
            },
            deleteSelected : function(){
                let self = this;
                let idToDelete = []; //array for recording ids to be deleted
                let tmpRecordings = this.recordings.slice(0);
                if(self.checkboxes.length <= 0){
                    alert('Nothing selected.');
                    return;
                }

                if(confirm('Are you sure you want to delete these call recordings?')) {
                    self.checkboxes.sort();
                    self.checkboxes.reverse();

                    self.checkboxes.forEach(function(index){
                        let toDelete = tmpRecordings.splice(index, 1);
                        idToDelete.push(toDelete[0].id);
                    });

                    axios.delete('file/' + idToDelete)
                        .then(function(){
                            alert('Call recordings deleted.');
                            self.recordings = tmpRecordings;
                            self.cleanDeleted();
                            self.$emit('files-deleted', self.recordings);
                        }).catch(function(error){
                            alert(error.response.data.message);
                    });
                }
            },
            cleanDeleted: function(){
                this.checkboxes = [];
                if(this.recordings.length <= 0){
                    this.all = false;
                }
            }
        }
    }
</script>

<style scoped>
    .status{
        display: block;
        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        padding: 5px;
    }
    .status.incomplete{
        background: #e9605c;
        color: #ffffff;
    }
    .status.complete{
        background: #38c172;
        color: #000000;
    }

    tr.selected{
        background: #adb5bd;
    }

</style>