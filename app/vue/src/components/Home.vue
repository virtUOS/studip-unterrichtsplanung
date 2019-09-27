<template>
    <div class="home">
        <h1><span class="nav home"></span></h1>
        <div class="content-wrapper">
            <nav class="homebox plans">
                <header>Plan bearbeiten</header>
                <router-link to="/plan/1"><p class="homebox-link">Plan A</p></router-link>
            </nav>
            <nav class="homebox add-plan">
                <header>Plan hinzufügen</header>
                <router-link to="/addplan/default"><p class="homebox-link">Bildungswissenschaftlich</p></router-link>
                <router-link to="/addplan/math"><p class="homebox-link">Fachdidaktik Mathematik</p></router-link>
                <router-link to="/addplan/sport"><p class="homebox-link">Fachdidaktik Sport</p></router-link>
                <router-link to="/addplan/geo"><p class="homebox-link">Fachdidaktik Geologie</p></router-link>
            </nav>
            <InfoBox />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';

export default {
    name: 'Home',
    components: {
        InfoBox
    }
};
</script>
