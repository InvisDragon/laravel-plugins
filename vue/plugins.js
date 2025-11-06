// Plugins init
window.pluginComponents = {};
window.registerPluginComponent = function( hookName, component ) {
    if(!window.pluginComponents[hookName]) {
        window.pluginComponents[hookName] = [];
    }
    window.pluginComponents[hookName].push( component );
    document.dispatchEvent( new CustomEvent( 'newPluginComponent' ) );
}
