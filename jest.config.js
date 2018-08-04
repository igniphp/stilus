const yaml = require('read-yaml');
const config = yaml.sync('.stilus.yml');

module.exports = {
  moduleFileExtensions: config.dashboard.tests.module_file_extensions,
  transform: {
    '^.+\\.vue$': 'vue-jest',
    '^.+\\.tsx?$': 'ts-jest',
    "^.+\\.(css|less)$": "identity-obj-proxy",
    '^.+\\.(vue|js)$': 'babel-jest'
  },
  moduleNameMapper: config.dashboard.tests.module_name_mapper,
  snapshotSerializers: [
    'jest-serializer-vue'
  ],
  collectCoverage: config.dashboard.tests.coverage,
  collectCoverageFrom: config.dashboard.tests.coverage_match,
  coverageDirectory: config.dashboard.tests.coverage_dir,
  coverageReporters: config.dashboard.tests.coverage_reporters,
  testMatch: config.dashboard.tests.test_match,
  testURL: `http://${config.dashboard.web_server.host}:${config.dashboard.web_server.port}/`
};
