var offerAcceptButton = document.getElementById('offer-accept');

if(offerAcceptButton){
	offerAcceptButton.addEventListener('click', toggleVisibility, false);
}

function toggleVisibility(){
	document.getElementById('offer-accept-content').classList.toggle('visible');
	offerAcceptButton.style.display = 'none';
}