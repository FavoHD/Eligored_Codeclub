//Scrollen deaktivieren START
window.addEventListener('scroll', function(e) {
    e.preventDefault();
}, false);

window.onscroll = function() {
    window.scrollTo(0, 0);
};
//Scrollen deaktivieren ENDE

var is_playing;
var world_id;

function init(){
    gameWidth = document.getElementById("canvas").width;
    gameHeight = document.getElementById("canvas").height;


	var xhr_getWorldId = new XMLHttpRequest();
	xhr_getWorldId.open("POST", "getWorldId.php");
	xhr_getWorldId.setRequestHeader("Content-Type", "application/json");
	xhr_getWorldId.send();
	xhr_getWorldId.onreadystatechange = function () {
   		if (xhr_getWorldId.readyState == 4 && xhr_getWorldId.status == 200) {
     		var world_id_obj = JSON.parse(xhr_getWorldId.responseText);

			if (world_id_obj.status == "success") {
				world_id = world_id_obj.world_id;

				const toSend = {
					world_id : world_id,
				};

				var jsonString = JSON.stringify(toSend);
				var xhr_getWorldById = new XMLHttpRequest();
				xhr_getWorldById.open("POST", "getWorldById.php");
				xhr_getWorldById.setRequestHeader("Content-Type", "application/json");
				xhr_getWorldById.send(jsonString);

				var world_obj = JSON.parse(xhr_getWorldById.responseText);

				if (world_obj.status == "success") {
					world = world_obj.world;
					console.log(world);
				}
			}
    	}
   	}



	is_playing = true;

    map_object = [];
    map = new Map();
    map_object[map_object.length] = new Player(755, 380, 90, 140, 1, null);       //(xPos, yPos, width, height, speed, texture)
    map_object[map_object.length] = new TestObject(100,100,500,50);     //(xPos, yPos, width, height)
    map_object[map_object.length] = new TestObject(1000,1000,100,100);      //(xPos, yPos, width, height)

    fps=60;

    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');

    window.addEventListener('resize', resize);

    setInterval(function() {
        render();
    }, 1000/fps);
}

function resize() {
    gameWidth = document.getElementById("canvas").width;
    gameHeight = document.getElementById("canvas").height;
}

function render() {
    ctx.clearRect(0, 0, gameWidth, gameHeight);

    map.draw();
}

class Map {
    constructor() {
        this.shiftX = 0;
        this.shiftY = 0;
    }

    draw() {
        for(var i=0; i<map_object.length; i++) {
            map_object[i].draw();
        }
    }
}

class MapObject {
    constructor(xPos, yPos, width, height, texture) {
        this.xPos = xPos;
        this.yPos = yPos;
        this.width = width;
        this.height = height;
        this.texture = texture;
    }

    draw() {
        if((this.texture === null)||(!this.texture)){
            ctx.fillStyle = "#000";
            ctx.fillRect(this.xPos+map.shiftX, this.yPos+map.shiftY, this.width, this.height);
        }

        ctx.font = "30px Arial";
        ctx.fillText("X:"+this.xPos+" ,Y:"+this.yPos, this.xPos+map.shiftX, this.yPos+map.shiftY-10);
    }


}

class Entity extends MapObject {
    constructor(xPos, yPos, width, height, texture) {
        super(xPos, yPos, width, height, texture);
    }
}

class Player extends Entity {
    constructor(xPos, yPos, width, height, speed, texture) {
        super(xPos, yPos, width, height, texture);

        this.speed = speed;
        this.is_colided;
        this.inventory = new Inventory();

        this.forward_key = 87;
        this.left_key = 65;
        this.backwards_key = 83;
        this.right_key = 68;

        this.eventChecker = setInterval(() => {
            this.on_w_pressed();
            this.on_a_pressed();
            this.on_s_pressed();
            this.on_d_pressed();
        }, 1);

        this.saveUserDataIntervall = setInterval(() => {
            this.saveUserData();
        }, 100);
    }

    //Events
    on_w_pressed() {
        if((keyboard.getKey(this.forward_key) === true)&&(is_playing === true)) {
            for(var i=0; i<map_object.length; i++) {
                if(isColided(this, map_object[i], 0, -this.speed) === true) {
                    return;
        	    }
            }
            this.moveForward();
        }
    }

    on_a_pressed() {
        if((keyboard.getKey(this.left_key) === true)&&(is_playing === true)) {
            for(var i=0; i<map_object.length; i++) {
                if(isColided(this, map_object[i], -this.speed, 0) === true) {
                    return;
        	    }
            }
            this.moveLeft();
        }
    }

    on_s_pressed() {
        if((keyboard.getKey(this.backwards_key) === true)&&(is_playing === true)) {
            for(var i=0; i<map_object.length; i++) {
                if(isColided(this, map_object[i], 0, this.speed) === true) {
                    return;
        	    }
            }
            this.moveBackwards();
        }
    }

    on_d_pressed() {
        if((keyboard.getKey(this.right_key) === true)&&(is_playing === true)) {
            for(var i=0; i<map_object.length; i++) {
                if(isColided(this, map_object[i], this.speed, 0) === true) {
                    return;
        	    }
            }
            this.moveRight();
        }
    }

    on_colision() {
        for(var i=0; i<map_object.length; i++) {
            if(isColided(this, map_object[i]) === true) {
                //console.log("colision");
                this.is_colided = true;
                return;
    	    }
        }
        //console.log("no colision");
        this.is_colided = false;
    }


    //Methoden
    moveForward() {
        this.yPos -= this.speed;
        map.shiftY += this.speed;
    }

    moveLeft() {
        this.xPos -= this.speed;
        map.shiftX += this.speed;
    }

    moveBackwards() {
        this.yPos += this.speed;
        map.shiftY -= this.speed;
    }

    moveRight() {
        this.xPos += this.speed;
        map.shiftX -= this.speed;
    }

    saveUserData() {
        var xPos = this.xPos;
        var yPos = this.yPos;

        var toSend = {
            xPos : xPos,
            yPos: yPos,
        };

        var jsonString = JSON.stringify(toSend);
        var xhr_saveUserData = new XMLHttpRequest();
        xhr_saveUserData.open("POST", "saveUserData.php");
        xhr_saveUserData.setRequestHeader("Content-Type", "application/json");
        xhr_saveUserData.send(jsonString);
    }
}

class Inventory {
    constructor() {

    }

    draw() {

    }
}

class DestroyableObsticles extends MapObject {
    constructor(xPos, yPos, width, height, texture) {
        super(xPos, yPos, width, height, texture);

        this.eventChecker = setInterval(() => {
            this.on_click();
        }, 1);
    }

    //Events
    on_click() {

    }
}

class TestObject extends DestroyableObsticles {
    constructor(xPos, yPos, width, height, texture) {
        super(xPos, yPos, width, height, texture);
    }
}

init();
