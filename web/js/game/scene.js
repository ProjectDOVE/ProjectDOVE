/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(["jquery", "three"], function ($, Three) {


    var gameDiv = $('#game');

    var scene = new Three.Scene();
    scene.add( new THREE.AmbientLight( 0xcccccc ) );

   // scene.add( new THREE.AxisHelper( 100 ) );
    var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);


    var renderer = new THREE.WebGLRenderer();


    var loader = new THREE.JSONLoader();
var submarine = null;
    var textureLoader = new THREE.TextureLoader();
    textureLoader.load(
        // resource URL
        'img/skins/submarine-uv.png',
        // Function when resource is loaded
        function ( texture ) {
            // do something with the texture
            var material = new THREE.MeshBasicMaterial({
                map: texture
            });
            loader.load( "js/game/objects/submarine.json", function(geometry, materials){

                submarine = new THREE.Mesh( geometry, material);
                submarine.scale.set( 10, 10, 10 );

                scene.add(submarine);
            });
        }
    );



    renderer.setSize(window.innerWidth, window.innerHeight);


    $(window).on('resize', function (e) {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(window.innerWidth, window.innerHeight);


    });

    gameDiv.append(renderer.domElement);


    camera.position.z = 100;
    camera.position.y = 10;

    var render = function () {
        requestAnimationFrame(render);
        submarine.rotation.y += 0.001;
        renderer.render(scene, camera);
    };

    return {
        render: render
    }
});