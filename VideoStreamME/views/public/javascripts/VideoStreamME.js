// JavaScript Document
document.addEventListener('DOMContentLoaded', function() {
	var mediaElements = document.querySelectorAll('video, audio'), total = mediaElements.length;

	for (var i = 0; i < total; i++) {
		new MediaElementPlayer(mediaElements[i], {
			pluginPath: 'https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.5/',
			shimScriptAccess: 'always',
			success: function () {
				var target = document.body.querySelectorAll('.player'), targetTotal = target.length;
				for (var j = 0; j < targetTotal; j++) {
					target[j].style.visibility = 'visible';
				}
			}
		});
	}
});
