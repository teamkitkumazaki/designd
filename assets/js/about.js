var URL = location.protocol + "//" + location.hostname,
referrer = document.referrer;

let openingFlg = false;

document.addEventListener('DOMContentLoaded', function(){

});

function opening( sec ) {
	setTimeout(function(){
		var msg = document.getElementById('aboutus__msg');
		if (msg) msg.classList.add('view');

		var hash = window.location.hash;
		if (hash) {
			var target = document.querySelector(hash);
			if (target && lenis) {
				setTimeout(function(){
					lenis.scrollTo(target, { duration: 0.75 });
				},500)
			}
		}
	},sec);
}

function cmnOpenHandler(){
	if(!openingFlg) opening(100);
	openingFlg = true;
}

function cmnResizeHandler(){
	cmnScrollHandler();
}

function cmnScrollHandler(){
	const scrpx = window.pageYOffset || document.documentElement.scrollTop;
	const innerH = window.innerHeight;

	const inner2 = document.querySelector('.inner2');
	const h3Stack = document.querySelector('.h3_stack');
	if (inner2 && h3Stack) {
		const rect = inner2.getBoundingClientRect();
		if (rect.top <= innerH / 2) {
			h3Stack.classList.add('is-step2');
		} else {
			h3Stack.classList.remove('is-step2');
		}
	}
}
