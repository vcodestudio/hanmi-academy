const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const webpack = require("webpack");
module.exports = {
  entry: {
    index: "./src/js/index.js",
  },
  output: {
    path: path.resolve(__dirname, "src/js/dist"),
    filename: "app.js",
    clean: true,
  },
  resolve: {
    alias: {
      vue: "vue/dist/vue.js",
    },
  },
  plugins: [
    new MiniCssExtractPlugin({
      linkType: false,
      filename: "app.css",
      chunkFilename: "app-hash.css",
    }),
  ],
  module: {
    rules: [
      {
        test: /(\.css$)/,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
      {
        test: /\.(less)$/,
        use: [MiniCssExtractPlugin.loader, "css-loader", "less-loader"],
      },
    ],
  },
};
