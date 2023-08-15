let playBtn = document.querySelectorAll('.playlist .box-container .box .play');
let musicPlayer = document.querySelector('.music-player');
let musicTitle = musicPlayer.querySelector('.title');
let musicAlbum = musicPlayer.querySelector('.album');
let musicName = musicPlayer.querySelector('.name');
let musicArtist = musicPlayer.querySelector('.artist');
let music = musicPlayer.querySelector('.music');


window.RufflePlayer = window.RufflePlayer || {};
//console.log(getPublicDir());
//window.RufflePlayer.config.publicPath = getPublicDir();
window.RufflePlayer.config = {
    // Options affecting the whole page
    "publicPath": undefined,
    "polyfills": true,

    // Options affecting files only
    "allowScriptAccess": false,
    "autoplay": "auto",
    "unmuteOverlay": "visible",
    "backgroundColor": null,
    "wmode": "window",
    "letterbox": "fullscreen",
    "warnOnUnsupportedContent": true,
    "contextMenu": "on",
    "showSwfDownload": false,
    "upgradeToHttps": window.location.protocol === "https:",
    "maxExecutionDuration": 15,
    "logLevel": "error",
    "base": null,
    "menu": true,
    "salign": "",
    "forceAlign": false,
    "scale": "showAll",
    "forceScale": false,
    "frameRate": null,
    "quality": "high",
    "splashScreen": true,
    "preferredRenderer": null,
    "openUrlMode": "allow",
    "allowNetworking": "all",
    "favorFlash": true,
};
         
const audioElement = document.createElement("audio");
const boxDiv = document.createElement('div');
var ruffle = null;
var player = null;

window.addEventListener("load", () => {
    ruffle = window.RufflePlayer.newest();
    player = ruffle.createPlayer();
});

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

         boxDiv.classList.add("box");

         const titleDiv = document.createElement('div');
         titleDiv.classList.add("title");
         boxDiv.appendChild(titleDiv);

         const imgDiv = document.createElement('img');
         imgDiv.classList.add("album");
         boxDiv.appendChild(imgDiv);

         const nameDiv = document.createElement('div');
         nameDiv.classList.add("name");
         boxDiv.appendChild(nameDiv);

         const artistDiv = document.createElement('div');
         artistDiv.classList.add("artist");
         boxDiv.appendChild(artistDiv);

         ////////audio//////
         // audioElement.classList.add("music");
         // audioElement.setAttribute("controls", null);
         // audioElement.controlsList = "noplaybackrate nodownload";
         // boxDiv.appendChild(audioElement);

         player.classList.add("music");
         player.setAttribute("controls", null);
         player.controlsList = "noplaybackrate nodownload";
         boxDiv.appendChild(player);
         ////////audio//////
         
         let src = play.getAttribute('data-src');
         let box = play.parentElement.parentElement;
         let name = box.querySelector('.name');
         let title = box.querySelector('.title');
         let album = box.querySelector('.album');
         let artist = box.querySelector('.artist');

         ////////ruffle//////
         // musicPlayer.appendChild(boxDiv);
         // imgDiv.src = album.src;
         // titleDiv.innerHTML = title.innerHTML;
         // nameDiv.innerHTML = name.innerHTML;
         // artistDiv.innerHTML = artist.innerHTML;
         // audioElement.src = src;

         musicPlayer.appendChild(boxDiv);
         imgDiv.src = album.src;
         titleDiv.innerHTML = title.innerHTML;
         nameDiv.innerHTML = name.innerHTML;
         artistDiv.innerHTML = artist.innerHTML;
         //player.src = src;
         ////////ruffle//////
   
         musicPlayer.classList.add('active');

         player.load(src).then(() => {
            console.info("Ruffle successfully loaded the file");
            //it.play();
         }).catch((e) => {
               console.error(`Ruffle failed to load the file: ${e}`);
         });
      }
});

document.querySelector('#close').onclick = () =>{
   musicPlayer.classList.remove('active');
   player.pause();
   boxDiv.innerHTML = "";
}

audioElement.addEventListener("contextmenu", function(event) {
  event.preventDefault();
});

// function getDir(){
//    var url,foldersAndFile,folders,folderpath,protocol,host,ourLocation;
//    url = window.location;
   
//    foldersAndFile = url.pathname.split("/");
//    folders = foldersAndFile.slice(0,foldersAndFile.length-1);
//    folderpath = folders.join("/");
   
//    protocol = url.protocol+"//";
//    host = url.host;
   
//    ourLocation=protocol+host+folderpath;
   
//    return ourLocation;
// }

function getPublicDir(){
      var url,protocol,host,publicLocation;
      url = window.location;
      
      protocol = url.protocol+"//";
      host = url.host;
      publicUrl = "/public/assets/music/"
      
      publicLocation=protocol+host+publicUrl
      
      return publicLocation;
   }



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


