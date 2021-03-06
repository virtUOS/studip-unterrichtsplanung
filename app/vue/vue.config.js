module.exports = {
    publicPath: '../../plugins_packages/elan-ev/Unterrichtsplanung/app/views/index/',
    outputDir: '../views/index/',
    assetsDir: '../../../assets/',
    filenameHashing: false,
    pages: {
        index: {
            entry: 'src/main.js',
            template: 'public/index.php',
            filename: 'index.php'
        },
        admin: {
            entry: 'src/main-admin.js',
            template: 'public/admin.php',
            filename: 'admin.php'
        }
    }
};
