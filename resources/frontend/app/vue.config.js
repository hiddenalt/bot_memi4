module.exports = {

    devServer: {
        proxy: "http://memi4.frontend/menu/",
        port: 9090
    },

    // output built static files to Laravel's public dir.
    // note the "build" script in package.json needs to be modified as well.
    outputDir: '../../../public/assets/app',

    publicPath: process.env.NODE_ENV === 'production'
        ? '/assets/app/'
        : '/menu',

    // modify the location of the generated HTML file.
    indexPath: process.env.NODE_ENV === 'production'
        ? '../../../resources/views/app.blade.php'
        : 'index.html',

    lintOnSave: false,
    transpileDependencies: [
        "vuetify"
    ],

    chainWebpack: config => {
        config
            .plugin('html')
            .tap(args => {
                args[0].title = '...';
                return args;
            });
    }
};
