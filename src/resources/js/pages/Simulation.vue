<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="h2 text-center">Simulation</div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Team Name</th>
                                <th>P</th>
                                <th>W</th>
                                <th>D</th>
                                <th>L</th>
                                <th>GD</th>
                            </tr>
                            <tbody>
                                <tr v-for="standing in standings" :key="standing.id">
                                    <td>{{standing.name}}</td>
                                    <td>{{standing.points}}</td>
                                    <td>{{standing.win_count}}</td>
                                    <td>{{standing.draw_count}}</td>
                                    <td>{{standing.loss_count}}</td>
                                    <td>{{standing.goal_difference}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <FixtureCard :fixtures="fixtures" :week="tour > maxTour ? maxTour : tour + 1"></FixtureCard>
                    </div>
                    <div class="col-md-3">
                        <table class="table table-bordered">
                            <tr>
                                <th>Championship Predictions</th>
                                <th>%</th>
                            </tr>
                            <tr v-if="predictions.length > 0" v-for="prediction in predictions" :key="prediction.id">
                                <td>{{prediction.name}}</td>
                                <td>{{prediction.probability}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <button v-if="tour < maxTour - 1" class="mt-3 btn btn-primary" @click="simulateChampionship">Play all weeks</button>
                    </div>
                    <div class="col-md-3">
                        <button v-if="tour < maxTour - 1" class="mt-3 btn btn-primary" @click="simulateTour">Play Next Week</button>
                    </div>
                    <div class="col-md-3">
                        <button class="mt-3 btn btn-danger" @click="resetData">Reset Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import FixtureCard from "../components/FixtureCard";
import router from "../router";

export default {
    name: "Simulation",
    components: {
        FixtureCard
    },
    data() {
        return {
            tour: 1,
            fixtures: [],
            standings: [],
            predictions: [],
            maxTour: 6,
        }
    },
    async mounted() {
        await this.getTeamsList();
        await this.getCurrentTour();
        if (this.tour > this.maxTour) {
            await router.replace('/')
        }
        this.getStandings();
        if (this.tour <= this.maxTour) {
            this.getFixtures(this.tour);
        }

        if (this.tour > 3) {
            this.getPredictions();
        }
    },
    methods: {
        getCurrentTour() {
            return axios.get('/api/current-tour')
                .then((response) => {
                    if (response.status !== 200 && response.data.message === 'All games played') {
                        this.tour = this.maxTour
                    }
                    this.tour = response.data.data.tour - 1
                })
        },
        getStandings() {
            axios.get('/api/standings')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/standings ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.standings = response.data.data
                })
        },
        getPredictions() {
            axios.get('/api/predictions')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/predictions ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.predictions = response.data.data
                })
        },
        getFixtures() {
            let tour = this.tour + 1
            if (tour > this.maxTour) {
                tour = this.maxTour
            }
            axios.get('/api/fixtures/' + tour)
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/fixtures/{tour} ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.fixtures = response.data.data
                })
        },
        resetData() {
            axios.delete('/api/fixtures/reset')
                .then((response)=>{
                    if (response.status !== 204) {
                        alert("/api/fixtures/reset ERROR RESPONSE: " + response.data.message)
                        return;
                    }

                    router.replace('/');
                })
        },
        simulateTour() {
            axios.post('/api/fixtures/simulate/tour')
                .then((response)=>{
                    if (response.status !== 201) {
                        alert("/api/fixtures/simulate/tour ERROR RESPONSE: " + response.data.message)
                        return;
                    }

                    this.tour++;

                    this.getStandings();
                    this.getFixtures();
                    if (this.tour > 3) {
                        this.getPredictions();
                    }
                })
        },
        simulateChampionship() {
            axios.post('/api/fixtures/simulate')
                .then((response)=>{
                    if (response.status !== 201) {
                        alert("/api/fixtures/simulate ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.tour = this.maxTour - 1
                    this.getStandings();
                    this.getFixtures();
                    this.getPredictions();
                })
        },
        getTeamsList() {
            axios.get('/api/teams')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/teams ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.maxTour = (response.data.data.length - 1) * 2
                })
        },
    }
}
</script>

<style scoped>

</style>
