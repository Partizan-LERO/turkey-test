<template>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Week {{week}}</h5>
        </div>
        <div class="card-body">
            <div v-for="(game, i) in fixtures" :key="game.id">
                <hr v-if="i > 0" />
                {{game.home_team_name}} - {{game.away_team_name}}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
      'fixtures',
      'week'
    ],
    name: "FixtureCard"
}
</script>

<style scoped>

</style>
