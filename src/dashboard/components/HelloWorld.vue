<template>
  <div>
    <mu-container>
        <h1>{{message}}</h1>
        <router-link to="/"><mu-button color="primary">Go to main</mu-button></router-link>
    </mu-container>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  props: {
      message: String
  }
}
</script>

<style>

</style>
