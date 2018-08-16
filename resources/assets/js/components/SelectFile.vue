<template>
    <div class="pb-4">
        <div class="row">
            <div class="col-md-6">
                <v-select class="custom-form-control" placeholder="Select audio to transcribe" :options="selection" v-model="selected" :required="true"></v-select>
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary">Select Audio file</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SelectFile",
        props: [
            'recordings'
        ],
        data(){
            return {
                selected: null,
                selection : []
            }
        },
        beforeMount(){
            let self = this;
            self.recordings.forEach(function(recording){
                self.selection.push({
                    label : recording.file_name + '.' + recording.type,
                    value: recording.id,
                });
            });
        }
    }
</script>

<style scoped>

</style>