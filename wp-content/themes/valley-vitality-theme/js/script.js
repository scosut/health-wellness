(function() {
	function getArray(col) {
		return Array.prototype.slice.call(col);
	}
	
	function hideAllMenus(nav, btn) {
		var menus = nav.querySelectorAll(".parent-menu, .child-menu");
		
		getArray(menus).forEach(function(menu) {
			menu.className  = menu.className.replace(/ menu-(visible|hidden)/g, '');
			var isParent    = menu.className.indexOf("parent-menu") >= 0;
			menu.className += " menu-hidden";
			//btn.click(); // add when child menus exist
		});
	}
	
	function checkViewport(hdr, nav, btn) {
		var scrollTop  = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		var iw         = window.innerWidth;
		var inViewport = (iw >= 783 && scrollTop < 400);
		
		if (inViewport) {
			nav.className = 'vp-in';
			hideAllMenus(nav, btn);
			btn.className = "fas fa-bars"; // remove when child menus exist
		}
		else {			
			nav.className = 'vp-out';
		}
	}
	
	function toggleNav(mnu, cls, e) {
		var isHidden   = mnu.className.indexOf("menu-hidden") >= 0;
		mnu.className  = cls + "-menu";
		mnu.className += isHidden ? " menu-visible" : " menu-hidden";
		
		if (e !== undefined) {
			if (cls === "child") {
				e.preventDefault();
			}
			
			if (cls === "parent") {
				e.target.className = isHidden ? "fas fa-times exit" : "fas fa-bars";	
			}
		}
	}
  
  function checkRadio(el, e) {
    e.preventDefault();
    
    if (e.keyCode === 32) {
      el.click();      
    }
  }
	
	function Validate() {
		this.errors = [];
	}
	
	Validate.prototype.isNotEmpty = function(el, msg) {
		if (el.value.trim().length == 0) {
			this.errors.push(msg);
			return false;
		}
		else {
			return true;
		}
	}
	
	Validate.prototype.isChecked = function(el, msg) {
		bln = false;

		for (var i=0; i<el.length; i++) {
			if (el[i].checked) {
				bln = true;
				break;
			}
		}

		if (!bln) {
			this.errors.push(msg);
			return false;
		}
		else {
			return true;
		}
	}
	
	Validate.prototype.isValidEmail = function(el, msg) {
		if (this.isNotEmpty(el, msg)) {
			var bln = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(el.value.trim());
			msg = "'" + el.value.trim() + "' is not a valid email.";
				
			if (!bln) {
				this.errors.push(msg);
				return false;
			}
			else {
				return true;
			}			
		}		
	}
	
	Validate.prototype.isValidPhone = function(el, msg) {
		if (this.isNotEmpty(el, msg)) {
			var bln = el.value.trim().replace(/[^0-9]/g, '').length >= 10;			
			msg = "'" + el.value.trim() + "' is not a valid phone.";
				
			if (!bln) {
				this.errors.push(msg);
				return false;
			}
			else {
				return true;
			}			
		}		
	}
	
	Validate.prototype.getErrors = function() {
		return this.errors.length > 0 ? this.errors : false;
	}
	
	Validate.prototype.showErrors = function(form) {
		if (this.getErrors()) {
			var ul = document.getElementById("errors") || document.createElement("ul");
			var li = document.createElement("li");
			ul.id        = "errors"
			ul.className = "form-group";
			ul.innerHTML = "";
			li.innerHTML = "ERRORS:";
			ul.appendChild(li);
			
			this.getErrors().forEach(function(e) {
				li = document.createElement("li");
				li.innerHTML = e;
				ul.appendChild(li);
			});
			
			if (form.firstElementChild.id != "errors") {
				form.insertBefore(ul, form.firstElementChild);
			}
			
			form.scrollIntoView();
			
			return true;
		}
		else {
			return false;
		}
	}
	
	function validateContactUs(e) {
		var form       = document.getElementById("form");
		var first      = document.getElementById("first");
		var last       = document.getElementById("last");
		var preference = document.getElementsByName("preference");
		var email      = document.getElementById("email");
		var phone      = document.getElementById("phone");
		var problems   = document.getElementById("problems");		
		var val        = new Validate();
		
		e.preventDefault();
		
		val.isNotEmpty(first,     "Please provide the first name.");
		val.isNotEmpty(last,      "Please provide the last name.");
		val.isChecked(preference, "Please select the preferred method of contact.");
				
		if (preference[0].checked || email.value.trim().length > 0) {
			val.isValidEmail(email, "Please provide the email.");
		}
		
		if (preference[1].checked || phone.value.trim().length > 0) {
			val.isValidPhone(phone, "Please provide the phone.");
		}
		
		val.isNotEmpty(problems, "Please provide the problem.");
			
		if (!val.showErrors(form)) {
			form.submit();
		}
	}
	
	function insertExit(con) {
		var i       = document.createElement("i");
		i.id        = "exit";
		i.className = "fas fa-times";
		i.title     = "close";
		i.addEventListener("click", hideAllContent.bind(this, con, [con]));
		con.insertBefore(i, con.firstElementChild);
	}

	function hideAllContent(el, els) {
		els.forEach(function(e) {				
			if (e.hasChildNodes() && e.firstElementChild.id == "exit") {
				e.removeChild(e.firstElementChild);
			}

			if (e.id == "vitality-content") {
				e.style.top = getComputedStyle(e).top;
			}

			e.className = (e.className.length == 0) ? "fadeOutImmediate" : (e.id == el.id || e.id == 'vitality-content') ? "fadeOut" : "fadeOutImmediate";
		});
	}

	function displayContent(el, con, arr) {
		hideAllContent(con, arr);

		if (el.id == 'hotspot-vitality') {
			con.className = "jumpUp";
			setTimeout(function(){location.href='/lets-go/';}, 2000);
			return;
		}

		insertExit(con);	
		con.className = "fadeIn";
		con.style.position = "absolute";
		var top            = window.innerWidth > 959 ? el.offsetTop-5 : 0;
		con.style.top      = top.toString() + 'px';
	}
  
	function events(e) {
		var hdr = document.getElementById("header");
		var nav = document.getElementById("nav");
		var btn = document.getElementById("hamburger");
    var eml = document.getElementById("lblEmail");
    var phn = document.getElementById("lblPhone");
		var sub = document.getElementById("btnSubmit");
		var par = btn.nextElementSibling;
		var chd = par.querySelectorAll(".has-children i");
		var nutrition_hotspot = document.getElementById("hotspot-nutrition");
		var lifestyle_hotspot = document.getElementById("hotspot-lifestyle");
		var movement_hotspot  = document.getElementById("hotspot-movement");
		var vitality_hotspot  = document.getElementById("hotspot-vitality");		
		var nutrition_content = document.getElementById("nutrition-content");
		var lifestyle_content = document.getElementById("lifestyle-content");
		var movement_content  = document.getElementById("movement-content");
		var vitality_content  = document.getElementById("vitality-content");
		var cons = [nutrition_content, lifestyle_content, movement_content, vitality_content];
		
		btn.addEventListener("click", toggleNav.bind(this, par, "parent"));
		par.className += " menu-visible";
		toggleNav(par, "parent");
		
		getArray(chd).forEach(function(c) {
			var mnu = c.parentElement.nextElementSibling;
			c.addEventListener("click", toggleNav.bind(this, mnu, "child"));
			mnu.className += " menu-visible"
			toggleNav(mnu, "child");
		});
		
		nav.className = "vp-in"
		window.addEventListener("scroll", checkViewport.bind(this, hdr, nav, btn));
    
    if (eml) {
      eml.addEventListener("keypress", checkRadio.bind(this, eml));
    }
    
    if (phn) {
      phn.addEventListener("keypress", checkRadio.bind(this, phn));
    }
		
		if (sub) {
			sub.addEventListener("click", validateContactUs);
		}
		
		if (nutrition_hotspot) {		
			nutrition_hotspot.addEventListener("click", displayContent.bind(this, nutrition_hotspot, nutrition_content, cons));
		}
		
		if (lifestyle_hotspot) {		
			lifestyle_hotspot.addEventListener("click", displayContent.bind(this, lifestyle_hotspot, lifestyle_content, cons));
		}
		
		if (movement_hotspot) {
			movement_hotspot.addEventListener("click", displayContent.bind(this, movement_hotspot, movement_content, cons));
		}
		
		if (vitality_hotspot) {
			vitality_hotspot.addEventListener("click", displayContent.bind(this, vitality_hotspot, vitality_content, cons));
		}
	}

	events();
})();