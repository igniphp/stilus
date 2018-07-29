<template>
    <div>
        <HelloWorld v-bind:message="message" />
    </div>
</template>

<script>
import HelloWorld from '../components/HelloWorld';

export default {
    name: 'Home',
    components: {HelloWorld},
    props: {
        message: String
    }
}
</script>
