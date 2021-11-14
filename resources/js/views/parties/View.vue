<template>
  <EntitiesView :id="id" entity="party" method="parties" />
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
