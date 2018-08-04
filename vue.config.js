const yaml = require('read-yaml');
const config = yaml.sync('.stilus.yml');

module.exports = {
    outputDir: config.dashboard.build.dir,
    devServer: {
        port: config.dashboard.web_server.port || 8080,
        host: config.dashboard.web_server.host || "0.0.0.0",
    },
    css: {
        sourceMap: config.dashboard.build.css_source_map || true,
        extract: false
    },
    pages: config.dashboard.build.pages,
};
