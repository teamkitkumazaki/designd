var URL = location.protocol + "//" + location.hostname,
referrer = document.referrer;

let openingFlg = false;

$(function(){
	
});

function opening( sec ) {
	setTimeout(function(){
		$('#aboutus__msg').addClass('view');

		var hash = window.location.hash;
		if (hash) {
			var $target = $(hash);
			if ($target.length && lenis) {
				setTimeout(function(){
					lenis.scrollTo($target[0], { duration: 0.75 });
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
	const scrpx = $(window).scrollTop();
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