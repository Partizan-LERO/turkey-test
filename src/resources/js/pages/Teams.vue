<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="h2">Tournament Teams</div>
                <div>
                    <table class="table table-striped table-bordered">
                        <thead>
                                <th>Team Name</th>
                        </thead>
                        <tbody>
                            <tr v-for="team in teams" :key="team.id">
                                <td>{{team.name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button v-if="!areFixturesExist" class="btn btn-lg btn-success" @click="generateFixtures">Generate fixtures</button>
                <button v-if="areFixturesExist" class="btn btn-lg btn-success" @click="goToSchedule">Go to fixtures</button>
            </div>
        </div>
    </div>
</template>

<script>

import router from "../router";

export default {
    name: "Teams",
    data() {
        return {
            teams: [],
            areFixturesExist: false,
        }
    },
    mounted() {
        this.areFixturesGenerated();
        this.getTeamsList();
    },
    methods: {
        goToSchedule() {
            router.replace('schedule');
        },
        getTeamsList() {
            axios.get('/api/teams')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/teams ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.teams = response.data.data
                })
        },
        areFixturesGenerated() {
            axios.get('/api/fixtures/exist')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/fixtures/exist ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.areFixturesExist = response.data.data.exist
                })
        },
        generateFixtures() {
            axios.post('/api/fixtures/generate')
                .then((response)=>{
                    let status = response.status
                    if (status !== 201) {
                        console.error("/api/fixtures/generate ERROR RESPONSE: " + response.data.message)
                        return
                    }

                    router.replace('schedule')
                })
        }
    }
}
</script>

<style scoped>

</style>
