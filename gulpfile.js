// In the global configuration object, keys are application names to be
// compiled, and values are arrays of theme names to build for that application
global.config = {
    'Osm_Tools': [],
    'Osm_Framework_Samples': ['_front__tailwind_samples']
};

// Run the framework Gulp scripts that define all the Gulp tasks, and
// export these tasks to the Gulp runner
Object.assign(exports, require('./gulp/main'));