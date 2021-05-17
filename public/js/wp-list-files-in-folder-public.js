document.querySelector("#wplfif-tree-root").addEventListener('click', function(ev) {
	el = ev.target;

	if (el.nodeName == 'SPAN' || el.nodeName == 'I') {
		el = el.parentElement;
	}
	sibling = el.nextSibling;

	if (el && el.className == 'folder' && sibling) {
		childrens = el.children;

		for ( var i = 0; i < childrens.length; i++) {
			if (childrens[i].classList.contains('right')) {
				if (childrens[i].classList.contains('dashicons-arrow-left')) {
					childrens[i].classList.remove('dashicons-arrow-left');
					childrens[i].classList.add('dashicons-arrow-down');
				} else {
					childrens[i].classList.remove('dashicons-arrow-down');
					childrens[i].classList.add('dashicons-arrow-left');
				}
			}
		}
		sibling.classList.toggle('show');
		ev.preventDefault();
	}
});