<template>

    <component v-for="component in components" :is="component" :context="context" />

</template>
<script>
export default {
    name: 'PluginArea',
    props: [ 'hook', 'context' ],
    data() {
        return {
            components: [],
        }
    },
    methods: {
        getComponents() {
            let components = [];

            if(window.pluginComponents && window.pluginComponents[this.hook]) {
                components = window.pluginComponents[this.hook];
            }

            this.components = components;
        }
    },
    mounted() {
        this.getComponents();
        this.componentListener = () => {
            this.getComponents();
        };
        document.addEventListener('newPluginComponent', this.componentListener);
    },
    unmounted() {
        document.removeEventListener('newPluginComponent', this.componentListener);
    }
};
</script>
