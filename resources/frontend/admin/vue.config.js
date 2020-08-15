module.exports = {
    devServer: {
        proxy: "http://memi4.frontend/admin/",
        port: 9091
    },
    // output built static files to Laravel's public dir.
    // note the "build" script in package.json needs to be modified as well.
    outputDir: '../../../public/assets/admin',

    publicPath: process.env.NODE_ENV === 'production'
        ? '/assets/admin/'
        : '/admin',

    // modify the location of the generated HTML file.
    indexPath: process.env.NODE_ENV === 'production'
        ? '../../../resources/views/admin.blade.php'
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
}
