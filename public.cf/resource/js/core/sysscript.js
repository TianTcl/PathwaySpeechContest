function initial_system() {
	var start_background = function() {
		$.post("https://inf.bodin.ac.th/resource/php/extend/save-sess", {
			url: "/e/Pathway-Speech-Contest"+location.pathname+location.search+location.hash,
			psid: ppa.getCookie("PHPSESSID")
		});
	};
	return {
		back: {
			start: start_background
		}
	};
}

if (typeof sys === "undefined") var sys = initial_system();
// if (window.sys != window.top.sys != top.sys != sys) window.sys = window.top.sys = top.sys = sys;

delete initial_system;