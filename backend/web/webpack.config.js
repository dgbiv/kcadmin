const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const devMode = process.env.NODE_ENV !== 'production';

const entryList = {
    amazing_creator: './src/js/amazing-creator/index.js',
    dashboard: './src/dashboard/index.js',
    style: './src/styles/index.scss',
    login_style: './src/styles/login.scss',
    detail_display: './src/detail-display/index.js',
    sku: './src/sku/index.js',
    mini_program_management: './src/mini-program-management/index.js',
    spread: './src/spread/index.js'
};

const needIconfontEntries = ['style', 'login_style'];

module.exports = function (env, argv) {
    const pathToClean = argv.module ? `custom/${argv.module}.*.js` : 'custom';
    const entry = argv.module ? { [argv.module]: entryList[argv.module] } : entryList;
    const htmlWebpackPluginList = Object.keys(entry).map(entryItem => new HtmlWebpackPlugin({
        filename: `${entryItem}.html`,
        chunks: [entryItem],
        template: needIconfontEntries.includes(entryItem) ? 'src/iconfont.html' : 'src/import.html'
    }));

    return {
        entry,
        output: {
            filename: '[name].[chunkhash].js',
            path: path.resolve(__dirname, 'custom'),
            publicPath: '/custom/'
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'babel-loader'
                    }
                },
                {
                    test: /\.scss$/,
                    use: ["style-loader", "css-loader", "resolve-url-loader", "sass-loader?sourceMap",
                        {
                            loader: 'sass-resources-loader',
                            options: {
                                resources: './src/styles/variables.scss'
                            }
                        }]
                },
                {
                    test: /\.css$/,
                    use: ["style-loader", "css-loader"]
                },
                {
                    test: /\.(png|jpg|gif|eot|svg|ttf|woff2?)(\?.*)?$/i,
                    use: [
                        {
                            loader: 'url-loader',
                            options: {
                                limit: 10000
                            }
                        },
                        'file-loader'
                    ]
                }
            ]
        },
        plugins: [
            new CleanWebpackPlugin(pathToClean, {watch: true}),
            ...htmlWebpackPluginList,
        ]
    };
};