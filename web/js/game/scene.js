/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define(["jquery", "three"], function ($, Three) {


    var gameDiv = $('#game');

    var initialized = false;

    var scene, camera, renderer, loader, textureLoader;
    var submarine;

    function initialize() {

        scene = new Three.Scene();
        scene.add( new THREE.AmbientLight( 0xcccccc ) );

        camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);


        renderer = new THREE.WebGLRenderer();

        renderer.setClearColor( 0x263a48 );
        loader = new THREE.JSONLoader();
        submarine = null;
        textureLoader = new THREE.TextureLoader();
        textureLoader.load(
            // resource URL
            'img/skins/submarine-uv.png',
            // Function when resource is loaded
            function ( texture ) {
                // do something with the texture

                var material = new THREE.MeshBasicMaterial({
                    map: texture
                });
                loader.load( "js/game/objects/submarine2.json", function(geometry, materials){

                    submarine = new THREE.Mesh( geometry, material);
                    submarine.scale.set( 10, 10, 10 );

                    scene.add(submarine);

                    initialized = true;
                });
            }
        );
        renderer.setSize(window.innerWidth, window.innerHeight);

        gameDiv.append(renderer.domElement);

        camera.position.z = 90;
        camera.position.y = 10;

        render();
    }


    $(window).on('resize', function (e) {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    var lastframe = undefined;

    var render = function (time) {
        if(lastframe === undefined) {
            lastframe = time;
        }
        var delta = time - lastframe;

        requestAnimationFrame(render);
        if(initialized === false) {
            return;
        }
        submarine.rotation.x += 0.25 * Math.PI * (delta / 1000); //45° per second

        renderer.render(scene, camera);
        lastframe = time;
    };

    return {
        init: initialize
    }
});