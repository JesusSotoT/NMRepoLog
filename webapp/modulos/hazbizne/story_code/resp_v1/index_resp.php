<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ComuniLog</title>

	<style>
		html { 
				overflow: hidden; 
			} 
			body { 
				position: absolute; 
				margin: 0px; 
				padding: 0px; 
				background: #000; 
				width: 100%; 
				height: 100%; 
			} 
			#screen { 
				position: absolute; 
				width: 100%; 
				height: 100%; 
				background: #000; 
				overflow: hidden; 
				font-family: Segoe UI, Verdana, Arial, Sans-Serif; 
				color: #fff; 
				font-size: 13px; 
			} 
			#screen canvas { 
				position: absolute; 
				width: 100%; 
				height: 100%; 
				background: #000;
			} 
			#info { 
		        		position: absolute; 
		        		text-align: left; 
		        		top: 50%; 
		        		left: 2%; 
				width: 180px; 
				height: 100px; 
		        		color: none; 
		        		font-size: 1em; 
			} 
			#info .background { 
				position: absolute; 
				width: 100%; 
				height: 100%; 
				background: #000; 
				opacity: 0.3; 
			} 
			#info .content { 
				position: absolute; 
				padding: 3px; 
				width: 100%; 
				height: 100%; 
			} 
			#info .w { 
				color: #fff; 				
			} 
			#info hr { 
				width: 90%; 
				border: none; 
				background-color: #666; 
				height: 1px; 
			} 
			#info h1 { 
				color: #fff; 
				text-align: center; 
			} 

		.button {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #ffffff;
			padding: 10px 20px;
			background: -moz-linear-gradient(
				top,
				#333333 0%,
				#000000);
			background: -webkit-gradient(
				linear, left top, left bottom, 
				from(#333333),
				to(#000000));
			-moz-border-radius: 1px;
			-webkit-border-radius: 1px;
			border-radius: 1px;
			border: 1px solid #000000;
			-moz-box-shadow:
				0px 1px 1px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,1);
			-webkit-box-shadow:
				0px 1px 1px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,1);
			box-shadow:
				0px 1px 1px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,1);
			text-shadow:
				0px -1px 0px rgba(000,000,000,0.7),
				0px 1px 0px rgba(255,255,255,0.3);
		}


		.button-panic {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 18px;
			color: #ffffff;
			padding: 8px 20px;
			background: -moz-linear-gradient(
				top,
				#ffffff 0%,
				#c73434 50%,
				#ff0000);
			background: -webkit-gradient(
				linear, left top, left bottom,
				from(#ffffff),
				color-stop(0.50, #c73434),
				to(#ff0000));
			-moz-border-radius: 10px;
			-webkit-border-radius: 10px;
			border-radius: 5px;
			border: 0px solid #000000;
			-moz-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 5px rgba(245,10,10,0.6);
			-webkit-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 5px rgba(245,10,10,0.6);
			box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 5px rgba(245,10,10,0.6);
			text-shadow:
				0px -1px 3px rgba(255,255,255,0.8),
				0px 1px 0px rgba(255,255,255,0.3);			
		}
	</style>


</head>
<body>
<div id="screen"> 
<canvas id="canvas">ComuniLog</canvas> 
<div id="info"> 
	<div class="background"></div> 

		<div style="display:none">
			<h1>3D Cubes</h1> 
			<table> 
				<tr><td class="w">drag</td><td>→ rotate X,Y axis</td></tr> 
				<tr><td class="w">wheel</td><td>→ rotate Z axis</td></tr> 
				<tr><td class="w">click</td><td>→ create cube</td></tr> 
			</table> 
			<hr> 
			
			<input type="checkbox" id="alpha"><label for="alpha"> transparency</label><br> 	
			<input type="checkbox" id="destroy"><label for="destroy"> destroy cubes</label><br> 
			<hr> 
			- <span id="fps" class="w">00</span> FPS<br> 
			- <span id="npoly" class="w">00</span> Faces<br> 
			<p align="center"><input type="button" value="RESET" id="reset" class="button"></input><input type="button" value="STOP" id="stopgo" class="button"></input></p> 
		</div>

		<div class="content"> 
			Opciones<br><br>
			<input type="checkbox" id="white"><label for="white"> white background</label><br> 
			<input type="checkbox" id="autor" checked="checked"><label for="autor"> auto rotation</label><br> 
			<br><br><br><br>
			<button  title="botón de pánico" id="btnpanic" class="button-panic" onclick="btnpanic_click()">!</button>
		</div> 
	</div> 
</div> 

<script>
	function btnpanic_click(){
		alert("Ha presionado el botón de panico!");
	}
</script>


<script>

	// ============================================================= 
	//           ===== CANVAS 3D experiment ===== 
	//     ===== simple 3D cubes HTML5 engine ==== 
	// script written by Gerard Ferrandez - January 2, 2012 
	// http://www.dhteumeuleu.com 
	// ============================================================= 
	 
	"use strict"; 
	 
	(function () { 
		// ======== private vars ======== 
		var scr, canvas, cubes, faces, nx, ny, nw, nh, xm = 0, ym = 0, cx = 50, cy = 50, cz = 0, cxb = 0, cyb = 0; 
		var white, alpha, fps = 0, ncube, npoly, faceOver, drag, moved, startX = 0, startY = 0; 
		var cosY, sinY, cosX, sinX, cosZ, sinZ, minZ, angleY = 0, angleX = 0, angleZ = 0; 
		var bkgColor1 = "rgba(0,0,0,0.1)"; 
		var bkgColor2 = "rgba(32,32,32,1)"; 
		var autorotate = true, destroy = false, running = true; 
		// ---- fov ---- 
		var fl = 250; 
		var zoom = 0; 
		// ======== canvas constructor ======== 
		var Canvas = function (id) { 
			this.container = document.getElementById(id); 
			this.ctx = this.container.getContext("2d"); 
			this.resize = function (w, h) { 
				this.container.width = w; 
				this.container.height = h; 
			} 
		}; 
		// ======== vertex constructor ======== 
		var Point = function (parent, xyz, project) { 
			this.project = project; 
			this.xo = xyz[0]; 
			this.yo = xyz[1]; 
			this.zo = xyz[2]; 
			this.cube = parent; 
		}; 
		Point.prototype.projection = function () { 
			// ---- 3D rotation ---- 
			var x = cosY * (sinZ * this.yo + cosZ * this.xo) - sinY * this.zo; 
			var y = sinX * (cosY * this.zo + sinY * (sinZ * this.yo + cosZ * this.xo)) + cosX * (cosZ * this.yo - sinZ * this.xo); 
			var z = cosX * (cosY * this.zo + sinY * (sinZ * this.yo + cosZ * this.xo)) - sinX * (cosZ * this.yo - sinZ * this.xo); 
			this.x = x; 
			this.y = y; 
			this.z = z; 
			if (this.project) { 
				// ---- point visible ---- 
				if (z < minZ) minZ = z; 
				this.visible = (zoom + z > 0); 
				// ---- 3D to 2D projection ---- 
				this.X = (nw * 0.5) + x * (fl / (z + zoom)); 
				this.Y = (nh * 0.5) + y * (fl / (z + zoom)); 
			} 
		}; 
		// ======= polygon constructor ======== 
		var Face = function (cube, index, normalVector) { 
			// ---- parent cube ---- 
			this.cube = cube; 
			// ---- coordinates ---- 
			this.p0 = cube.points[index[0]]; 
			this.p1 = cube.points[index[1]]; 
			this.p2 = cube.points[index[2]]; 
			this.p3 = cube.points[index[3]]; 
			// ---- normal vector ---- 
			this.normal = new Point(this, normalVector, false) 
			// ---- # faces ---- 
			npoly++; 
			document.getElementById('npoly').innerHTML = npoly; 
			//document.getElementById('npoly').innerHTML  = "PRUEBA";
		}; 
		Face.prototype.pointerInside = function () { 
			// ---- Is Point Inside Triangle? ---- 
			// http://2000clicks.com/mathhelp/GeometryPointAndTriangle2.aspx 
			var fAB = function (p1, p2, p3) { return (ym - p1.Y) * (p2.X - p1.X) - (xm - p1.X) * (p2.Y - p1.Y); }; 
			var fCA = function (p1, p2, p3) { return (ym - p3.Y) * (p1.X - p3.X) - (xm - p3.X) * (p1.Y - p3.Y); }; 
			var fBC = function (p1, p2, p3) { return (ym - p2.Y) * (p3.X - p2.X) - (xm - p2.X) * (p3.Y - p2.Y); }; 
			if ( 
				fAB(this.p0, this.p1, this.p3) * fBC(this.p0, this.p1, this.p3) > 0 && 
				fBC(this.p0, this.p1, this.p3) * fCA(this.p0, this.p1, this.p3) > 0 
			) return true; 
			if ( 
				fAB(this.p1, this.p2, this.p3) * fBC(this.p1, this.p2, this.p3) > 0 && 
				fBC(this.p1, this.p2, this.p3) * fCA(this.p1, this.p2, this.p3) > 0 
			) return true; 
			// ---- 
			return false; 
		}; 
		Face.prototype.faceVisible = function () { 
			// ---- points visible ---- 
			if (this.p0.visible && this.p1.visible && this.p2.visible && this.p3.visible) { 
				// ---- back face culling ---- 
				if ((this.p1.Y - this.p0.Y) / (this.p1.X - this.p0.X) < (this.p2.Y - this.p0.Y) / (this.p2.X - this.p0.X) ^ this.p0.X < this.p1.X == this.p0.X > this.p2.X) { 
					// ---- face visible ---- 
					this.visible = true; 
					return true; 
				} 
			} 
			// ---- face hidden ---- 
			this.visible = false; 
			this.distance = -99999; 
			return false; 
		}; 
		Face.prototype.distanceToCamera = function () { 
			// ---- distance to camera ---- 
			var dx = (this.p0.x + this.p1.x + this.p2.x + this.p3.x ) * 0.25; 
			var dy = (this.p0.y + this.p1.y + this.p2.y + this.p3.y ) * 0.25; 
			var dz = (zoom + fl) + (this.p0.z + this.p1.z + this.p2.z + this.p3.z ) * 0.25; 
			this.distance = Math.sqrt(dx * dx + dy * dy + dz * dz); 
		}; 
		Face.prototype.draw = function () { 
			// ---- shape face ---- 
			canvas.ctx.beginPath(); 
			canvas.ctx.moveTo(this.p0.X, this.p0.Y); 
			canvas.ctx.lineTo(this.p1.X, this.p1.Y); 
			canvas.ctx.lineTo(this.p2.X, this.p2.Y); 
			canvas.ctx.lineTo(this.p3.X, this.p3.Y); 
			canvas.ctx.closePath(); 
			// ---- light ---- 
			if (this == faceOver) { 
				var r = 256; 
				var g = 0; 
				var b = 0; 
			} else { 
				// ---- flat (lambert) shading ---- 
				this.normal.projection(); 
				var light = ( 
					white ? 
					this.normal.y + this.normal.z * 0.8 : 
					this.normal.z 
				) * 256; 
				var r = g = b = light; 
			} 
			// ---- fill ---- 
			/*canvas.ctx.fillStyle = "rgba(" + 
								Math.round(r) + "," + 
								Math.round(g) + "," + 
								Math.round(b) + "," + this.cube.alpha + ")"; */
			canvas.ctx.
			canvas.ctx.fill(); 
		}; 
		// ======== Cube constructor ======== 
		var Cube = function(parent, nx, ny, nz, x, y, z, w) { 
			if (parent) { 
				// ---- translate parent points ---- 
				this.w = parent.w; 
				this.points = []; 
				var i = 0, p; 
				while (p = parent.points[i++]) { 
					this.points.push( 
						new Point( 
							parent, 
							[p.xo + nx, p.yo + ny, p.zo + nz], 
							true 
						) 
					); 
				} 
			} else { 
				// ---- create points ---- 
				this.w = w; 
				this.points = []; 
				var p = [ 
					[x-w, y-w, z-w], 
					[x+w, y-w, z-w], 
					[x+w, y+w, z-w], 
					[x-w, y+w, z-w], 
					[x-w, y-w, z+w], 
					[x+w, y-w, z+w], 
					[x+w, y+w, z+w], 
					[x-w, y+w, z+w] 
				]; 
				for (var i in p) this.points.push( 
					new Point(this, p[i], true) 
				); 
			} 
			// ---- faces coordinates ---- 
			var f  = [ 
				[0,1,2,3], 
				[0,4,5,1], 
				[3,2,6,7], 
				[0,3,7,4], 
				[1,5,6,2], 
				[5,4,7,6] 
			]; 
			// ---- faces normals ---- 
			var nv = [ 
				[0,0,1], 
				[0,1,0], 
				[0,-1,0], 
				[1,0,0], 
				[-1,0,0], 
				[0,0,-1] 
			]; 
			// ---- cube transparency ---- 
			this.alpha = alpha ? 0.5 : 1; 			
			//this.alpha = 0.02;
			// ---- push faces ---- 
			for (var i in f) { 
				faces.push( 
					new Face(this, f[i], nv[i]) 
				); 
			} 
			ncube++; 
		}; 
		//////////////////////////////////////////////////////////////////////////// 
		var resize = function () { 
			// ---- screen resize ---- 
			nw = scr.offsetWidth; 
			nh = scr.offsetHeight; 
			var o = scr; 
			for (nx = 0, ny = 0; o != null; o = o.offsetParent) { 
				nx += o.offsetLeft; 
				ny += o.offsetTop; 
			} 
			canvas.resize(nw, nh); 
		}; 
		var reset = function () { 
			// ---- create first cube ---- 
			cubes = []; 
			faces = []; 
			ncube = 0; 
			npoly = 0; 
			cubes.push( 
				new Cube(false,0,0,0,0,0,0,80) 
			); 
		}; 
		var detectFaceOver = function () { 
			// ---- detect pointer over face ---- 
			var j = 0, f; 
			faceOver = false; 
			while ( f = faces[j++] ) { 
				if (f.visible) { 
					if ( f.pointerInside() ) { 
						faceOver = f; 
						//faceOver = "blue";
					} 
				} else break; 
			} 
		}; 
		var click = function () { 
			// ---- click cube ---- 
			detectFaceOver(); 
			if (faceOver) { 
				if (destroy) { 
					if (ncube > 1) { 
						var c = faceOver.cube; 
						faceOver.clicked = false; 
						// ---- destroy faces ---- 
						var i = 0, f; 
						while ( f = faces[i++] ) { 
							if (f.cube == c) { 
								faces.splice(--i, 1); 
								npoly--; 
							} 
						} 
						document.getElementById('npoly').innerHTML = npoly; 
						// ---- destroy cube ---- 
						var i = 0, o; 
						while ( o = cubes[i++] ) { 
							if (o == c) { 
								cubes.splice(--i, 1); 
								ncube--; 
								break; 
							} 
						} 
					} 
				} else { 
					if (!faceOver.clicked) { 

						//alert(faceOver);
						for(var propertyName in faceOver.cube.points){
							console.log(propertyName);
						}
						//alert(faceOver.cube.w);
							

						// ---- create new cube ---- 
						/*
						faceOver.clicked = true; 
						var w = -2.25 * faceOver.cube.w; 
						cubes.push( 
							new Cube( 
								faceOver.cube, 
								w * faceOver.normal.xo, 
								w * faceOver.normal.yo, 
								w * faceOver.normal.zo 
							) 
						); 
						detectFaceOver(); 
						*/
					} 
				} 
			} 
		}; 
		//////////////////////////////////////////////////////////////////////////// 
		var init = function () { 
			// ---- init script ---- 
			scr = document.getElementById("screen"); 
			canvas  = new Canvas("canvas"); 
			// ======== unified touch/mouse events handler ======== 
			scr.ontouchstart = scr.onmousedown = function (e) { 
				if (!running) return true; 
				// ---- touchstart ---- 
				if (e.target !== canvas.container) return; 
				e.preventDefault(); // prevents scrolling 
				if (scr.setCapture) scr.setCapture(); 
				moved = false; 
				drag = true; 
				startX = (e.clientX !== undefined ? e.clientX : e.touches[0].clientX) - nx; 
				startY = (e.clientY !== undefined ? e.clientY : e.touches[0].clientY) - ny; 
			}; 
			scr.ontouchmove = scr.onmousemove = function(e) { 
				if (!running) return true; 
				// ---- touchmove ---- 
				e.preventDefault(); 
				xm = (e.clientX !== undefined ? e.clientX : e.touches[0].clientX) - nx; 
				ym = (e.clientY !== undefined ? e.clientY : e.touches[0].clientY) - ny; 
				detectFaceOver(); 
				if (drag) { 
					cx = cxb + (xm - startX); 
					cy = cyb - (ym - startY); 
				} 
				if (Math.abs(xm - startX) > 10 || Math.abs(ym - startY) > 10) { 
					// ---- if pointer moves then cancel the tap/click ---- 
					moved = true; 
				} 
			}; 
			scr.ontouchend = scr.onmouseup = function(e) { 
				if (!running) return true; 
				// ---- touchend ---- 
				e.preventDefault(); 
				if (scr.releaseCapture) scr.releaseCapture(); 
				drag = false; 
				cxb = cx; 
				cyb = cy; 
				if (!moved) { 
					// ---- click/tap ---- 
					xm = startX; 
					ym = startY; 
					click(); 
				} 
			}; 
			scr.ontouchcancel = function(e) { 
				if (!running) return true; 
				// ---- reset ---- 
				if (scr.releaseCapture) scr.releaseCapture(); 
				moved = false; 
				drag = false; 
				cxb = cx; 
				cyb = cy; 
				startX = 0; 
				startY = 0; 
			}; 
			// ---- Z axis rotation (mouse wheel) ---- 
			scr.addEventListener('DOMMouseScroll', function(e) { 
				if (!running) return true; 
				cz += e.detail * 12; 
				return false; 
			}, false); 
			scr.onmousewheel = function () { 
				if (!running) return true; 
				cz += event.wheelDelta / 5; 
				return false; 
			} 
			// ---- multi-touch gestures ---- 
			document.addEventListener('gesturechange', function(e) { 
				if (!running) return true; 
				e.preventDefault(); 
				// ---- Z axis rotation ---- 
				cz = event.rotation; 
			}, false); 
			// ---- screen size ---- 
			resize(); 
			window.addEventListener('resize', resize, false); 
			// ---- fps count ---- 
			setInterval(function () { 
				document.getElementById('fps').innerHTML = fps * 2; 
				fps = 0; 
			}, 500); // update every 1/2 seconds 
			// ---- some UI options ---- 
			document.getElementById("white").onchange = function () { 
				white = this.checked; 
				if (white) { 
					bkgColor1 = "rgba(256,256,256,0.1)"; 
					bkgColor2 = "rgba(192,192,192,1)"; 
				} else { 
					bkgColor1 = "rgba(0,0,0,0.1)"; 
					bkgColor2 = "rgba(32,32,32,1)"; 
				} 
			} 
			document.getElementById("alpha").onchange = function () { 
				alpha = this.checked; 
			} 
			document.getElementById("autor").onchange = function () { 
				autorotate = this.checked; 
			} 
			document.getElementById("destroy").onchange = function () { 
				destroy = this.checked; 
			} 
			document.getElementById("stopgo").onclick = function () { 
				running = !running; 
				document.getElementById("stopgo").value = running ? "STOP" : "GO!"; 
				if (running) run(); 
			} 
			document.getElementById("reset").onclick = function () { 
				reset(); 
			} 
			// ---- engine start ---- 
			reset(); 
			run(); 
		} 
		//////////////////////////////////////////////////////////////////////////// 
		// ======== main loop ======== 
		var run = function () { 
			// ---- screen background ---- 
			canvas.ctx.fillStyle = bkgColor1; 
			canvas.ctx.fillRect(0, Math.floor(nh * 0.15), nw, Math.ceil(nh * 0.7)); 
			canvas.ctx.fillStyle = bkgColor2; 
			canvas.ctx.fillRect(0, 0, nw, Math.ceil(nh * 0.15)); 
			canvas.ctx.fillStyle = bkgColor2; 
			canvas.ctx.fillRect(0, Math.floor(nh * 0.85), nw, Math.ceil(nh * 0.15)); 
			// ---- easing rotations ---- 
			angleX += ((cy - angleX) * 0.05); 
			angleY += ((cx - angleY) * 0.05); 
			angleZ += ((cz - angleZ) * 0.05); 
			if (autorotate) cz += 1; 
			// ---- pre-calculating trigo ---- 
			cosY = Math.cos(angleY * 0.01); 
			sinY = Math.sin(angleY * 0.01); 
			cosX = Math.cos(angleX * 0.01); 
			sinX = Math.sin(angleX * 0.01); 
			cosZ = Math.cos(angleZ * 0.01); 
			sinZ = Math.sin(angleZ * 0.01); 
			// ---- points projection ---- 
			minZ = 0; 
			var i = 0, c; 
			while ( c = cubes[i++] ) { 
				var j = 0, p; 
				while ( p = c.points[j++] ) { 
					p.projection(); 
				} 
			} 
			// ---- adapt zoom ---- 
			var d = -minZ + 100 - zoom; 
			zoom += (d * ((d > 0) ? 0.05 : 0.01)); 
			// ---- faces light ---- 
			var j = 0, f; 
			while ( f = faces[j++] ) { 
				if ( f.faceVisible() ) { 
					f.distanceToCamera(); 
				} 
			} 
			// ---- faces depth sorting ---- 
			faces.sort(function (p0, p1) { 
				return p1.distance - p0.distance; 
			}); 
			// ---- painting faces ---- 
			j = 0; 
			while ( f = faces[j++] ) { 
					if (f.visible) { 
						f.draw(); 
					} else break; 
			} 
			// ---- animation loop ---- 
			fps++; 
			if (running) setTimeout(run, 16); 
		} 
		return { 
			//////////////////////////////////////////////////////////////////////////// 
			// ---- onload event ---- 
			load : function () { 
				window.addEventListener('load', function () { 
					init(); 
				}, false); 
			} 
		} 
	})().load(); 
</script>

 
</body>
</html>
