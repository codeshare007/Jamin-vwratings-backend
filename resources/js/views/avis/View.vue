<template>
  <EntitiesView :id="id" entity="avi" method="avis" />
</template>
<script>
import EntitiesView from "../../components/entities/EntitiesView";
export default {
  props: ['id'],
  components: {
    EntitiesView
  }
}
</script>
