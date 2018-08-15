<template>
    <div>
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" />
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
                <tr v-for="(recording, index) in recordings" :class="{ 'selected' : checkboxes.indexOf(recording.id) >= 0}">
                    <td class="text-center">
                        <input type="checkbox" :value="recording.id" v-model="checkboxes" />
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
                            <source :src="recording.url" :type="'audio/' + recording.type">
                        </audio>
                    </td>
                    <td class="text-nowrap">
                        <a href="#">Open Transcription</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <button class="btn btn-secondary float-right" @click="deleteSelected()">Delete Selected</button>
    </div>
</template>

<script>
    export default {
        name: "RecordingList",
        props: [
            'recordings'
        ],
        data(){
            return {
                checkboxes: [],
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