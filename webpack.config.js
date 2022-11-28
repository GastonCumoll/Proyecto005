const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('indiceJs', './assets/indiceJs.js')
    .addEntry('normaEdit', './assets/normaEdit.js')
    .addEntry('normaEditTexto', './assets/normaEditTexto.js')
    .addEntry('generarPDF','./assets/generarPDF.js')
    .addEntry('busquedaJs','./assets/busquedaJs.js')
    .addEntry('ordenamiento','./assets/ordenamiento.js')
    .addEntry('busquedaParamNorma','./assets/busquedaParamNorma.js')
    .addEntry('busquedaParamEtiqueta','./assets/busquedaParamEtiqueta.js')
    .addEntry('busquedaParamItem','./assets/busquedaParamItem.js')
    .addEntry('etiquetasNorma', './assets/etiquetasNorma.js')
    .addEntry('etiquetasNormaEdit', './assets/etiquetasNormaEdit.js')
    .addEntry('etiqueta', './assets/etiqueta.js')
    .addEntry('datepicker', './assets/datepicker.js')
    .addEntry('redireccion', './assets/redireccion.js')
    .addEntry('consulta', './assets/consulta.js')
    .addEntry('redireccionLogout', './assets/redireccionLogout.js')
    .addEntry('scroll', './assets/scroll.js')
    .addEntry('spinnerJs', './assets/spinnerJs.js')
    .addEntry('tipoRelaNew', './assets/tipoRelaNew.js')
    .addEntry('tipoNormaNew', './assets/tipoNormaNew.js')
    .addEntry('tipoConsultaNew', './assets/tipoConsultaNew.js')
    .addEntry('relacionNew', './assets/relacionNew.js')
    
    .addStyleEntry('base', '/assets/styles/app.css')
    .addStyleEntry('estilo', '/assets/styles/estilo.css')
    .addStyleEntry('boton', '/assets/styles/boton.css')
    .addStyleEntry('busquedaAvanzada', '/assets/styles/busquedaAvanzada.css')
    .addStyleEntry('normaShow', '/assets/styles/normaShow.css')
    .addStyleEntry('createCss', '/assets/styles/create.css')
    .addStyleEntry('createTipoNorma', '/assets/styles/createTipoNorma.css')
    .addStyleEntry('inicio', '/assets/styles/inicio.css')
    .addStyleEntry('inicioAdmin', '/assets/styles/inicioAdmin.css')
    .addStyleEntry('botonKnp', '/assets/styles/botonKnp.css')
    .addStyleEntry('indexAdmin','/assets/styles/indexAdmin.css')
    .addStyleEntry('index','/assets/styles/index.css')
    .addStyleEntry('scrollCss', '/assets/styles/scroll.css')
    .addStyleEntry('formularioBusqueda', '/assets/styles/formularioBusqueda.css')
    .addStyleEntry('menuAdmin', '/assets/styles/menuAdmin.css')
    .addStyleEntry('footer', '/assets/styles/footer.css')
    .addStyleEntry('botonBarraNavegacion', '/assets/styles/botonBarraNavegacion.css')
    .addStyleEntry('barrita', '/assets/styles/barrita.css')
    .addStyleEntry('spinnerCss', '/assets/styles/spinner.css')
    .addStyleEntry('login', '/assets/styles/login.css')
    .addStyleEntry('pdf', '/assets/styles/pdf.css')



    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
