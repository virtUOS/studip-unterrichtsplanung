<template>
  <div class="note-element">
      <header class="note-element-title">{{element.title}}</header>
      <textarea class="note-element-content" :value="element.content" />
  </div>
</template>

<script>
export default {
    name: 'NoteElement',
    props: {
        element: Object
    },
    mounted() {
    }
}
</script>
