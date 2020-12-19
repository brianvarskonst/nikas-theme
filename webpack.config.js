const Encore = require('@symfony/webpack-encore');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

const isProduction = Encore.isProduction();

Encore
    .setOutputPath('assets/')

    .setPublicPath('./')
    .setManifestKeyPrefix('./')

    .addEntry('theme', './resources/ts/theme.ts')
    .addEntry('style', './resources/scss/style.scss')
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