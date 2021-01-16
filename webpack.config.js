const Encore = require('@symfony/webpack-encore');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

const isProduction = Encore.isProduction();

Encore
    .setOutputPath('assets/')

    .setPublicPath('./')
    .setManifestKeyPrefix('./')

    .addStyleEntry('style', './resources/scss/style.scss')
    .addEntry('theme', './resources/ts/theme.ts')
    .addEntry('admin', './resources/ts/admin.ts')
    .enableTypeScriptLoader()
    .enableSassLoader()

    //.enableEslintLoader()

    .enableForkedTypeScriptTypesChecking()
    .enablePostCssLoader()
    .enableSourceMaps(!isProduction)
    .disableSingleRuntimeChunk()
    .addPlugin(new DependencyExtractionWebpackPlugin())
    .splitEntryChunks();

Encore.cleanupOutputBeforeBuild(!isProduction ? ['*.js', '*.css'] : '');

module.exports = Encore.getWebpackConfig();