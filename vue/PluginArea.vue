<template>

    <component v-for="component in components" :is="component" :context="context" />

</template>
<script>
export default {
    name: 'PluginArea',
    props: [ 'hook', 'context' ],
    data() {
        let components = [];

        if(window.pluginComponents[this.hook]) {
            components = window.pluginComponents[this.hook];
        }

        return {
            components: components,
        }
    }
};
</script>
