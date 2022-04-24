<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="h2 text-center">Generated Fixtures</div>
                <div class="row">
                    <div v-for="(fixtures,index) in tours" :key="index" class="col-md-3 mb-3">
                        <FixtureCard :fixtures="fixtures" :week="index"></FixtureCard>
                    </div>
                </div>

                <button class="mt-3 btn btn-lg btn-success" @click="startSimulation">Start Simulation</button>
            </div>
        </div>
    </div>
</template>

<script>

import router from "../router";
import FixtureCard from "../components/FixtureCard";

export default {
    name: "Schedule",
    components: {
        FixtureCard
    },
    data() {
        return {
            tours: []
        }
    },
    mounted() {
        this.getFixtures();
    },
    methods: {
        getFixtures() {
            axios.get('/api/fixtures')
                .then((response)=>{
                    if (response.status !== 200) {
                        alert("/api/fixtures ERROR RESPONSE: " + response.data.message)
                        return;
                    }
                    this.tours = response.data.data
                })
        },
        startSimulation() {
            router.replace('simulation')
        }
    }
}
</script>

<style scoped>

</style>
