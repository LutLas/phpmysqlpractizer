let playBtn = document.querySelectorAll('.playlist .box-container .box .play');

const audioElement = document.getElementById("ruffle-player-embed");
const audioElementCover = document.getElementById("ruffle-player-cover");
const audioElementAlbum = document.getElementById("ruffle-player-album");
const audioElementArtist = document.getElementById("ruffle-player-artist");
const audioElementTitle = document.getElementById("ruffle-player-title");

const closureDiv = document.getElementById("close");
const defaultImage = "/../assets/images/favicon.png";

let mousePos = { x: undefined, y: undefined };

window.addEventListener('mousemove', (event) => {
  mousePos = { x: event.clientX, y: event.clientY };
  audioElement.controlsList = "noplaybackrate nodownload";
});

window.onkeydown = function () {
   console.log('key down');
   audioElement.controlsList = "noplaybackrate nodownload";
};


playBtn.forEach(play =>{

   play.onclick = () =>{

      let src = play.getAttribute('data-src');
      let newSrc = window.atob(src);

      let box = play.parentElement.parentElement;
      let title = box.querySelector('.title');
      let artistName = box.querySelector('.name');
      let albumName = box.querySelector('.artist');
      let albumCoverArt = box.querySelector('.album');

      audioElement.setAttribute("controls", null);
      audioElement.setAttribute("src", newSrc);
      audioElement.controlsList = "noplaybackrate nodownload";

      audioElementTitle.innerHTML = title.innerHTML;
      audioElementArtist.innerHTML = artistName.innerHTML;
      audioElementAlbum.innerHTML = albumName.innerHTML;
      audioElementCover.setAttribute("src", albumCoverArt.src);
      audioElementCover.style.width = '100px';
      audioElementCover.style.height = '100px';
      
      closureDiv.classList.add("navmasterRed","fas","fa-times","largest");

      audioElement.load();
      audioElement.play();

   }
});

document.querySelector('#close').onclick = () =>{
   audioElement.pause();
   audioElement.setAttribute("src", "");
   audioElementTitle.innerHTML = "";
   audioElementArtist.innerHTML = "";
   audioElementAlbum.innerHTML = "";
   audioElementCover.setAttribute("src", defaultImage);
   audioElementCover.style.width = '100px';
   audioElementCover.style.height = '100px';
   closureDiv.classList.remove("navmasterRed","fas","fa-times","largest");
}

audioElement.addEventListener("contextmenu", function(event) {
   event.preventDefault();
});

function callback(mutationList, _) {
   mutationList.forEach( (mutation) => {
       //console.log(mutation, JSON.stringify(mutation))
     switch(mutation.type) {
       case 'childList':
         /* One or more children have been added to and/or removed
            from the tree.
            (See mutation.addedNodes and mutation.removedNodes.) */
         //audioElement.controlsList = "noplaybackrate nodownload";
         break;
       case 'attributes':
         /* An attribute value changed on the element in
            mutation.target.
            The attribute name is in mutation.attributeName, and
            its previous value is in mutation.oldValue. */
            if (!audioElement.hasAttribute("controlsList")){
               audioElement.controlsList = "noplaybackrate nodownload";
            }else{
               const controlsList = audioElement.getAttribute("controlsList")
               if (!controlsList || controlsList !== "noplaybackrate nodownload") {
                  audioElement.controlsList = "noplaybackrate nodownload";
               }
            }
         break;
     }
   });
 }
 
 const observerOptions = {
   childList: true,
   attributes: true,
 
   // Omit (or set to false) to observe only changes to the parent node
   subtree: true
 }
 
 const observer = new MutationObserver(callback);
 observer.observe(audioElement, observerOptions);