<template>
  <section class="page page--restauracia-produkt-detail">
    <h1>Detail produktu</h1>
    <p>Vzorová stránka detailu produktu. ID: {{ $route.params.id }}</p>
  </section>
</template>

<script>
export default {
  name: 'RestauraciaProduktDetailView',
  props: {
    id: {
      type: String,
      required: false,
    },
  },
};
</script>
