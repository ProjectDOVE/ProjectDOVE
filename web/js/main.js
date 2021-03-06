/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

requirejs.config({
    //By default load any module IDs from js/vendor
    baseUrl: "js/vendor",
    //except, if the module ID starts with "page",
    //load it from the js/page directory. paths
    //config is relative to the baseUrl, and
    //never includes a ".js" extension since
    //the paths config could be for a directory.
    paths: {
        page: "../page",
        jquery: "jquery.min"
    },
    // Add this map config in addition to any baseUrl or
    // paths config you may already have in the project.
    map: {
      // '*' means all modules will get 'jquery-private'
      // for their 'jquery' dependency.
      '*': { 'jquery': 'jquery-private' },

      // 'jquery-private' wants the real jQuery module
      // though. If this line was not here, there would
      // be an unresolvable cyclic dependency.
      'jquery-private': { 'jquery': 'jquery' }
    }
});

//Do stuff that needs to be done outside of the game here